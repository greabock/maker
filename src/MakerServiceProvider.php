<?php

namespace Greabock\Maker;

use Illuminate\Support\ServiceProvider;

class MakerServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Maker::class);
    }
}