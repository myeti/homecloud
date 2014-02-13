<?php

namespace My\Logic;

class Front
{

    /**
     * Landing page
     * @auth 1
     */
    public function hello()
    {
        go('/explore');
    }

}