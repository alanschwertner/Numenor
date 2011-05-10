<?php

class Form_Exemplo extends Zend_Form {

    public function __construct($option = null) {
        parent::__construct($option);

        $this->addElementPrefixPath('Numenor_Form_Decorator', 'Numenor/Form/Decorator/', 'decorator');

        $dadosOption = array(
        );

        $ttuloSelect = new Zend_Form_Element_Select('ttulo_select_');
        $ttuloSelect->setLabel('Título Select:')
                ->setRequired(false)
                ->addMultiOptions($dadosOption)
                ->setOptions(array(
                    'attr_label' => array(
                        'class' => 'width150 Left'
                    ),
                    'attr_campo' => array(
                        'class' => 'width201'
                    )
                ));

        $ttuloInputText = new Zend_Form_Element_Text('ttulo_input_text_');
        $ttuloInputText->setLabel('Título Input Text:')
                ->setRequired(false)
                ->setOptions(array(
                    'attr_label' => array(
                        'class' => 'width150 Left'
                    ),
                    'attr_campo' => array(
                        'class' => 'width200',
                        'valida' => 'texto'
                    )
                ));

        $ttuloTextarea = new Zend_Form_Element_Textarea('ttulo_textarea_');
        $ttuloTextarea->setLabel('Título Textarea:')
                ->setRequired(false)
                ->setOptions(array(
                    'attr_label' => array(
                        'class' => 'width150 Left'
                    ),
                    'attr_campo' => array(
                        'class' => 'width200 height50'
                    )
                ));

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Salvar')
                ->setOptions(array(
                    'attr_linha_form' => array(
                        'class' => 'Center'
                    ),
                    'attr_campo' => array(
                        'class' => 'width92'
                    )
                ));

        $this->addElements(array(
            $ttuloSelect,
            $ttuloInputText,
            $ttuloTextarea,
            $submit,
        ));

        $this->setDecorators(array(
            'FormElements',
            array(
                array(
                    'data' => 'HtmlTag'
                ),
                array(
                    'tag' => 'div',
                    'class' => 'formulario'
                )
            ),
            'Form'
        ));

        $this->setElementDecorators(array('Composite'));
    }

}