<?php
/**
 * Created by PhpStorm.
 * User: zhangsai
 * Date: 18-8-20
 * Time: 上午11:46
 */

namespace yuns\core;


use yuns\http\HttpRequest;

class WebApplication
{

    /**表示单列对象*/
    static private $Single;

    private $configs = array();

    public $httprequest;
    /**控制器实例*/
    public  $actionInvoke;

    /**
     * 初始化对象
     */
    public static function  getInstance() {

        if ( self::$Single == null ) {

            self::$Single =  new self();
        }

        return self::$Single;
    }

    /**
     * 运行程序
     */
    public function  exect($appConfigs) {

        $this->setConfigs($appConfigs);
        //处理路径请求
        $this->requestInit();
        //获取上面处理过的模块控制器和方法
        $this->actionInvoke();
        //返回结果

        $this->response();
    }

    public function  requestInit() {

         //获取HttpReques初始化请求

        $this->httprequest = new HttpRequest();

        $this->httprequest->parseUrl();
    }

    public function  actionInvoke() {

        //加载控制器Action文件
        $module = $this->httprequest->getModule();
        $action = $this->httprequest->getAction();
        $method = $this->httprequest->getMethod();

        $actionDir = APP_PATH."modules/{$module}/action/";
        $actionFile = ucfirst($action)."Action.class.php";

        $fileName = $actionDir.$actionFile;

        if ( file_exists($fileName)) {

            require $fileName;
            //$str = $action."Action";
            //$className =new \ReflectionClass('App\Admin\Action\LoginAction');
            $className = "App\\".ucfirst($module).'\Action\\'.ucfirst($action)."Action";

            $relect =new \ReflectionClass($className);

            $this->actionInvoke = $relect->newInstance();

            $this->actionInvoke->$method($this->httprequest);

        }
    }

    public function response() {

        $this->actionInvoke->display($this->actionInvoke->getView());
    }

    public function setConfigs($appConfig) {

        $this->configs = $appConfig;
    }

    public function  getConfigs() {

        return $this->configs;
    }

}