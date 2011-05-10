<?php

class Default_TipoValidacaoController extends Zend_Controller_Action {

    private $db;

    public function init() {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout->disableLayout();
        $this->db = Zend_Db_Table::getDefaultAdapter();
    }

    public function indexAction() {

        $select = $this->db->select()->from('tipo_validacao');
        $dados = $select->query()->fetchAll();

        $retorno = array('retorno' => 'sucesso', 'dados' => $dados);
        echo Zend_Json_Encoder::encode($retorno);
    }

    public function cadastrarAction() {
        if ($this->_request->isXmlHttpRequest()) {

            $id_tipo_validacao = $this->_getParam('id_tipo_validacao');

            $dados = array(
                'nome' => $this->_getParam('nome'),
                'nome_logico' => $this->_getParam('nome_logico'),
                'expressao_regular' => $this->_getParam('expressao_regular')
            );

            try {

                if (empty($id_tipo_validacao)) {
                    $this->db->insert('tipo_validacao', $dados);
                    $id = $this->db->lastInsertId();

                    $retorno = array(
                        'retorno' => 'sucesso',
                        'id_tipo_validacao' => $id,
                        'msg' => 'Dados inseridos com sucesso!'
                    );
                } else {
                    $where = $this->db->quoteInto('id_tipo_validacao = ?', $id_tipo_validacao);
                    $this->db->update('tipo_validacao', $dados, $where);
                    $retorno = array('retorno' => 'sucesso', 'msg' => 'Dados editados com sucesso!');
                }
            } catch (Zend_Exception $e) {
                $retorno = array('retorno' => 'erro', 'msg' => 'Ocorreu algum problema ao gravar os dados');
            }
        } else {
            $retorno = array('retorno' => 'erro', 'msg' => 'Requisição não é ajax');
        }
        echo Zend_Json_Encoder::encode($retorno);
    }

    public function excluirAction() {
        if ($this->_request->isXmlHttpRequest()) {

            $id_tipo_validacao = $this->_getParam('id_tipo_validacao');
            try {

                if (!empty($id_tipo_validacao)) {

                    $where = $this->db->quoteInto('id_tipo_validacao = ?', $id_tipo_validacao);
                    $this->db->delete('tipo_validacao', $where);

                    $retorno = array('retorno' => 'sucesso', 'msg' => 'Dados excluido com sucesso!');
                } else {
                    $retorno = array('retorno' => 'erro', 'msg' => 'Id não informado.');
                }
            } catch (Zend_Db_Exception $e) {
                $retorno = array('retorno' => 'erro', 'msg' => 'Ocorreu algum problema ao gravar os dados');
            }
        } else {
            $retorno = array('retorno' => 'erro', 'msg' => 'Requisição não é ajax');
        }
        echo Zend_Json_Encoder::encode($retorno);
    }

}
