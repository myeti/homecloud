<?php

namespace Craft\Orm\Model;

use Craft\Orm\Model;

trait Flagged
{

    use Model;

    /** @var int */
    public $id;

    /** @var string date */
    public $created;

    /** @var string date */
    public $updated;

} 