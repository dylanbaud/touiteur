<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit4a6e9f5ec6ae56e55fe843d27b3835f6
{
    public static $prefixLengthsPsr4 = array (
        'i' => 
        array (
            'iutnc\\touiteur\\' => 15,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'iutnc\\touiteur\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/classes',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit4a6e9f5ec6ae56e55fe843d27b3835f6::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit4a6e9f5ec6ae56e55fe843d27b3835f6::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit4a6e9f5ec6ae56e55fe843d27b3835f6::$classMap;

        }, null, ClassLoader::class);
    }
}
