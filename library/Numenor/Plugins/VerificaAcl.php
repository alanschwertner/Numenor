<?php

class Numenor_Plugins_VerificaAcl extends Zend_Controller_Plugin_Abstract {

    private $_acl = null;

    public function __construct(Zend_Acl $acl) {
        $this->_acl = $acl;
    }

    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request) {

        $modulo = $request->getModuleName(); // retorna o nome do modulo
        $controller = $request->getControllerName(); // retorna o nome do controller
        $acao = $request->getActionName(); // retorna o nome da acao
        // verifica se o usuario logado tem perissao em determinado controller e determinada acao
        if (!$this->_acl->isAllowed(Zend_Registry::get('tipo_usuario'), $modulo . ':' . $controller, $acao)) {
            // redireciona a requisicao para o login caso o usuario nao tenha permissao
            $request->setModuleName('default')
                    ->setControllerName('autenticacao')
                    ->setActionName('index');
        }
    }

}