<?php

namespace Techone\Lib\Helper;

use Smarty;

/**
 *  Smarty para renderizar os templates
 */
class SmartyTechone extends Smarty
{
    public function __construct()
    {
        parent::__construct();

        $this->setTemplateDir(BASE_DIR . 'Resources/templates');
        $this->setCompileDir(BASE_DIR . 'View/Smarty/templates_c');
        $this->setConfigDir(BASE_DIR . 'View/Smarty/configs');
        $this->setCacheDir(BASE_DIR . 'View/Smarty/cache');

        $this->caching = false;
        //$this->cache_lifetime = 120;

        //Pastas CSS e IMG
        $this->assign('css_dir', CSS_DIR);
        $this->assign('img_dir', IMG_DIR);
    }
}
