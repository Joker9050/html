<?php

/**
 * Convenient helpers in case you prefer to use them instead of the facades
 */

if (! function_exists('html')) {

    /**
     * @return \Illuminate\Foundation\Application|mixed
     */
    function html()
    {
        return app('html');
    }
}

if (! function_exists('form')) {
    /**
     * @return \Illuminate\Foundation\Application|mixed
     */
    function form()
    {
        return app('form');
    }
}

if (! function_exists('field')) {
    function field()
    {
        return app('field');
    }
}

if (! function_exists('alert')) {
    /**
     * Creates a new alert message (alias of Alert::message)
     *
     * @param string $message
     * @param string $type
     * @param array $args
     * @return string
     */
    function alert($message = '', $type = 'success', $args = [])
    {
        return app('alert')->message($message, $type, $args);
    }
}

if (! function_exists('menu')) {
    /**
     * Generates a new menu (alias of Menu::make)
     *
     * @param \Closure $items
     * @return string
     */
    function menu(Closure $items)
    {
        return app('menu')->make($items);
    }
}

if (! function_exists('html_classes')) {
    /**
     * Builds an HTML class attribute dynamically.
     *
     * @param array $classes
     * @param bool $addClassAttribute
     * @return string
     */
    function html_classes(array $classes, $addClassAttribute = true)
    {
        return app('html')->classes($classes, $addClassAttribute);
    }
}
