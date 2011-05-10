<?php

class Default_GeradorController extends Zend_Controller_Action {

    public function init() {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout->disableLayout();
    }

    public function indexAction() {
        
    }

    public function zipAction(){
        
        $zip = $this->_getParam('zip');
        
        $result = Numenor_Gerador_File_Gerar::zip($zip);
        
        if ($result){
            $this->_redirect($result);
        }
    }
    
    public function gerarCodigoAction() {

        if ($this->_request->isXmlHttpRequest()) {

            $dadosFormularios = $this->_getParam('dados_formularios');
            $dadosConexao = $this->_getParam('dados_conexao');

            try {

                $gerador = new Numenor_Gerador($dadosConexao);
                $gerador->setDadosFormularios($dadosFormularios);
                if ($gerador->gerar()) {

                    $projeto = $gerador->getProjeto();
                    $zip = Numenor_Gerador_File_Gerar::projeto($projeto);
                    Numenor_CodeColorer_Codigo::getCodeColorerProjeto($projeto);

                    $retorno = array(
                        'retorno' => 'sucesso',
                        'zip' => $zip,
                        'codigo' => $projeto
                    );
                } else {
                    // throw new Zend_Exception();
                }
            } catch (Zend_Exception $e) {
                $retorno = array('retorno' => 'erro', 'msg' => 'Ocorreu algum problema ao gerar código' . $e->getMessage());
            }
        } else {
            $retorno = array('retorno' => 'erro', 'msg' => 'Requisição não é ajax');
        }
        echo Zend_Json_Encoder::encode($retorno);
    }

    public function optionsSelectAction() {

        if ($this->_request->isXmlHttpRequest()) {

            $dadosConexao = $this->_getParam('dados_conexao');

            $schemaTabela = $this->_getParam('schema_tabela');
            $campoValue = $this->_getParam('campo_value');
            $campoText = $this->_getParam('campo_text');



            try {

                $gerador = new Numenor_Gerador($dadosConexao);
                $dadosOptions = $gerador->getOptionsCampoSelect($schemaTabela, $campoValue, $campoText);

                $retorno = array(
                    'retorno' => 'sucesso',
                    'dados' => $dadosOptions
                );
            } catch (Zend_Exception $e) {
                $retorno = array('retorno' => 'erro', 'msg' => 'Ocorreu algum problema ao buscar dados das options');
            }
        } else {
            $retorno = array('retorno' => 'erro', 'msg' => 'Requisição não é ajax');
        }

        echo Zend_Json_Encoder::encode($retorno);
    }

    public function testeConexaoAction() {
        $retorno = array();

        if ($this->_request->isXmlHttpRequest()) {

            $banco = $this->_getParam('banco');

            $dados = array(
                'host' => $this->_getParam('host'),
                'username' => $this->_getParam('usuario'),
                'password' => $this->_getParam('senha'),
                'dbname' => $this->_getParam('dbnome'),
            );

            try {

                $db = Zend_Db::factory($banco, $dados);
                $db->getConnection();

                $retorno = array('retorno' => 'sucesso');
            } catch (Zend_Exception $e) {
                $retorno = array('retorno' => 'erro', 'msg' => $e->getMessage());
            }
        } else {
            $retorno = array('retorno' => 'erro', 'msg' => 'Requisição não é ajax');
        }

        echo Zend_Json_Encoder::encode($retorno);
    }

    public function dadosTabelasAction() {
        $retorno = array();

        if ($this->_request->isXmlHttpRequest()) {
            $dadosConexao = $this->_getParam('dados_conexao');

            try {

                $gerador = new Numenor_Gerador($dadosConexao);
                $dadosBanco = $gerador->getEstruturaBanco();

                $retorno = array(
                    'retorno' => 'sucesso',
                    'dados' => $dadosBanco
                );
            } catch (Zend_Exception $e) {
                $retorno = array('retorno' => 'erro', 'msg' => 'Ocorreu algum problema ao buscar dados do banco');
            }
        } else {
            $retorno = array('retorno' => 'erro', 'msg' => 'Requisição não é ajax');
        }

        echo Zend_Json_Encoder::encode($retorno);
    }

}
