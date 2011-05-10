<?php

/**
 * Classe responsável em gerar o código fonte para o campo Select
 *
 * @author Cristian Cardoso
 * @email ctncardoso@ctncardoso.com.br
 * @version 1.5
 * @data_modificacao 12/03/2011 00:10
 */
class Numenor_Gerador_Form_Select {

    private $nome;
    private $nomeVar;
    private $label;
    private $labelLargura;
    private $labelAlinhamento;
    private $requerido;
    private $valorPadrao;
    private $larguraCampo;
    private $options;
    private $tabelaOptions;
    private $campoOptionText;
    private $campoOptionValue;
    private $codigo;

    public function __construct($campo) {

        $nome = (empty($campo['campo_tabela'])) ? $campo['titulo'] : $campo['campo_tabela'];
        $idNome = Numenor_Gerador_Nomes::getNomeCampo($nome);

        $this->nome = $nome;
        $this->nomeVar = $idNome;
        $this->label = $campo['titulo'];
        $this->labelAlinhamento = $campo['titulo_alinhamento'];
        $this->labelLargura = intval($campo['titulo_largura']);

        $this->valorPadrao = $campo['valor_padrao'];

        $this->requerido = ($campo['requerido'] == 'sim') ? 'true' : 'false';
        $this->larguraCampo = intval($campo['campo_largura']);

        $this->options = empty($campo['values']) ? array() : $campo['values'];
        $this->tabelaOptions = $campo['tabela_options'];
        $this->campoOptionText = $campo['campo_option_text'];
        $this->campoOptionValue = $campo['campo_option_value'];
    }

    /**
     * Método responsável em retornar o nome do campo no formato de propriedade
     * 
     * @return string 
     */
    public function getNomeCampoPropriedade() {
        return Numenor_Gerador_Nomes::getNomePropriedade($this->nomeVar);
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


        $s = '        ';
        $codigo = '';

        if (empty($this->tabelaOptions)) {
            $codigo .= $s . "\$dadosOption = array(\r\n";
            foreach ($this->options as $option) {
                $codigo .= $s . "    '{$option['value']}' => '{$option['text']}',\r\n";
            }
            $codigo .= $s . ");\r\n";
        } else {
            $nomeClasse = Numenor_Gerador_Nomes::getNomeClasse($this->tabelaOptions);
            $nomePropriedade = Numenor_Gerador_Nomes::getNomePropriedade($this->tabelaOptions);
            $nomeMetodo = Numenor_Gerador_Nomes::getNomeMetodo('get select ' . $this->campoOptionValue . ' ' . $this->campoOptionText);

            $codigo .= $s . "// Preenche os options do select com os campos {$this->campoOptionValue} e {$this->campoOptionText}.\r\n";
            $codigo .= $s . "\${$nomePropriedade} = new {$nomeClasse}();\r\n";
            $codigo .= $s . "\$dadosOption = \${$nomePropriedade}->{$nomeMetodo}();\r\n";
        }

        $codigo .= "\r\n";

        $nomePropriedade = $this->getNomeCampoPropriedade();

        $codigo .= $s . "\${$nomePropriedade} = new Zend_Form_Element_Select('{$this->nomeVar}');\r\n";
        $codigo .= $s . "\${$nomePropriedade}->setLabel('{$this->label}')\r\n";
        $codigo .= $s . "    ->setRequired({$this->requerido})\r\n";

        if ($this->requerido == 'true') {
            $codigo .= $s . "    ->setValidators(array('NotEmpty'))\r\n";
        }

        $codigo .= $s . "    ->addMultiOptions(\$dadosOption)\r\n";
        $codigo .= $s . "    ->setOptions(array(\r\n";
        $codigo .= $s . "        'attr_label' => array(\r\n";
        $codigo .= $s . "            'class' => 'width{$this->labelLargura} {$this->labelAlinhamento}'\r\n";
        $codigo .= $s . "        ),\r\n";
        $codigo .= $s . "        'attr_campo' => array(\r\n";
        $codigo .= $s . "            'class' => 'width{$this->larguraCampo}'\r\n";
        $codigo .= $s . "        )\r\n";
        $codigo .= $s . "    ));\r\n";

        $this->codigo = $codigo;

        return true;
    }

}