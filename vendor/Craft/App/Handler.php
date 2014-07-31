<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\App;

/**
 * Universal handler :
 * receives a Request and
 * returns a Response.
 */
interface Handler
{

    /**
     * Handle context request
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function handle(Request $request, Response $response = null);

} 