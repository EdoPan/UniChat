<?php
require('smarty-libs/Smarty.class.php');

/**
 * Classe che gestisce le operazioni preliminari per richiedere l'utilizzo di Smarty.
 */
class VSmarty
{

    /**
     * Imposta le cartelle di lavoro di Smarty e ne restituisce un'istanza.
     * @return Smarty Istanza Smarty.
     */
    public static function start(): Smarty
    {
        $smarty=new Smarty();
        $smarty->setTemplateDir('smarty-dir/templates');   // directory dei template
        $smarty->setCompileDir('smarty-dir/templates_c');
        $smarty->setCacheDir('smarty-dir/cache');
        $smarty->setConfigDir('smarty-dir/configs');
        return $smarty;
    }

}