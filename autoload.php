<?php

/**
 * Fichier d'autoload des différentes classes du plugin d'import
 * Respecte les normes PSR-0 et PSR-4
 */

function autoload_framework_backend_form($className)
{
    $className = ltrim($className, '\\');
    $fileName  = '';
    $namespace = '';
    if ($lastNsPos = strripos($className, '\\')) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace).DIRECTORY_SEPARATOR;
    }
    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className).'.php';

    if (file_exists(__DIR__.'/'.$fileName)) {
        require __DIR__.'/'.$fileName;
    }
}

spl_autoload_register('autoload_framework_backend_form');
