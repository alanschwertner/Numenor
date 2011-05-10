<?php

class Default_IndexController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {

        //$dbSL = Zend_Db::factory('pdo_sqlite', array('dbname' => APPLICATION_PATH . "/config/numenor.db"));
//
//        $db = Zend_Db_Table::getDefaultAdapter();
//        
//         $select = $db->select()->from('autenticacao');
//
//        print_r($select->query()->fetchAll());
        //$sql = 'select * from autenticacao';
        // action body
        //$gerador = new Numenor_Gerador();
        //$this->view->dadosBanco = Zend_Json::encode($gerador->getEstruturaBanco());
    }

    public function testeAction() {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout->disableLayout();

        Numenor_Gerador_File_Gerar::zip('2011_04_18_07_01_35');


        exit;

        $banco = array(
            'nome schema' => array(
                'nome tabela' => array(
                    'nome campo' => array(
                        'tipo' => 'tipo de campo',
                        'nulo' => 'true | false',
                        'tamanho' => 'quantidade de caracteres',
                        'chave_primaria' => 'true | false',
                        'chave_estrangeira' => 'true | false',
                        'referencia' => array(
                            'nome_schema_ref' => 'nome do schema',
                            'nome_tabela_ref' => 'nome da tabela',
                            'nome_campo_ref' => 'nome do campo',
                            'nome_chave_ref' => 'nome da chave de ligação'
                        )
                    )
                )
            )
        );

        //echo $codigo;
    }

    public function formAction() {
        $this->_helper->layout->disableLayout();

//        $formulario = new Form_Exemplo();
//
//        $this->view->form = $formulario;
    }

}
