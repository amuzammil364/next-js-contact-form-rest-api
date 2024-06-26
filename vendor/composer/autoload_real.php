<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInit20b0ad42854b1df1b72f0e71c2702939
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        spl_autoload_register(array('ComposerAutoloaderInit20b0ad42854b1df1b72f0e71c2702939', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInit20b0ad42854b1df1b72f0e71c2702939', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInit20b0ad42854b1df1b72f0e71c2702939::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
