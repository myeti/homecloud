<?php

namespace My\Logic;

use Craft\Remote\MailServer;
use Forge\Flash;
use Forge\Mog;


/**
 * Class Cloud
 * @auth 1
 */
class Cloud
{

    /**
     * Explore folder
     * @render views/cloud.explore
     * @param string $input
     * @return array
     */
    public function explore($input = null)
    {
        // #bug : get Request sometimes instead of string
        $path = is_object($input) ? '' : (string)$input;

        // clean
        if($path == '/') {
            $path = null;
        }
        if(isset($path[0]) and $path[0] == '/') {
            $path = ltrim($path, '/');
            go(':' . $path);
        }

        // decode
        if($path) {
            $path = rawurldecode($path);
        }

        // init
        $query = null;
        $items = $bread = [];

        // make path
        $target = $this->makePath($path);
        $real = rtrim(realpath($target), HC_SEP) . HC_SEP;

        // not found
        if(strstr($real, HC_ROOT) === false or !is_dir($target)) {
            Flash::set('error', 'Folder "' . $path . '" not found.');
        }
        else {

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
                $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($target, \FilesystemIterator::SKIP_DOTS));
                $iterator = new \RegexIterator($files, '#' . preg_quote($query) . '($|[^' . HC_SEP . ']+)#i');
                $items = iterator_to_array($iterator, true);
            }
            // explore current path
            else {
                $iterator = new \FilesystemIterator($target, \FilesystemIterator::SKIP_DOTS);
                $items = iterator_to_array($iterator, true);
            }

        }

        ksort($items);

        return [
            'path'  => $path,
            'bread' => $bread,
            'items' => $items,
            'query' => $query
        ];
    }


    /**
     * Create folder
     * @param string $input
     */
    public function create($input = null)
    {
        // #bug : get Request sometimes instead of string
        $path = is_object($input) ? '' : (string)$input;

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
     * @param string $input
     */
    public function rename($input = null)
    {
        // #bug : get Request sometimes instead of string
        $path = is_object($input) ? '' : (string)$input;

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
     * @param string $input
     */
    public function delete($input = null)
    {
        // #bug : get Request sometimes instead of string
        $path = is_object($input) ? '' : (string)$input;

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
     * @param string $input
     */
    public function upload($input = null)
    {
        // #bug : get Request sometimes instead of string
        $path = is_object($input) ? '' : (string)$input;

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
     * @param string $path
     * @param string $name
     * @return string
     */
    protected function makePath($path = null, $name = null)
    {
        // decode
        if($path) {
            $path = rawurldecode($path);
        }

        $target = HC_SEP . ltrim(HC_ROOT, HC_SEP);

        if($path) { $target .= rtrim($path, HC_SEP) . HC_SEP; }
        if($name) { $target .= ltrim($name, HC_SEP); }

        return $target;
    }

}