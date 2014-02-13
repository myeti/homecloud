<?php

namespace My\Logic;

use Craft\Env\Flash;
use Craft\Env\Mog;

class Cloud
{


    /**
     * Route cloud action
     * @auth 1
     * @render views/cloud.explore
     * @param null $path
     * @return array
     */
    public function index($path = null)
    {
        // route action
        if($action = Mog::get('action') and $name = Mog::get('name')) {

            // create
            if($action == 'create') {
                $this->create($path, $name);
            }
            // rename
            elseif($action == 'rename' and $to = Mog::get('to')) {
                $this->rename($path, $name, $to);
            }
            // move
            elseif($action == 'move' and $to = Mog::get('to')) {
                $this->move($path, $name, $to);
            }
            // delete
            elseif($action == 'delete') {
                $this->delete($path, $name);
            }
            // preview
            elseif($action == 'preview') {
                $this->preview($path, $name);
            }

            // clear params in url
            go($path);

        }

        return $this->explore($path);
    }


    /**
     * Explore folder
     * @param string $path
     * @return array
     */
    protected function explore($path)
    {
        // make path
        $target = HC_ROOT . rtrim($path, HC_SEP);

        // parse bread
        $bread = [];
        if(!empty($path)) {
            $url = '';
            $segments = explode('/', $path);
            foreach($segments as $segment) {
                $url .= '/' . $segment;
                $bread[$segment] = $url;
            }
        }

        // search under path
        if($query = Mog::get('search')) {
            $directory = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($target));
            $iterator = new \RegexIterator($directory, '#.*(' . preg_quote($query) . ').*#Di');
        }
        // explore current path
        else {
            $iterator = new \FilesystemIterator($target, \FilesystemIterator::SKIP_DOTS);
        }

        return [
            'path'  => $path,
            'bread' => $bread,
            'items' => $iterator
        ];
    }


    /**
     * Create folder
     * @param $path
     * @param $name
     */
    protected function create($path, $name)
    {
        // make path
        $target = HC_ROOT . rtrim($path, HC_SEP) . HC_SEP . ltrim($name, HC_SEP);

        // already exists
        if(file_exists($target)) {
            Flash::set('error', '"' . $name . '" already exists.');
        }
        // unknown error
        elseif(!mkdir($target)) {
            Flash::set('error', 'Something is wrong, cannot create "' . $name . '".');
        }
        // success
        else {
            Flash::set('success', '"' . $name . '" created !');
        }
    }


    /**
     * Rename folder or file
     * @param $path
     * @param $name
     * @param $to
     */
    protected function rename($path, $name, $to)
    {
        // make path
        $source = HC_ROOT . rtrim($path, HC_SEP) . HC_SEP . ltrim($name, HC_SEP);
        $target = HC_ROOT . rtrim($path, HC_SEP) . HC_SEP . ltrim($to, HC_SEP);

        // already exists
        if(file_exists($target)) {
            Flash::set('error', '"' . $to . '" already exists.');
        }
        elseif(!rename($source, $target)) {
            Flash::set('error', 'Something is wrong, cannot rename "' . $name . '".');
        }
        else {
            Flash::set('success', '"' . $name . '" renamed to "' . $to . '" !');
        }
    }


    /**
     * Move folder or file
     * @param $path
     * @param $name
     * @param $to
     */
    protected function move($path, $name, $to)
    {
        // make path
        $source = HC_ROOT . rtrim($path, HC_SEP) . HC_SEP . ltrim($name, HC_SEP);
        $target = HC_ROOT . rtrim($path, HC_SEP) . HC_SEP . trim($to, HC_SEP) . HC_SEP . ltrim($to, HC_SEP);

        // already exists
        if(file_exists($target)) {
            Flash::set('error', '"' . $to . '" already exists.');
        }
        // unknown error
        elseif(!rename($source, $target)) {
            Flash::set('error', 'Something is wrong, cannot move "' . $name . '".');
        }
        // success
        else {
            Flash::set('success', '"' . $name . '" moved to "' . $to . '" !');
        }
    }


    /**
     * Delete folder or file
     * @param $path
     * @param $name
     */
    protected function delete($path, $name)
    {
        // make path
        $source = HC_ROOT . rtrim($path, HC_SEP) . HC_SEP . ltrim($name, HC_SEP);

        // unknown error
        if((is_dir($source) and !rmdir($source)) or !unlink($source)) {
            Flash::set('error', 'Something is wrong, cannot delete "' . $name . '".');
        }
        // success
        else {
            Flash::set('success', '"' . $name . '" deleted !');
        }
    }


    /**
     * Preview file
     * @param $path
     * @param $name
     */
    protected function preview($path, $name)
    {
        // not implemented
    }


    /**
     * Download file
     * @param $path
     * @param $name
     */
    protected function download($path, $name)
    {
        // not implemented
    }

} 