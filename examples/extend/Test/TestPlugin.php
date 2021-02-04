<?php

namespace App\Plugins\Test;

use Oneso\LaravelPlugins\Plugin;

class TestPlugin extends Plugin
{
    public $name = 'Test';

    public function boot()
    {
        $this->enableViews();
        $this->enableRoutes();
    }
}
