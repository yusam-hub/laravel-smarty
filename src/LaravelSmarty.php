<?php

namespace YusamHub\LaravelSmarty;

use Illuminate\Contracts\View\Factory;
use Smarty;

/**
 * Class LaravelSmarty
 * @package YusamHub\LaravelSmarty
 */
final class LaravelSmarty extends Smarty
{
    /**
     * @var Factory
     */
    protected Factory $viewFactory;

    /**
     * @param Factory $factory
     */
    public function setViewFactory(Factory $factory)
    {
        $this->viewFactory = $factory;
    }

    /**
     * @return Factory
     */
    public function getViewFactory(): Factory
    {
        return $this->viewFactory;
    }
}
