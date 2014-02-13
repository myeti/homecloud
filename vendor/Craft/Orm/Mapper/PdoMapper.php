<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Orm\Mapper;

use Craft\Orm\Mapper;
use Craft\Storage\File;

class PdoMapper extends Mapper
{

    /** @var \PDO */
    protected $pdo;

    /** @var string */
    protected $prefix;


    /**
     * Get or set PDO instance
     * @param \PDO|null $pdo
     * @param string|null $prefix
     */
    public function __construct(\PDO $pdo = null, $prefix = null)
    {
        $this->pdo = $pdo;
        $this->prefix = $prefix;
    }


    /**
     * Execute a custom sql request
     * @param  string $query
     * @param  string $cast
     * @return array
     */
    public function query($query, $cast = null)
    {
        // execute
        $result = $cast
            ? $this->pdo->query($query, \PDO::FETCH_CLASS, $this->models[$cast])
            : $this->pdo->query($query, \PDO::FETCH_OBJ);

        return $result->fetchAll();
    }


    /**
     * Count items in collection
     * @param  string $alias
     * @param  array $where
     * @return int
     */
    public function count($alias, array $where = [])
    {
        // prepare sql
        $sql = 'SELECT COUNT(*) FROM `' . $this->prefix . $alias . '`';

        // where clause
        if($where)
        {
            $sql .= ' WHERE 1';
            foreach($where as $field => $condition)
                $sql .= ' AND `' . $field . '` = "' . $condition . '"';
        }

        $count = $this->pdo->query($sql)->fetchColumn();

        return $count;
    }


    /**
     * Find a collection
     * @param  string $alias
     * @param  array $where
     * @param null $orderBy
     * @param null $limit
     * @param null $step
     * @return array
     */
    public function find($alias, array $where = [], $orderBy = null, $limit = null, $step = null)
    {
        // prepare sql
        $sql = 'SELECT * FROM `' . $this->prefix . $alias . '`';

        // where clause
        if($where)
        {
            $sql .= ' WHERE 1';
            foreach($where as $field => $condition)
                $sql .= ' AND `' . $field . '` = "' . $condition . '"';
        }

        // order by clause
        if($orderBy) {

            $sql .= ' ORDER BY';

            if(is_array($orderBy)) {
                foreach($orderBy as $field => $dir)
                    $sql .= ' `' . $field . '` ' . strtoupper($dir);
            }
            else {
                $sql .= ' `' . (string)$orderBy . '`';
            }

        }

        // limit
        if($limit or $limit === 0){

            $sql .= ' LIMIT ' . $limit;

            if($step){
                $sql .= ', ' . $step;
            }

        }

        // execute
        $sth = $this->pdo->prepare($sql);
        $result = $sth->execute();

        // not a select query
        if(!$sth->columnCount()){
            return $result;
        }

        // result set
        $rows = !empty($this->models[$alias])
            ? $sth->fetchAll(\PDO::FETCH_CLASS, $this->models[$alias])
            : $sth->fetchAll(\PDO::FETCH_OBJ);

        return $rows;
    }


    /**
     * Find a specific entity
     * @param  string $alias
     * @param  mixed $where
     * @return object|\stdClass
     */
    public function one($alias, $where = null)
    {
        // id or conditions ?
        if(is_string($where)){
            $where = ['id' => $where];
        }

        // execute
        $results = static::find($alias, $where, null, 1);

        return count($results) ? $results[0] : false;
    }


    /**
     * Persist entity
     * @param string $table
     * @param object $entity
     * @return bool
     */
    public function save($alias, &$entity)
    {
        // cast to object
        $entity = (object)$entity;

        // extract env
        $data = get_object_vars($entity);

        // insert
        if(empty($data['id']))
        {
            // exclude id
            unset($data['id']);

            // prepare sql
            $sql = 'INSERT INTO `' . $this->prefix . $alias . '`';

            // fields
            $sql .= ' (`' . implode('`, `', array_keys($data)) . '`)';

            // values
            $sql .= ' VALUES ("' . implode('", "', $data) . '")';
        }
        // update
        else
        {
            // exclude id
            $id = $data['id'];
            unset($data['id']);

            // prepare sql
            $sql = 'UPDATE `' . $this->prefix . $alias . '` SET ';

            // prepare set
            $set = [];
            foreach($data as $field => $value)
                $set[] = '`' . $field . '` = "' . $value . '"';

            // add values
            $sql .= implode(', ', $set);

            // where clause
            $sql .= ' WHERE `id` = ' . $id;
        }

        // execute
        $result = $this->pdo->exec($sql);

        // re-hydrate object
        if($result) {

            // has id
            $newId = $entity->id ?: $this->pdo->lastInsertId();
            if($newId)
                $entity = static::one($alias, $newId);

        }

        return $result;
    }


