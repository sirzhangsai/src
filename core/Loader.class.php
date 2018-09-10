<?php
/**
 * Created by PhpStorm.
 * User: zhangsai
 * Date: 18-8-20
 * Time: 下午2:43
 */

namespace yuns\core;


class Loader
{
    /**
     * store globe config
     */

    static protected  $fileConfig = array();
    /**
     * store class file
     */
    static protected  $classFile = array();

    /**
     * create single object
     *
     */
    static protected $single =array();
    /**
     * 加载全局配置文件
     */
    public static function config($app) {



            $dir = APP_ROOT.'www/config/'.$app.'.php';
            if (file_exists($dir)) {

                self::$fileConfig[$app] = include $dir;

                return self::$fileConfig[$app];
            } else {

                return '';
            }

    }
    /**
     * 加载程序运行时的类
     */
    public static function  import($className, $type = IMPRONT_FRAME, $ext = EXT_PHP)
    {

        if (!$className) {
            return false;
        }

        $classKey = $className . $ext;
        if (isset(self::$classFile[$classKey])) {

            return false;
        }
       $path = IMPRONT_FRAME;
        switch ( $type ) {

            case IMPRONT_FRAME:
                $path = APP_FRAME_PATH;
                break;

            case IMPRONT_APP:
                $path = APP_PATH;
                break;
            default:

                break;
        }


        $classPath = str_replace('.', '/', $className);

        $newClassPath = $path.$classPath.$ext;
//var_dump($newClassPath);
        if (file_exists($newClassPath)) {

         require  $newClassPath;
        } else {

            echo "465没有相应的类文件";
        }

        self::$classFile[$classKey] = true;

        return true;
    }

    /**
     * 实例化单体
     */
    public static function  singleInstance($classPath) {

        if ( !isset(self::$single[$classPath]) ) {
            $reflect = new \ReflectionClass($classPath);
            self::$single[$classPath] = $reflect->newInstance();
        }

        return self::$single[$classPath];
    }

    /**
     * 加载模型
     */

    public static function  model($modelNamePath) {

        return self::singleInstance($modelNamePath);

    }

    /**
     * 加载服务层
     */
    public static function  service($serviceNamePath) {

        return self::singleInstance($serviceNamePath);
    }
}