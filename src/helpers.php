<?php

if(!function_exists('path_to')) {
    function path_to($path, $to) {
        return str_replace($_ENV['WORDPRESS_URL'], "$to", $path);
    }
}