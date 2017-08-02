<?php

function __autoload($class_name)
{

    $array_paths = array(
        ROOT.'/classes/'
    );

    foreach ($array_paths as $path) {
        $file_path = $path.$class_name.'.php';

        if (is_file($file_path)) {
            require $file_path;
        } else {
            throw new Exception("Error, file $file_path does not exist", 1);
        }
    }
}
