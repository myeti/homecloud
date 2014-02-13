<?php

namespace My\Logic;

class Error
{

    /**
     * 404 Not found
     * @render views/error.404
     */
    public function lost() {}

    /**
     * 403 Forbidden
     * @render views/error.403
     */
    public function sorry() {}

}