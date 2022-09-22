<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit321b6e8e6b9e8747e67be71d513f68e0
{
    public static $prefixLengthsPsr4 = array (
        'W' => 
        array (
            'WilliamCosta\\DotEnv\\' => 20,
            'WilliamCosta\\DatabaseManager\\' => 29,
        ),
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'WilliamCosta\\DotEnv\\' => 
        array (
            0 => __DIR__ . '/..' . '/william-costa/dot-env/src',
        ),
        'WilliamCosta\\DatabaseManager\\' => 
        array (
            0 => __DIR__ . '/..' . '/william-costa/database-manager/src',
        ),
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit321b6e8e6b9e8747e67be71d513f68e0::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit321b6e8e6b9e8747e67be71d513f68e0::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit321b6e8e6b9e8747e67be71d513f68e0::$classMap;

        }, null, ClassLoader::class);
    }
}
