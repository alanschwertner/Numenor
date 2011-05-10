<?php

class Default_BancoDadosController extends Zend_Controller_Action {

    public function init() {
        $this->_helper->layout->setLayout('layout_config');

        //$this->view->headScript()->appendFile('/js/controller/BancoDados.js');
    }

    public function indexAction() {

        $bancoDados = new ConfigBancoDados();
        $this->view->bancoDados = $bancoDados->fetchAll();
    }

    public function cadastrarAction() {

        // instancia formulario de login
        $formulario = new Form_Config_BancoDados_Cadastrar(); //Default_Form_BancoDados();
        // pega a requisição
        $requisicao = $this->getRequest();
        // verifica se a requisição foi postada
        if ($requisicao->isPost()) {
            // verifica se o formulario for preenchido corretamente
            if ($formulario->isValid($this->_request->getPost())) {

                $dados = array(
                    'id_tipo_banco_dados' => $formulario->getValue('id_tipo_banco_dados'),
                    'host' => $formulario->getValue('host'),
                    'usuario' => $formulario->getValue('usuario'),
                    'senha' => $formulario->getValue('senha'),
                    'database' => $formulario->getValue('database'),
                );

                $bancoDados = new ConfigBancoDados($dados);
                $bancoDados->save();

                // redireciona para a index
                $this->_redirect('/banco-dados/');
            }
        }

        $this->view->formulario = $formulario;
    }

    public function excluirAction() {
        $idBancoDados = intval($this->_getParam("idBancoDados"));

        $configBancoDados = new ConfigBancoDados();
        $configBancoDados->delete($idBancoDados);

        $this->_redirect("/banco-dados/");
    }

    public function visualizarTabelasAction(){

        $gerador = new Numenor_Gerador();
        $this->view->dados = $gerador->getEstruturaBanco();
        
    }
}