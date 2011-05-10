<?php

class Default_TipoBancoDadosController extends Zend_Controller_Action {

    public function init() {
        $this->_helper->viewRenderer->setNoRender();
    }

    public function indexAction() {

        $categorias = new ConfigTipoBancoDados();

        echo '<pre>';
        print_r($categorias->fetchAll());
        echo '</pre>';
    }

    public function cadastrarAction() {

        $dados = array(
            'nome' => 'asdasdasd',
            'adaptador' => 'meu adaptador'
        );

        $produto = new ConfigTipoBancoDados($dados);
        $produto->save();
        echo $produto->getLastInsertId('config.tipo_banco_dado_id_tipo_banco_dados');
    }

    public function editarAction() {

        $dados = array(
            'id' => 7,
            'nome' => 'asdg aaaaaaaaaaaaa',
            'adaptador' => 'meu adaptador'
        );

        $produto = new ConfigTipoBancoDados($dados);
        $produto->save();
    }

    public function excluirAction() {

    }

}