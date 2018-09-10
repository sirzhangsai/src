<?php
/**
 * Created by PhpStorm.
 * User: zhangsai
 * Date: 18-8-20
 * Time: 下午1:52
 */

namespace yuns\http;


class HttpRequest
{

    public  $requestUrl;

    public $module;

    public $action;

    public $method;

    public $parameters;

    public $defaultUrl = array();

    public function __construct() {

        $this->requestUrl = $_SERVER['REQUEST_URI'];
    }

    public function  parseUrl() {

        //当用浏览器进行www.phpframe.my时$this->requestUrl，而用ps进行输出是null

            $appConfig = getConfig();
            $defaultUrl = $appConfig['default_url'];

            $urlInfo = parse_url($this->requestUrl);

            //获取url中的path部分
            if ( $urlInfo['path'] && $urlInfo['path'] != '/' ) {
               $filename = str_replace(EXT_HTML, '', $urlInfo['path']);
                $filename = rtrim($filename, "/");

                $pathInfo = explode('/', $filename);
               array_shift($pathInfo);

                if ( $pathInfo[0] ) {
                    $this->setModule($pathInfo[0]);
                }

                if ( $pathInfo[1] ) {

                    $this->setAction($pathInfo[1]);
                }

                if ( $pathInfo[2] ) {

                    $this->setMethod($pathInfo[2]);
                }

              /**获取参数*/

              if ( count($pathInfo) > 3 ) {

                  for ( $i = 3; $i < count($pathInfo); $i++) {

                      if ( $i%2 != 0 ) {

                          if ( trim($pathInfo[$i]) == '') {
                              continue;
                          }
                          $_GET[$pathInfo[$i]] = $pathInfo[$i+1];
                      }

                  }

              }

            $this->setParameters($_GET);
            }

        if ( !$this->module ) {
            $this->setModule($defaultUrl['module']);
        }

        if ( !$this->action ) {
            $this->setAction($defaultUrl['action']);
        }

        if ( !$this->method ) {

            $this->setMethod($defaultUrl['method']);
        }

    }

    public function setParameters($parameter) {

        $this->parameters = $parameter;
    }

    public function  input($input) {

        return $this->parameters[$input];
    }

    public function  setModule($model) {

        $this->module = $model;
    }

    public function  setAction($action) {

        $this->action = $action;
    }

    public function  setMethod($method) {

        $this->method = $method;
    }

    public function  getModule() {

        return $this->module;
    }

    public function  getAction() {

        return $this->action;
    }

    public function  getMethod() {

        return $this->method;
    }
}