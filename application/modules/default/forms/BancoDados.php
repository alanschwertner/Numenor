<?php

class Default_Form_BancoDados extends Zend_Form {

    public function init() {
        /* Form Elements & Other Definitions Here ... */
    }

    public function __construct($option = null) {
        parent::__construct($option);

        $this->setName('config_db')
                ->setMethod('POST')
                ->setAction(Zend_Controller_Front::getInstance()
                        ->getBaseUrl('/autenticacao/login'));

        $tipoBancoDado = new ConfigTipoBancoDados();
        $dadosOption = array('' => 'Selecione') + $tipoBancoDado->getNomeTipoBancoDados();

        $banco = new Zend_Form_Element_Select('banco');
        $banco->setLabel('Banco:')
                ->setRequired(true)
                ->setValidators(array('NotEmpty'))
                ->setOptions(array('class' => 'width250S ui-widget-content ui-corner-all'))
                ->addMultiOptions($dadosOption);

        $host = new Zend_Form_Element_Text('host');
        $host->setLabel('Host:')
                ->setRequired(true)
                ->setValidators(array('NotEmpty'))
                ->setOptions(array('class' => 'width250 ui-widget-content ui-corner-all'));

        $usuario = new Zend_Form_Element_Text('usuario');
        $usuario->setLabel('Usuário:')
                ->setRequired(true)
                ->setValidators(array('NotEmpty'))
                ->setOptions(array('class' => 'width250 ui-widget-content ui-corner-all'));

        $senha = new Zend_Form_Element_Text('senha');
        $senha->setLabel('Senha:')
                ->setRequired(true)
                ->setValidators(array('NotEmpty'))
                ->setOptions(array('class' => 'width250 ui-widget-content ui-corner-all'));

        $db_nome = new Zend_Form_Element_Text('db_nome');
        $db_nome->setLabel('DB Nome:')
                ->setRequired(true)
                ->setValidators(array('NotEmpty'))
                ->setOptions(array('class' => 'width250 ui-widget-content ui-corner-all'));

        $botaoSubmit = new Zend_Form_Element_Button('login');
        $botaoSubmit->setLabel('Enviar')
                ->setOptions(array(
                    'class' => 'width80 botaoA ui-widget-content ui-corner-all',
                    'id' => 'submitForm'
                ));

        $this->addElements(array(
            $banco,
            $host,
            $usuario,
            $senha,
            $db_nome,
            $botaoSubmit
        ));

        $this->setElementDecorators(array(
            'viewHelper',
            'Errors',
            array(array('data' => 'HtmlTag'), array('tag' => 'td')),
            array('Label', array('tag' => 'td')),
            array(array('row' => 'HtmlTag'), array('tag' => 'tr'))
        ));

        $this->setDecorators(array(
            'FormElements',
            array(array('data' => 'HtmlTag'), array('tag' => 'table', 'class' => 'formulario')),
            'Form'
        ));

        $botaoSubmit->setDecorators(array(
            array('ViewHelper'),
            array('Description'),
            array('HtmlTag', array('tag' => 'td', 'class' => 'Right', 'colspan' => '2')),
        ));
    }

}

