<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Orm\Pdo;

class SQLite extends \PDO
{

    /**
     * Init for sqlite
     * @param $filename
     */
    public function __construct($filename)
    {
        parent::__construct('sqlite:' . $filename);
    }

} 