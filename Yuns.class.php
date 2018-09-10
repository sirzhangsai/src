<?php
/**
 * Created by PhpStorm.
 * User: zhangsai
 * Date: 18-8-20
 * Time: 上午9:13
 */

include APP_FRAME_PATH.'core/Loader.class.php';

use yuns\core\Loader;
use yuns\core\WebApplication;

class Yuns
{
    /**
     * 存错框架的基础类
     */

    static protected  $libClass = array();

    /**
     * store user class
     */
    static protected  $appClass = array();
    /**
     * 加载配置文件
     */
    static protected  $appConfig = array();

    public static function run() {

        //设置错误等级
        error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE & ~E_WARNING);

        //加载框架核心类
        self::_autolodBaseClass();

        $appConfigs = Loader::config('app');
        $begin =  WebApplication::getInstance() ;
        $begin->exect($appConfigs);

    }

    //
    public static function  _autolodBaseClass() {

        self::$libClass = [

            'yuns\core\WebApplication' => 'core.WebApplication',
            'yuns\http\HttpRequest' => 'http.HttpRequest',
            'yuns\core\Loader' => 'core.Loader',
            'yuns\core\Controller' =>'core.Controller',
            'yuns\core\Template' => 'core.Template',
            'yuns\file\FileDir' => 'file.FileDir',
            'yuns\db\DBFactory' => 'db.DBFactory',
            'yuns\db\mysql\SingleDB' => 'db.SingleDB',
            'yuns\model\MysqlModel' => 'model.MysqlModel',
            'yuns\cache\CacheFactory' => 'cache.CacheFactory',
            'yuns\cache\FileCache' => 'cache.FileCache',
            'yuns\cache\RedisCache' => 'cache.RedisCache',
           // 'yuns\cache\Cache'

        ];

        self::$appConfig = Loader::config('model');
    }

    public static function aotuload($classPath) {

        if (self::$libClass[$classPath] ) {

            //echo self::$libClass[$classPath]."\n";
            Loader::import(self::$libClass[$classPath], IMPRONT_FRAME, EXT_PHP);
        }
        else {
            Loader::import(self::$appConfig[$classPath], IMPRONT_APP, EXT_FILE);
        }

    }

}

spl_autoload_register(array('Yuns', 'aotuload'));