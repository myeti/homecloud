<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\App\Preset;

use Craft\Kernel\App;
use Craft\Reflect\Action;
use Craft\View\Viewable;

class Bubble extends App
{

    /** @var string */
    protected $dir;

    /** @var string */
    protected $ext;


    /**
     * Setup static web
     * @param array $dir
     * @param string $ext
     * @param array $helpers
     */
    public function __construct($dir, $ext = 'php', array $helpers = [])
    {
        // set config
        $config = [
            'template.dir'  => $dir,
            'template.ext'  => $ext,
            'template.helpers' => $helpers + $this->config['template.helpers']
        ];

        // routing
        $routes = [
            '/'     => [$this, 'page'],
            '/:id'  => [$this, 'page']
        ];

        // init dispatcher
        parent::__construct($routes, $config);

        // intercept template
        $this->on('dispatcher.render', function(Viewable $view, Action $action){
            $action->metadata['render'] = $action->data['template'];
        });

        // 404
        $this->on(404, function(){
            $this->plug('/404');
        });
    }

    /**
     * Render static page
     * @param string $page
     * @return string
     */
    public function page($page = 'index')
    {
        return ['template' => $page];
    }

} 