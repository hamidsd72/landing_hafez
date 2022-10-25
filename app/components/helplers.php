<?php

if (! function_exists('assets')) {
    /**
     * Generate an asset path for the application.
     *
     * @param  string  $path
     * @param  bool|null  $secure
     * @return string
     */
    function assets($path)
    {
        return url('/').env('APP_URL').('/'.$path);
    }
}
