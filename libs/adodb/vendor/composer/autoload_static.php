<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit840a49e047c350f3ccd6d5488adea868
{
    public static $files = array (
        'bf9f5270ae66ac6fa0290b4bf47867b7' => __DIR__ . '/..' . '/adodb/adodb-php/adodb.inc.php',
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticInit840a49e047c350f3ccd6d5488adea868::$classMap;

        }, null, ClassLoader::class);
    }
}
