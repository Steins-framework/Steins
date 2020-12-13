<?php

if (! file_exists('join_path')){
    function join_path($path, $other): string
    {
        if (! str_ends_with($path, '/')){
            $path .= '/';
        }
        $path = $path . $other;

        return str_replace('//', '/', $path);
    }
}