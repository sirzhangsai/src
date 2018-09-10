<?php
/**
 * Created by PhpStorm.
 * User: zhangsai
 * Date: 18-8-20
 * Time: 下午5:46
 */

function getConfig() {

     $configInfo = \yuns\core\WebApplication::getInstance();

     return $configInfo->getConfigs();
}