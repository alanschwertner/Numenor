<?php

class Default_AutenticacaoController extends Zend_Controller_Action {

    public function init() {
        $this->view->headTitle('Login', 'PREPEND');
        $this->_helper->layout->setLayout('login');
    }

    public function indexAction() {
    }
    

    public function logarAction() {

        $retorno = array();

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        if ($this->_request->isXmlHttpRequest()) {

            $usuario = $this->_request->getParam("usuario");
            $senha = $this->_request->getParam("senha");

            $authAdapter = $this->_getAutenticacaoAdapter();
            // seta valores para efetuar a verificação
            $authAdapter->setIdentity($usuario)
                    ->setCredential($senha);
            // pega o objeto de autenticação
            $auth = Zend_Auth::getInstance();
            // seta seta os dados para autenticar
            $resultado = $auth->authenticate($authAdapter);

            // verifica se a autenticação esta correta
            if ($resultado->isValid()) {
                // se estiver correta guarda a autenticação
                $identity = $authAdapter->getResultRowObject();
                $authStorage = $auth->getStorage();
                $authStorage->write($identity);

                $retorno = array('retorno' => 'sucesso');
            } else {
                $retorno = array('retorno' => 'erro', 'msg' => 'Usuário ou Senha inválidos');
            }
        } else {
            $retorno = array('retorno' => 'erro', 'msg' => 'Requisição não é ajax');
        }

        echo Zend_Json_Encoder::encode($retorno);
    }

    public function logoutAction() {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout->disableLayout();

        Zend_Auth::getInstance()->clearIdentity();
        $this->_redirect('/default/autenticacao');
    }

    private function _getAutenticacaoAdapter() {
        $autenticacao = new Zend_Auth_Adapter_DbTable(Zend_Db_Table::getDefaultAdapter());
        // seta a tabela, campo usuario e campo senha, onde devera ser consultado as informações
        $autenticacao->setTableName('autenticacao')
                ->setIdentityColumn('usuario')
                ->setCredentialColumn('senha')
                ->setCredentialTreatment('tipo_usuario <> ""');
        // retorna o objeto
        return $autenticacao;
    }

    public function editarUsuarioAction() {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout->disableLayout();

        $db = Zend_Db_Table::getDefaultAdapter();

        if ($this->_request->isXmlHttpRequest()) {

            $dados = array(
                'usuario' => $this->_getParam('usuario'),
                'senha' => $this->_getParam('senha')
            );

            try {

                $db->update('autenticacao', $dados, 'id_autenticacao = 1');
                $retorno = array('retorno' => 'sucesso');
            } catch (Zend_Exception $e) {
                $retorno = array('retorno' => 'erro', 'msg' => 'Ocorreu algum problema ao editar dados do usuário');
            }
        } else {
            $retorno = array('retorno' => 'erro', 'msg' => 'Requisição não é ajax');
        }

        echo Zend_Json_Encoder::encode($retorno);
    }

}

