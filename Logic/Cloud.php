<?php

namespace My\Logic;

use Craft\Env\Flash;
use Craft\Env\Mog;


/**
 * Class Cloud
 * @auth 1
 */
class Cloud
{

    /**
     * Explore folder
     * @render views/cloud.explore
     * @param string $path
     * @return array
     */
    public function explore($path = null)
    {
        // clean
        if($path == '/') {
            $path = null;
        }
        if($path[0] == '/') {
            $path = ltrim($path, '/');
            go(':' . $path);
        }

        // init
        $query = null;
        $iterator = $bread = [];

        // make path
        $target = $this->makePath($path);
        $real = rtrim(realpath($target), HC_SEP) . HC_SEP;

        // not found
        if(strstr($real, HC_ROOT) === false or !is_dir($target)) {
            Flash::set('error', 'Folder "' . $path . '" not found.');
            return [
                'path'  => $path,
                'bread' => $bread,
                'items' => $iterator,
                'query' => $query
            ];
        }

        // parse bread
        if(!empty($path)) {
            $url = '';
            $segments = explode('/', $path);
            foreach($segments as $segment) {
                $url .= '/' . $segment;
                $bread[$segment] = ltrim($url, '/');
            }
        }

        // search under path
        if($query = Mog::post('search')) {
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveRegexIterator(
                    new \RecursiveDirectoryIterator($target, \FilesystemIterator::SKIP_DOTS),
                    '#' . preg_quote($query) . '($|[^' . preg_quote(HC_SEP) . ']+)#i'
                ), true
            );
        }
        // explore current path
        else {
            $iterator = new \FilesystemIterator($target, \FilesystemIterator::SKIP_DOTS);
        }

        return [
            'path'  => $path,
            'bread' => $bread,
            'items' => $iterator,
            'query' => $query
        ];
    }


    /**
     * Create folder
     * @param $path
     */
    public function create($path = null)
    {
        // get data
        if($name = Mog::post('name')) {

            // make path
            $target = $this->makePath($path, $name);

            // already exists
            if(file_exists($target)) {
                Flash::set('error', '"' . $name . '" already exists.');
            }
            // unknown error
            elseif(!mkdir($target, 0777, true)) {
                Flash::set('error', 'Something is wrong, cannot create "' . $name . '".');
            }
            // success
            else {
                Flash::set('success', '"' . $name . '" created !');
            }

        }

        go(':' . $path);
    }


    /**
     * Rename folder or file
     * @param $path
     */
    public function rename($path = null)
    {
        // get data
        if($name = Mog::post('name') and $to = Mog::post('to')) {

            // make path
            $source = $this->makePath($path, $name);
            $target = $this->makePath($path, $to);

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

        go(':' . $path);
    }


    /**
     * Delete folder or file
     * @param $path
     */
    public function delete($path = null)
    {
        // get data
        if($name = Mog::post('name')) {

            // make path
            $source = $this->makePath($path, $name);

            // delete
            $done = $this->wipe($source);

            // unknown error
            if(!$done) {
                Flash::set('error', 'Something is wrong, cannot delete "' . $name . '".');
            }
            // success
            else {
                Flash::set('success', '"' . $name . '" deleted !');
            }

        }

        go(':' . $path);
    }


    /**
     * Recursive delete
     * @param $path
     * @return bool
     */
    protected function wipe($path)
    {
        $current = ($path instanceof \SplFileInfo) ? $path : new \SplFileInfo($path);
        if($current->isDir()) {
            $valid = true;
            $iterator = new \FilesystemIterator($current . HC_SEP, \FilesystemIterator::SKIP_DOTS);
            foreach($iterator as $item) {
                $valid &= $this->wipe($item);
            }
            $valid &= rmdir($path);
            return $valid;
        }

        return unlink($path);
    }


    /**
     * Upload file to path
     * @param $path
     */
    public function upload($path = null)
    {
        // get data
        if($file = Mog::file('file')) {

            // make path
            $target = $this->makePath($path, $file->name);

            // already exists
            if(file_exists($target)) {
                Flash::set('error', '"' . $file->name . '" already exists.');
            }
            // save
            elseif(!move_uploaded_file($file->tmp_name, $target)) {
                Flash::set('error', 'Something is wrong, cannot upload "' . $file->name . '".');
            }
            else {
                Flash::set('success', '"' . $file->name . '" uploaded !');
            }

        }

        go(':' . $path);
    }


    /**
     * Build path
     * @param null $path
     * @param null $name
     * @return string
     */
    protected function makePath($path = null, $name = null)
    {
        $target = HC_SEP . HC_ROOT;

        if($path) { $target .= rtrim($path, HC_SEP) . HC_SEP; }
        if($name) { $target .= ltrim($name, HC_SEP); }

        return $target;
    }

}