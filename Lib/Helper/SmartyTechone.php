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
        $this->setCompileDir('../../smarty/templates_c');
        $this->setConfigDir('../../smarty/configs');
        $this->setCacheDir('../../smarty/cache');

        $this->caching = false;
        //$this->cache_lifetime = 120;

        //Pastas CSS e IMG
        $this->assign('css_dir', CSS_DIR);
        $this->assign('img_dir', IMG_DIR);
    }
}
