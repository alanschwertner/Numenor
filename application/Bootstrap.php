<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

    protected function _initAutoLoader() {

        $autoloader = Zend_Loader_Autoloader::getInstance();
        $autoloader->setFallbackAutoloader(true); // Pega tudo

        $autoLoad = new Zend_Application_Module_Autoloader(
                        array(
                            'namespace' => "Default",
                            'basePath' => APPLICATION_PATH . '/modules/default/'
                        )
        );

        return $autoLoad;
    }

    protected function _initAcl() {

        // verifica se esta logado
        if (Zend_Auth::getInstance()->hasIdentity()) {
            // caso esteja logado registra o tipo de usuario
            // o ultimo item desta linha é o nome do campo da tabela
            Zend_Registry::set('tipo_usuario', Zend_Auth::getInstance()->getStorage()->read()->tipo_usuario);
        } else {
            // difine o tipo de usuario caso nao esteja logado
            Zend_Registry::set('tipo_usuario', 'visitantes');
        }

        // responsavel em fazer a verificação se o usuario esta logado ou nao
        $this->_acl = new LibraryAcl(); // retorna a ACL lista de permissões

        $frontController = Zend_Controller_Front::getInstance();
        $frontController->registerPlugin(new Numenor_Plugins_VerificaAcl($this->_acl)); // registra o plugin para poder utilizalo
    }

    protected function _initViews() {

        $this->bootstrap('view');
        $view = $this->getResource('view');

        //$view = $layout->getView();
        $view->doctype('XHTML1_STRICT');
        $view->setEncoding('utf-8');
        Zend_Registry::set('view', $view);

        /* habilita Jquery */
        /* $view->setHelperPath(APPLICATION_PATH . '/helpers', '');
          ZendX_JQuery::enableView($view);
         */

        $view->addHelperPath('ZendX/Jquery/View/Helper', 'ZendX_Jquery_View_Helper');

        /* Define o formato da moeda */
        //$currency = new Zend_Currency('pt_BR');
        //$currency->setFormat(array('symbol' => 'R$ '));
        //Zend_Registry::set('currency', $currency);

        /* define qual o caracter que sera o separador juntamente com o titulo */
        $view->headTitle()->setSeparator(' - ')->headTitle('Númenor');

        /* define a(s) META TAG */
        $view->headMeta()->appendHttpEquiv('Content-Type', 'text/html; charset=utf-8');
    }

    protected function _initNavigation() {
        $view = Zend_Registry::get('view');

        $navConfig = new Zend_Config_Xml(APPLICATION_PATH . '/configs/menu_cabecalho.xml', 'nav');
        $navContainer = new Zend_Navigation($navConfig);

        $view->navigation($navContainer);
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

    protected function _initPlugins() {
        $bootstrap = $this->getApplication();

        if ($bootstrap instanceof Zend_Application) {
            $bootstrap = $this;
        }

        $bootstrap->bootstrap('FrontController');
        $front = $bootstrap->getResource('FrontController');

        $front->registerPlugin(new Numenor_Plugins_Layout());
    }

//    protected function _initDb() {
//
//        if ($this->hasPluginResource("db")) {
//            $dbResource = $this->getPluginResource("db");
//            $db = $dbResource->getDbAdapter();
//
//            $sql = "CREATE TABLE tipo_validacao (
//                        id_tipo_validacao INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
//                        nome varchar(255) NOT NULL,
//                        nome_logico varchar(255) NOT NULL,
//                        expressao_regular varchar(255) NOT NULL
//                    );";
//
//            $db->query($sql);
//
//            Zend_Registry::set("db", $db);
//        }
//    }

}

