<?php
require('smarty-libs/Smarty.class.php');
class VSmarty
{
    public static function start(): Smarty{
        $smarty=new Smarty();
        $smarty->setTemplateDir('smarty-dir/templates');   // directory dei template
        $smarty->setCompileDir('smarty-dir/templates_c');
        $smarty->setCacheDir('smarty-dir/cache');
        $smarty->setConfigDir('smarty-dir/configs');
        return $smarty;
    }
}