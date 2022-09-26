<?php

namespace YusamHub\LaravelSmarty;

use Illuminate\Support\Facades\App;

class LaravelSmartyDefaultComposer
{
    public function compose(\Illuminate\View\View $view): void
    {
        $view->with("LaravelSmartyDefaultComposer", [
            'appIsDebug' => (bool) config('app.debug')
        ]);
    }
}