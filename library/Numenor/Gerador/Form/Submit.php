<?php

/**
 * Classe responsável em gerar o código fonte para o campo Input Submit
 *
 * @author Cristian Cardoso
 * @email ctncardoso@ctncardoso.com.br
 * @version 1.5
 * @data_modificacao 12/03/2011 00:10
 */
class Numenor_Gerador_Form_Submit {

    private $nome;
    private $label;
    private $alinhamentoCampo;
    private $larguraCampo;
    private $codigo;

    public function __construct($campo) {

        $this->nome = 'submit';
        $this->label = $campo['titulo'];
        $this->alinhamentoCampo = $campo['alinhamento'];
        $this->larguraCampo = intval($campo['largura']);
    }

    /**
     * Método responsável em retornar o nome do campo no formato de propriedade
     * 
     * @return string 
     */
    public function getNomeCampoPropriedade() {
        return Numenor_Gerador_Nomes::getNomePropriedade($this->nome);
    }

    /**
     * Retorna o código gerado
     * 
     * @return string 
     */
    public function getCodigo() {
        return $this->codigo;
    }

    public function gerar() {

        $nomePropriedade = $this->getNomeCampoPropriedade();

        $s = '        ';
        $codigo = '';
        $codigo .= $s . "\${$nomePropriedade} = new Zend_Form_Element_Submit('{$this->nome}');\r\n";
        $codigo .= $s . "\${$nomePropriedade}->setLabel('{$this->label}')\r\n";
        $codigo .= $s . "    ->setOptions(array(\r\n";
        $codigo .= $s . "        'attr_linha_form' => array(\r\n";
        $codigo .= $s . "            'class' => '{$this->alinhamentoCampo}'\r\n";
        $codigo .= $s . "        ),\r\n";
        $codigo .= $s . "        'attr_campo' => array(\r\n";
        $codigo .= $s . "            'class' => 'width{$this->larguraCampo}'\r\n";
        $codigo .= $s . "        )\r\n";
        $codigo .= $s . "    ));\r\n";

        $this->codigo = $codigo;

        return true;
    }

}