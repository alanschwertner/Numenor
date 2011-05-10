<?php

/**
 * Classe responsável em montar a estrutura HTML personalizad para utilizar
 * com Zend_Form
 * 
 * Versão: 1.6
 * Criado Por: Cristian Cardoso - ctncardoso@gmail.com
 * Data Modificação: 28/04/2011 10:20:00
 */
class Numenor_Form_Decorator_Composite extends Zend_Form_Decorator_Abstract {

    /**
     * Método responsável em retornar os atributos formatados ou o valor de um único atributo
     * 
     * @param string $tipoAttr Tipo de atributo a ser retornado ja formatado
     * @param string $atributo caso informado retornara apenas o valor do atributo
     * @return string retorna os atributos ja formatados ou apenas o valor de um unico atributo
     */
    private function getAtributosFormatado($tipoAttr, $atributo = null) {
        $element = $this->getElement();
        $attrs = $element->getAttribs();

        $attrFormatado = '';

        if (isset($attrs[$tipoAttr])) {
            foreach ($attrs[$tipoAttr] as $key => $value) {
                
                if (empty($atributo)){
                    $attrFormatado .= empty($attr)
                    ? $key . '="' . $value . '"'
                    : ' ' . $key . '="' . $value . '"';
                } else if ($atributo == $key) {
                    return $value;
                }
                
            }
        }

        return $attrFormatado;
    }

    /**
     * Responsável em retornar um array contendo os atributos
     * 
     * @param string $tipoAttr Tipo de atributo a ser retornado
     * @return array 
     */
    private function getAtributosArray($tipoAttr) {
        $element = $this->getElement();
        $attrs = $element->getAttribs();

        if (isset($attrs[$tipoAttr])) {
            return $attrs[$tipoAttr];
        } else {
            return array();
        }
    }

    /**
     * Método monta a linha do formulário contendo label, campo, mensagens.
     * 
     * @return string Retorna o HTML da linha
     */
    private function getLinhaForm() {

        $codigo = '';
        $codigo .= '<div class="linha-form">';
        $codigo .= $this->getLabel();
        $codigo .= $this->getCampo();
        $codigo .= $this->getDescription();
        $codigo .= $this->getErrors();
        $codigo .= '</div>';

        return $codigo;
    }

    /**
     * Responsável em montar o campo com as informações definidas
     * 
     * @return string Retorna a estrutura HTML do campo
     */
    private function getCampo() {

        $element = $this->getElement();

        $helper = $element->helper;

        $codigo = '';
        $codigo .= $element->getView()->$helper(
                $element->getName(),
                $element->getValue(),
                $this->getAtributosArray('attr_campo'),
                $element->options
        );

        return $codigo;
    }

    /**
     * Responsável em montar o label do campo
     * 
     * @return string Retorna a estrutura HTML do label
     */
    private function getLabel() {
        $element = $this->getElement();
        $attr = $this->getAtributosFormatado('attr_label');

        $codigo = '';
        $codigo .= '<label for="' . $element->getName() . '"';
        $codigo .= ( empty($attr)) ? '>' : ' ' . $attr . '>';

        if ($translator = $element->getTranslator()) {
            $codigo .= $translator->translate($element->getLabel());
        } else {
            $codigo .= $element->getLabel();
        }

        if ($element->isRequired()) {
            $codigo .= '<span class="campo-requerido">*</span>';
        }

        $codigo .= '</label>';

        return $codigo;
    }

    /**
     * Monta a estrutura HTML do erro
     * 
     * @return string Retorna a estrutura HTML do erro
     */
    private function getErrors() {
        $element = $this->getElement();
        $messages = $element->getMessages();
        if (empty($messages)) {
            return '';
        }
        return '<div class="errors">' . $element->getView()->formErrors($messages) . '</div>';
    }

    /**
     *
     * @return string 
     */
    private function getDescription() {
        $element = $this->getElement();
        $desc = $element->getDescription();
        if (empty($messages)) {
            return '';
        }
        return '<div class="description">' . $desc . '</div>';
    }

    /**
     * Responsávem em montar linha do formulário que contenha um botão
     * 
     * @return string Retorna a estrutura HTML da linha do formulário
     */
    private function getButton() {
        $element = $this->getElement();
        $value = $element->getValue();

        if (empty($value)) {
            $element->setValue($element->getLabel());
        }

        $attrLinhaForm = $this->getAtributosFormatado('attr_linha_form', 'class');

        $codigo = '';

        if (empty($attrLinhaForm)) {
            $codigo .= '<div class="linha-form">';
        } else {
            $codigo .= '<div class="linha-form ' . $attrLinhaForm . '">';
        }

        $codigo .= $this->getCampo();
        $codigo .= $this->getDescription();
        $codigo .= $this->getErrors();
        $codigo .= '</div>';

        return $codigo;
    }
    

    /**
     * Responsável em renderizar o campo do formulário
     * 
     * @param type $content
     * @return string Retorna a estrutura HTML da linha do formulário 
     */
    public function render($content) {
        $element = $this->getElement();

        if (!$element instanceof Zend_Form_Element) {
            return $content;
        }
        if (null === $element->getView()) {

            return $content;
        }

        $separator = $this->getSeparator();
        $placement = $this->getPlacement();

        if ($element instanceof Zend_Form_Element_Submit) {
            $output = $this->getButton();
        } else if ($element instanceof Zend_Form_Element_Button) {
            $output = $this->getButton();
        } else if ($element instanceof Zend_Form_Element_Hidden) {
            $output = $this->getCampo();
        } else {
            $output = $this->getLinhaForm();
        }

        switch ($placement) {
            case (self::PREPEND):
                return $output . $separator . $content;
            case (self::APPEND):
            default:
                return $content . $separator . $output;
        }
    }

}