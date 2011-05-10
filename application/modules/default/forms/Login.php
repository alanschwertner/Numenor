<?php

class Default_Form_Login extends Zend_Form {

    public function init() {
        /* Form Elements & Other Definitions Here ... */
    }

    public function __construct($option = null) {
        parent::__construct($option);

        //$this->addElementPrefixPath('Numenor_Form_Decorator', 'Numenor/Form/Decorator', 'decorator');

        $this->setName('login')
                ->setMethod('POST')
                ->setAction(Zend_Controller_Front::getInstance()
                        ->getBaseUrl() . '/default/autenticacao');

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

        $this->clearDecorators();

        $this->addDecorator('FormElements')
                ->addDecorator('HtmlTag', array(
                    'tag' => 'ul',
                    'id' => 'form-login',
                ))
                ->addDecorator('Form');

        $this->setElementDecorators(array(
            array('ViewHelper'),
            array('Errors'),
            array('Description'),
            array('Label', array('separator' => ' ')),
            array('HtmlTag', array('tag' => 'li')),
        ));

        // buttons do not need labels
        $botaoSubmit->setDecorators(array(
            array('ViewHelper'),
            array('Description'),
            array('HtmlTag', array('tag' => 'li', 'class' => 'botao')),
        ));


        // $this->setElementDecorators(array('Composite'));
    }

}

