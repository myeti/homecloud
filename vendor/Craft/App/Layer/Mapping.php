<?php

namespace Craft\App\Layer;

use Craft\Error\NotFound;
use Craft\App\Layer;
use Craft\App\Request;
use Craft\Orm\Syn;
use Forge\Logger;

/**
 * Inject a model when @map is specified.
 *
 * Needs Layer\Metadata
 */
class Mapping extends Layer
{

    /** @var callable[] */
    protected $seekers = [];


    /**
     * Define model seeker
     * @param $model
     * @param callable $seeker
     * @return $this
     */
    public function define($model, callable $seeker)
    {
        $this->seekers[$model] = $seeker;
        return $this;
    }


    /**
     * Handle request
     * @param Request $request
     * @throws NotFound
     * @throws \InvalidArgumentException
     * @return Request
     */
    public function before(Request $request)
    {
        // mapping requested
        if(!empty($request->meta['map'])) {

            // parse meta (@map My\Model:prop)
            list($model, $property) = explode(':', $request->meta['map']);

            // get entity
            $entity = isset($this->seekers[$model])
                ? call_user_func_array($this->seekers[$model], [$request, $property])
                : Syn::one($model, [$property => $request->args[$property]]);

            // not found
            if(!$entity) {
                throw new NotFound($model . '[' . $property . ':' . $request->args[$property] . '] not found.');
            }

            // replace property with entity
            $request->args[$property] = $entity;
            Logger::info('App.Mapping : map model ' . $model . ' into $' . $property);
        }

        return $request;
    }

}