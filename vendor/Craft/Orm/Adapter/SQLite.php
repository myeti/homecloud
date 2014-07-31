<?php

namespace Craft\Orm\Adapter;

use Craft\Orm\Database;

class SQLite extends Database
{

    /**
     * SQLite connector
     * @param string $filename
     */
    public function __construct($filename)
    {
        // create pdo instance
        $pdo = new \PDO('sqlite:' . $filename);
        parent::__construct($pdo);

        // custom builder
        $this->builder = new SQLite\Builder;
    }

} 