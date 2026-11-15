<?php

/**
 * Autoload sencillo para este proyecto sin Composer.
 * Cada clase/interfaz se encuentra en un archivo con el mismo nombre.
 */
spl_autoload_register(static function (string $class): void {
    $base = __DIR__;
    $directories = [
        'Bootstrap', 'Controllers', 'Database', 'Events', 'Legacy',
        'Models', 'Notifications', 'Pricing', 'Reports', 'Repositories',
        'Security', 'Services',
    ];

    foreach ($directories as $directory) {
        $file = $base . DIRECTORY_SEPARATOR . $directory
            . DIRECTORY_SEPARATOR . $class . '.php';

        if (is_file($file)) {
            require_once $file;
            return;
        }
    }
});