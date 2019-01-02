<?php

namespace diggu;

/**
 * Class Loader
 * @package diggu
 * @created 2017-04-18 develop@diggu.cn
 * @modified 2017-04-18 develop@diggu.cn
 * @copyright © 2017 www.diggu.cn
 * @contact DP <develop@diggu.cn>
 */
class Loader
{

    /**
     * Psr class container
     * @var array
     */
    protected static $psr4 = [
        'diggu' => __DIR__,
        'diggu\\utils' => __DIR__ . '/utils',
        'diggu\\opensdk' => __DIR__ . '/opensdk',
        'diggu\\agentsdk' => __DIR__ . '/agentsdk'
    ];

    /**
     * Autoload class mapping
     * @var array
     */
    protected static $classes = [];

    /**
     * Bind diggu autoload
     * @retun void
     */
    public static function register()
    {
        spl_autoload_register(['\\diggu\\Loader', 'autoLoad']);
    }

    /**
     * Unbind diggu autoload
     * @retun void
     */
    public static function unRegister()
    {
        spl_autoload_unregister(['\\diggu\\Loader', 'autoLoad']);
    }

    /**
     * Autoload handler
     * @param string $class
     * @return void
     */
    protected static function autoLoad($class)
    {
        if ($cls = self::getClass($class)) {
            require $cls;
        } else if ($cls = self::loadPsr4($class)) {
            require $cls;
        }
    }

    /**
     * find a class real path in registered mapping
     * @param $class
     * @return mixed|null
     */
    public static function getClass($class)
    {
        return isset(self::$classes[$class]) ? self::$classes[$class] : null;
    }

    /**
     * load psr4 style class
     * @param $class
     * @param string $suffix
     * @return mixed|null
     */
    public static function loadPsr4($class, $suffix = '.php')
    {
        $cls = trim(substr($class, strrpos($class, '\\')), '\\');
        $ns = trim(str_replace($cls, '', $class), '\\');
        if (array_key_exists($ns, self::$psr4)) {
            $file = self::$psr4[$ns] . '/' . $cls . $suffix;
            if (file_exists($file)) {
                self::$classes[$class] = $file;
            }
        }
        return self::getClass($class);
    }

}