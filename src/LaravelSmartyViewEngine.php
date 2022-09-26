<?php

namespace YusamHub\LaravelSmarty;

use Illuminate\Contracts\View\Engine;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ViewErrorBag;
use Illuminate\View\Factory;

/**
 * Class SmartyViewEngine
 * @package YusamHub\LaravelSmarty
 */
class LaravelSmartyViewEngine implements Engine
{
    /**
     * @var LaravelSmarty
     */
    protected $smarty;

    /**
     * SmartyEngine constructor.
     * @param LaravelSmarty $smarty
     */
    public function __construct(LaravelSmarty $smarty)
    {
        $this->smarty = $smarty;
    }

    /**
     * @param string $path
     * @param array $data
     * @return string
     * @throws \SmartyException
     */
    public function get($path, array $data = [])
    {
        $params = [];

        foreach ($data as $key => $val) {
            if (!is_object($val)) {
                $params[$key] = $val;
            }
        }

        $this->smarty->assign($params);

        $this->smarty->assign('smarty_debugging', $this->smarty->debugging);
        $this->smarty->assign('smarty_compile_check', $this->smarty->compile_check);

        $this->smarty->assign("smarty_view", [
            'templateBasePath' => $this->smarty->getTemplateDir(0),
            'templatePath' => dirname($path) . DIRECTORY_SEPARATOR,
            'templateFile' => $path,
            'templateFileBody' => str_replace(".tpl","-body.tpl", $path),
        ]);

        return $this->smarty->fetch($path);
    }

    /**
     * @param $data
     * @return Application
     */
    protected function _app($data)
    {
        return $data['app'];
    }

    /**
     * @param $data
     * @return Factory
     */
    protected function _env($data)
    {
        return $data['__env'];
    }

    /**
     * @param $data
     * @return ViewErrorBag
     */
    protected function _error($data)
    {
        return $data['errors'];
    }

}
