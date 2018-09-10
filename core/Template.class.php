<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/22
 * Time: 20:55
 */

namespace yuns\core;

use yuns\file\FileDir;
class Template
{

    /**
     * 临时存放变量
     *
     */
    private $templeVar = array();

    /**
     * 模板目录
     */
    private $fileDir = '';
    /**
     * 编译目录
     */
    private $compileDir = '';

    /**
     * 模板编译缓存配置
     */
    private $cache;

    /**
     * 模板编译文件
     */
    protected $compileFile = null;
    /**
     * 模板编译规则
     */
    private static $tempRules = array(
        /**
         * 输出变量,数组
         * {$varname}, {$array['key']}
         */
        '/{\$([^\}|\.]{1,})}/i' => '<?php echo \$${1}?>',
        /**
         * 以 {$array.key} 形式输出一维数组元素
         */
        '/{\$([0-9a-z_]{1,})\.([0-9a-z_]{1,})}/i'	=> '<?php echo \$${1}[\'${2}\']?>',
        /**
         * 以 {$array.key1.key2} 形式输出二维数组
         */
        '/{\$([0-9a-z_]{1,})\.([0-9a-z_]{1,})\.([0-9a-z_]{1,})}/i'	=> '<?php echo \$${1}[\'${2}\'][\'${3}\']?>',

        //for 循环
        '/{for ([^\}]+)}/i'	=> '<?php for ${1} {?>',
        '/{\/for}/i'    => '<?php } ?>',

        /**
         * foreach key => value 形式循环输出
         * foreach ( $array as $key => $value )
         */
        '/{loop\s+\$([^\}]{1,})\s+\$([^\}]{1,})\s+\$([^\}]{1,})\s*}/i'   => '<?php foreach ( \$${1} as \$${2} => \$${3} ) { ?>',
        '/{\/loop}/i'    => '<?php } ?>',

        /**
         * foreach 输出
         * foreach ( $array as $value )
         */
        '/{loop\s+\$(.*?)\s+\$([0-9a-z_]{1,})\s*}/i'	=> '<?php foreach ( \$${1} as \$${2} ) { ?>',
        '/{\/loop}/i'	=> '<?php } ?>',

        /**
         * {run}标签： 执行php表达式
         * {expr}标签：输出php表达式
         * {url}标签：输出格式化的url
         * {date}标签：根据时间戳输出格式化日期
         * {cut}标签：裁剪字指定长度的字符串,注意截取的格式是UTF-8,多余的字符会用...表示
         */
        '/{run\s+(.*?)}/i'   => '<?php ${1} ?>',
        '/{expr\s+(.*?)}/i'   => '<?php echo ${1} ?>',
        '/{url\s+(.*?)}/i'   => '<?php echo url("${1}") ?>',
        '/{date\s+(.*?)(\s+(.*?))?}/i'   => '<?php echo $this->getDate(${1}, "${2}") ?>',
        '/{cut\s+(.*?)(\s+(.*?))?}/i'   => '<?php echo $this->cutString(${1}, "${2}") ?>',

        /**
         * if语句标签
         * if () {} elseif {}
         */
        '/{if\s+(.*?)}/i'   => '<?php if ( ${1} ) { ?>',
        '/{else}/i'   => '<?php } else { ?>',
        '/{elseif\s+(.*?)}/i'   => '<?php } elseif ( ${1} ) { ?>',
        '/{\/if}/i'    => '<?php } ?>',

        /**
         * 导入模板
         * require|include
         */
        '/{(require|include)\s{1,}([0-9a-z_\.\:]{1,})\s*}/i'
        => '<?php include $this->getIncludePath(\'${2}\')?>',

        '/{(res)\s+([^\}]+)\s*}/i'
        => '<?php echo $this->getResourceURL(\'${2}\')?>',

        /**
         * 引入静态资源 css file,javascript file
         */
        '/{(res):([a-z]{1,})\s+([^\}]+)\s*}/i'
        => '<?php echo $this->importResource(\'${2}\', "${3}")?>'
    );


    public function  __construct() {

        $this->fileDir = APP_PATH.'modules/admin/template/';
        $this->compileDir = APP_ROOT.'runtime/admin/';
    }

    /**
     * 设置模板变量
     *
     */

    public function  assign($key, $value) {

        $this->templeVar[$key] = $value;
    }

    public function  getTempleVar($key) {

        return $this->templeVar[$key];
    }

    public function  getTempleVars() {

        return $this->templeVar;
    }


    public function display($staticFile) {

        $tempFile = $staticFile.EXT_HTML;

        $compileFile = $tempFile.".php";

        if (file_exists($this->fileDir.$tempFile)) {

            $this->compileTemplate($this->fileDir.$tempFile, $this->compileDir.$compileFile);

            extract($this->templeVar);
            include  $this->compileDir.$compileFile;
        }



    }

    /**
     * 编译模板文件
     */

    public function  compileTemplate($tempFile, $compile) {
          //后面的一个条件判断就是说如果一个文件被修改了那么就要重新编译


        if (!file_exists($compile) || filemtime($tempFile) > filemtime($compile)) {
            $contents = file_get_contents($tempFile);

         echo filemtime($tempFile) > filemtime($compile);
            if ($contents == false ) {

                echo "没有相应的模板文件";
                die;
            }
            $content = preg_replace(array_keys(self::$tempRules), self::$tempRules, $contents);
            //生成编辑目录
            if (!file_exists(dirname($compile))) {
                FileDir::makeDir(dirname($compile));
            }
            //在重新写入文件时要注意权限问题
           if (!file_put_contents($compile, $content, LOCK_EX)) {
                echo "重新编译失败!";
                die;
           };
        }
    }

    public function &getExecutedHtml( $tempFile ) {

        ob_start();
        $this->display( $tempFile );
        $html = ob_get_contents();
        ob_end_clean();
        return  $html;

    }

}