<?php

/**
 * Classe responsável em gerar o código fonte para o campo Input Text
 *
 * @author Cristian Cardoso
 * @email ctncardoso@ctncardoso.com.br
 * @version 1.6
 * @data_modificacao 03/05/2011 15:20
 */
class Numenor_Gerador_Form_Text {

    private $nome;
    private $label;
    private $labelLargura;
    private $labelAlinhamento;
    private $requerido;
    private $tipoValidacao;
    private $valorPadrao;
    private $larguraCampo;
    private $codigo;

    public function __construct($campo) {

        $nome = (empty($campo['campo_tabela'])) ? $campo['titulo'] : $campo['campo_tabela'];
        $idNome = Numenor_Gerador_Nomes::getNomeCampo($nome);

        $this->nome = $idNome;
        $this->label = $campo['titulo'];
        $this->labelAlinhamento = $campo['titulo_alinhamento'];
        $this->labelLargura = $campo['titulo_largura'];
        $this->tipoValidacao = $campo['tipo_validacao'];
        $this->valorPadrao = $campo['valor_padrao'];
        $this->larguraCampo = intval($campo['campo_largura']);
        $this->requerido = ($campo['requerido'] == 'sim') ? 'true' : 'false';
    }

    /**
     * Método responsável em retornar o nome do campo no formato de propriedade
     * 
     * @return string 
     */
    public function getNomeCampoPropriedade() {
        return Numenor_Gerador_Nomes::getNomePropriedade($this->nome);
    }

    private function getRegExp() {

        if (!empty($this->tipoValidacao)) {
            $db = Zend_Db_Table::getDefaultAdapter();
            $select = $db->select()
                    ->from('tipo_validacao', array('expressao_regular'))
                    ->where('nome_logico = ?', $this->tipoValidacao);
            $dados = $select->query()->fetch();

            return $dados['expressao_regular'];
        }

        return '';
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
        $codigo .= $s . "\${$nomePropriedade} = new Zend_Form_Element_Text('{$this->nome}');\r\n";
        $codigo .= $s . "\${$nomePropriedade}->setLabel('{$this->label}')\r\n";
        $codigo .= $s . "    ->setRequired({$this->requerido})\r\n";

        if ($this->requerido == 'true') {
            $codigo .= $s . "    ->setValidators(array('NotEmpty'))\r\n";
        }

        if (!empty($this->valorPadrao)) {
            $codigo .= $s . "    ->setValue('{$this->valorPadrao}')\r\n";
        }

        if (!empty($this->tipoValidacao)) {
            $codigo .= $s . "    ->addValidator('regex', false, array('{$this->getRegExp()}'))\r\n";
        }

        $codigo .= $s . "    ->setOptions(array(\r\n";
        $codigo .= $s . "        'attr_label' => array(\r\n";
        $codigo .= $s . "            'class' => 'width{$this->labelLargura} {$this->labelAlinhamento}'\r\n";
        $codigo .= $s . "        ),\r\n";
        $codigo .= $s . "        'attr_campo' => array(\r\n";
        $codigo .= $s . "            'class' => 'width{$this->larguraCampo}',\r\n";

        if (!empty($this->tipoValidacao)) {
            $codigo .= $s . "            'valida' => '{$this->tipoValidacao}'\r\n";
        }

        $codigo .= $s . "        )\r\n";
        $codigo .= $s . "    ));\r\n";

        $this->codigo = $codigo;

        return true;
    }

}