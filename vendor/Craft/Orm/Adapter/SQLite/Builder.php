<?php

namespace Craft\Orm\Adapter\SQLite;

use Craft\Orm\Database\Builder as NativeBuilder;

class Builder extends NativeBuilder
{

    /**
     * Fix <autoincrement> syntax
     */
    public function __construct()
    {
        $this->syntax['primary'] = 'PRIMARY KEY AUTOINCREMENT';
    }

} 