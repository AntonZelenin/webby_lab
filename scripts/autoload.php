<?php

spl_autoload_register(function ($class_name) {
    $array_paths = array(
        ROOT.'/classes/'
    );

    foreach ($array_paths as $path) {
        $file_path = $path.$class_name.'.php';

        if (is_file($file_path)) {
            require $file_path;

            return;
        }
    }

    throw new Exception("Error, file $class_name.php not found", 1);
});
