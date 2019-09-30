<?php
/**
 * Created by PhpStorm.
 * User: zhangsai
 * Date: 18-8-24
 * Time: 下午2:47
 */

namespace yuns\file;


class FileDir
{

    public static function  makeDir($path) {

        //preg_split('/[\\] | /', $path);
        $files = preg_split('/[\/|\\\]/s', $path);
        $dir = '';
        foreach ($files as $value) {
            $dir .= $value . DIRECTORY_SEPARATOR;

            if (!file_exists($dir)) {
                mkdir($dir);
            }
        }
    }

}