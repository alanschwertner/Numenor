<?php

class Default_Form_Formulario extends Zend_Form {

    public function init() {
        /* Form Elements & Other Definitions Here ... */
    }

    public function __construct($option = null) {
        parent::__construct($option);

        $this->addElementPrefixPath('Decorators', 'Decorators/', 'decorator');


        $this->setName('login')
                ->setMethod('POST')
                ->setAction(Zend_Controller_Front::getInstance()
                        ->getBaseUrl() . '/index/form');

        $usuario = new Zend_Form_Element_Text('usuario');
        $usuario->setLabel('Usuário:')
                ->setRequired(true)
                ->setOptions(array('class' => 'width150 ui-widget-content ui-corner-all'));

        $senha = new Zend_Form_Element_Password('senha');
        $senha->setLabel('Senha:')
                ->setRequired(true)
                ->setOptions(array('class' => 'width150 ui-widget-content ui-corner-all'));

        $botaoSubmit = new Zend_Form_Element_Submit('login');
        $botaoSubmit->setLabel('Login')
                ->setOptions(array('class' => 'width80 botaoA ui-widget-content ui-corner-all '));


        $this->addElements(array(
            $usuario,
            $senha,
            $botaoSubmit
        ));

        $this->setElementDecorators(array('Composite'));
    }

}

