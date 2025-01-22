<?php
spl_autoload_register(function ($class_name) {
    $directories = [
        __DIR__ . '/../Classes/',
        __DIR__ . '/../pages/',
        __DIR__ . '/../includes/'
    ];

    foreach ($directories as $directory) {
        $file = $directory . $class_name . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});
?>