    /**
     * Delete entity
     * @param  string $table
     * @param  mixed $entity
     * @return bool|int
     */
    public function drop($alias, $entity)
    {
        // resolve id
        $id = is_object($entity) ? $entity->id : $entity;

        // id needed
        if(!$id){
            return false;
        }

        // prepare sql
        $sql = 'DELETE FROM `' . $this->prefix . $alias . '`';

        // where clause
        $sql .= ' WHERE `id` = ' . $id;

        // execute
        $result = $this->pdo->exec($sql);

        return (bool)$result;
    }


    /**
     * Sync model with database
     * @return \PDOStatement
     */
    public function merge()
    {
        // init schema
        $tables = [];
        foreach($this->models as $name => $model) {
            $tables[$name] = $this->schema($name);
        }

        // create tables
        $query = [];

        foreach($tables as $name => $details) {

            // create table if not exists
            $subquery = 'create table if not exists `' . $this->prefix . $name . '` (';

            foreach($details as $field => $prop) {
                $subquery .= '`' . $field . '` ';

                // define type
                switch($prop) {
                    case 'int' : $type = 'int'; break;
                    case 'string' : $type = 'varchar(255)'; break;
                    case 'string email' : $type = 'varchar(255)'; break;
                    case 'string text' : $type = 'text'; break;
                    case 'string date' : $type = 'date';  break;
                    case 'string datetime' : $type = 'datetime';  break;
                    default: $type = 'varchar(255)'; break;
                }

                $subquery .= $type . ' ';

                // id ?
                $subquery .= ($field == 'id') ? ' not null auto_increment,' : ' default null,';

            }

            $subquery .= 'primary key (`id`));';

            // add to general query
            $query[] = $subquery;

        }

        // alter fields
        foreach($tables as $name => $details) {

            // alter table
            $subquery = 'alter table `' . $this->prefix . $name . '` ';

            foreach($details as $field => $prop) {
                $subquery .= 'modify `' . $field . '` ';

                // define type
                switch($prop) {
                    case 'int' : $type = 'int'; break;
                    case 'string' : $type = 'varchar(255)'; break;
                    case 'string email' : $type = 'varchar(255)'; break;
                    case 'string text' : $type = 'text'; break;
                    case 'string date' : $type = 'date';  break;
                    case 'string datetime' : $type = 'datetime';  break;
                    default: $type = 'varchar(255)'; break;
                }

                $subquery .= $type . ' ';

                // id ?
                $subquery .= ($field == 'id') ? ' not null auto_increment,' : ' default null,';

            }

            // add to general query
            $query[] = $subquery;
        }

        return (bool)static::query(implode("\n", $query));
    }


    /**
     * Create a backup of the database in sql
     * @param $filename string
     * @return string
     */
    public function backup($filename)
    {

        // get table list
        $tables = [];
        foreach($this->pdo->query('show tables')->fetchAll() as $row)
            $tables[] = $row[0];

        // init backup sql
        $backup = '';

        // table structure
        foreach($tables as $table) {

            // create table
            $backup .= 'DROP TABLE IF EXISTS `' . $table . '`;' . "\n";
            $backup .= 'CREATE TABLE `' . $table . '` (' . "\n";

            // get fields
            $fields = $this->pdo->query('describe `' . $table . '`')->fetchAll();

            // build lines
            $lines = [];
            foreach($fields as $field) {

                // new line
                $line = "\t";

                // name and type
                $line .= '`' . $field['Field'] . '` ' . $field['Type'];

                // default value
                if($field['Default'])
                    $line .= ' DEFAULT ' . $field['Default'];

                // null value
                if($field['Null'] == 'NO')
                    $line .= ' NOT NULL';

                // extra value
                if($field['Extra'])
                    $line .= ' ' . $field['Extra'];

                // primary key
                if($field['Key'] == 'PRI')
                    $line .= ' PRIMARY KEY';

                // push line
                $lines[] = $line;
            }

            $backup .= implode($lines, ",\n") . "\n);\n\n";
        }

        // env backup
        foreach($tables as $table) {

            // find env
            $lines = [];
            foreach($this->find($table) as $row) {

                // new line
                $line = 'INSERT INTO `' . $table . '` (`';

                // add fields
                $fields = array_keys(get_object_vars($row));
                $line .= implode($fields, '`, `');

                // add values
                $line .= '`) VALUES (';
                $values = get_object_vars($row);

                // escape input
                foreach($values as $k => $v){
                    $values[$k] = $this->pdo->quote($v);
                }
                $line .= implode($values, ', ');

                // close and push line
                $line .= ');';
                $lines[] = $line;

            }

            $backup .= implode($lines, "\n") . "\n\n";

        }

        // write backup in file
        return File::write($filename, $backup);
    }

}