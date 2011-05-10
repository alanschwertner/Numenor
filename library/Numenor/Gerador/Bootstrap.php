<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

    protected function _initAutoLoader() {

        $autoloader = Zend_Loader_Autoloader::getInstance();
        $autoloader->setFallbackAutoloader(true); // Pega tudo
    }

    protected function _initTranslate() {

        $translator = new Zend_Translate(array(
                    'adapter' => 'array',
                    'content' => '../library/Numenor/Form/Translate',
                    'locale' => 'pt_BR',
                    'scan' => Zend_Translate::LOCALE_DIRECTORY)
        );
        Zend_Validate_Abstract::setDefaultTranslator($translator);
    }
}
