<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Kernel\Logic;

use Craft\Orm\Syn;

trait CrudLogic
{

    /**
     * Get model alias
     * @return mixed
     */
    abstract protected function getAlias();


	/**
	 * Get all items
	 * @return array
	 */
	public function all()
	{
		$items = Syn::find($this->getAlias());
		return [$this->getAlias() . 's' => $items];
	}

	/**
	 * Get one item
	 * @param  string $id
	 * @return array
	 */
	public function one($id)
	{
		$item = Syn::one($this->getAlias(), $id);
		return [$this->getAlias() => $item];
	}

	/**
	 * Create item
	 * @return array
	 */
	public function create()
	{
		$model = Syn::model($this->getAlias());
		$item = new $model();

		if($post = post()) {
			hydrate($item, $post);
			Syn::save($this->getAlias(), $item);
		}

		return [$this->getAlias() => $item];
	}

	/**
	 * Edit item
	 * @param  string $id
	 * @return array
	 */
	public function edit($id)
	{
		$item = Syn::one($this->getAlias(), $id);

		if($post = post()) {
			hydrate($item, $post);
			Syn::save($this->getAlias(), $item);
		}

		return [$this->getAlias() => $item];
	}

	/**
	 * Delete item
	 * @param  string $id
	 */
	public function delete($id)
	{
		Syn::drop($this->getAlias(), $id);
	}

}