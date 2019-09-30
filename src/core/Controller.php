<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/22
 * Time: 20:52
 */

namespace yuns\core;

/**
 * 抽象基类所有控制器都要继承
 *
 */
use yuns\core\Template;
abstract class Controller  extends Template
{

    /**
     * 视图变量
     */
    protected $view = null;

    public function C_start() {}

    /**
     * 渲染模板函数
     *
     */

    public function  setView($string){

        $this->view = $string;
    }

    public function  getView() {

        return $this->view;
    }

    /**
     * 析构函数
     *
     */

    public function  __destruct() {

    }
}