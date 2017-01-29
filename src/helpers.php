<?php

use Greabock\Maker\Maker;

if (!function_exists('make')) {
    /**
     *
     * @param  string $abstract
     *
     * @return mixed|\Greabock\Maker\Maker
     */
    function make($abstract, $parameters = [])
    {
        if (is_null($abstract)) {
            return app(Maker::class);
        }

        return app(Maker::class)->make($abstract, $parameters);
    }
}