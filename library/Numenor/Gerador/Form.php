<?php

/**
 * Classe responsável em gerar o código fonte para os formulários
 *
 * @author Cristian Cardoso
 * @email ctncardoso@ctncardoso.com.br
 * @version 1.5
 * @data_modificacao 12/03/2011 00:10
 */
class Numenor_Gerador_Form {

    private $nome;
    private $campos = array();
    private $codigo;
    private $dependencias = array();
    private $idCampoGerado = array();

    public function __construct($form = null) {

        if (!empty($form)) {
            $this->setNome($form['nome_formulario']);
            $this->setCampos($form['campos_formulario']);
        }
    }

    /**
     * Responsável em receber o nome do formulário
     *
     * @param string $nome Nome do formulário
     * @return Numenor_Gerador_Form
     * @throw Numenor_Gerador_Exception
     */
    public function setNome($nome) {
        $this->nome = $nome;
        return $this;
    }

    /**
     * Retorna o nome do formulário 
     * 
     * @return string 
     */
    public function getNome() {
        return Numenor_Gerador_Nomes::getNomeClasse($this->nome);
    }

    /**
     * Retorna o nome da Classe
     * 
     * @return string 
     */
    public function getNomeClass() {
        return 'Form_' . Numenor_Gerador_Nomes::getNomeClasse($this->nome);
    }

    /**
     * Responsável em receber os campos do formulário
     *
     * @param array $campos Campos do formulário
     * @return Numenor_Gerador_Form
     */
    public function setCampos($campos) {

        if (is_array($campos)) {
            $this->campos = $campos;
            return $this;
        } else {
            throw new Numenor_Gerador_Exception('Valor informado não é um array válido');
        }
    }

    private function dependencia($schemaTabela, $select) {

        if (array_key_exists($schemaTabela, $this->dependencias)) {
            if (!empty($select)) {
                if (empty($this->dependencias[$schemaTabela]['select'])) {
                    $this->dependencias[$schemaTabela]['select'][] = $select;
                } else if (!in_array($select, $this->dependencias[$schemaTabela]['select'])) {
                    $this->dependencias[$schemaTabela]['select'][] = $select;
                }
            }
        } else {
            if (!empty($select)) {
                $this->dependencias[$schemaTabela]['select'][] = $select;
            } else {
                $this->dependencias[$schemaTabela]['select'] = array();
            }
        }
    }

    private function setDependencia($campo) {

        if (!empty($campo['campo_tabela'])) {

            $splitCampo = explode('.', $campo['campo_tabela']);

            if (count($splitCampo) == 3) {
                $schemaTabela = $splitCampo[0] . '.' . $splitCampo[1];
            } else {
                $schemaTabela = $splitCampo[0];
            }

            $select = array();

            $this->dependencia($schemaTabela, $select);
        }

        if (!empty($campo['tabela_options'])) {
            $select = array(
                'value' => $campo['campo_option_value'],
                'text' => $campo['campo_option_text']
            );

            $this->dependencia($campo['tabela_options'], $select);
        }
    }

    /**
     * Retorna todas as dependencias do formulário
     * 
     * @return array 
     */
    public function getDependencias() {
        return $this->dependencias;
    }

    /**
     * Define o nome das variaveis dos campos que foram gerados
     * 
     * @param type $id 
     */
    private function setIdCampoGerado($id) {
        $this->idCampoGerado[] = $id;
    }

    /**
     * Método responsável em gerar o código do Formulário a partir das informações setadas
     * 
     * Retorna TRUE em caso de sucesso ou FALSE caso contrário
     *
     * @return bool
     */
    public function gerar() {

        if (empty($this->campos)) {
            return false;
        }

        $dataCriacao = Numenor_Gerador_Info::getDataCriacao();
        $versao = Numenor_Gerador_Info::getVersion();
        $criadoPor = Numenor_Gerador_Info::getCriador();

        $codigo = '';
        $codigo .= "<?php\r\n";
        $codigo .= "\r\n";

        $codigo .= "/**\r\n";
        $codigo .= " * Classe Zend_Form para o formulário {$this->nome}\r\n";
        $codigo .= " *\r\n";
        $codigo .= " * Versão: {$versao}\r\n";
        $codigo .= " * Criado Por: {$criadoPor}\r\n";
        $codigo .= " * Data Criação: {$dataCriacao}\r\n";
        $codigo .= " * Modificado Por:\r\n";
        $codigo .= " * Data Modificação:\r\n";
        $codigo .= " */\r\n";
        $codigo .= "class {$this->getNomeClass()} extends Zend_Form {\r\n";
        $codigo .= "\r\n";
        $codigo .= "    public function __construct(\$option = null) {\r\n";
        $codigo .= "        parent::__construct(\$option);\r\n";
        $codigo .= "\r\n";
        $codigo .= "        // Define o camino do decorator que irá utilizar em todos os elementos.\r\n";
        $codigo .= "        \$this->addElementPrefixPath('Numenor_Form_Decorator', 'Numenor/Form/Decorator/', 'decorator');\r\n";
        $codigo .= "\r\n";

        foreach ($this->campos as $campo) {

            $this->setDependencia($campo);

            $obj = null;

            switch ($campo['tipo_campo']) {
                
                case 'text':
                    $obj = new Numenor_Gerador_Form_Text($campo);
                    break;
                case 'textarea':
                    $obj = new Numenor_Gerador_Form_Textarea($campo);
                    break;
                case 'select':
                    $obj = new Numenor_Gerador_Form_Select($campo);
                    break;
                case 'submit' :
                    $obj = new Numenor_Gerador_Form_Submit($campo);
                    break;
            }

            if ($obj != null) {
                if ($obj->gerar()) {
                    $codigo .= $obj->getCodigo();
                    $codigo .= "\r\n";
                    $this->setIdCampoGerado($obj->getNomeCampoPropriedade());
                }
            }
        }


        $codigo .= "        // Inclui os campos ao formulário.\r\n";
        $codigo .= "        \$this->addElements(array(\r\n";
        foreach ($this->idCampoGerado as $id) {
            $codigo .= "            \${$id},\r\n";
        }
        $codigo .= "        ));\r\n";

        $codigo .= "\r\n";

        $codigo .= "        // Define a existencia de uma div envolvendo as linhas do formulário.\r\n";
        $codigo .= "        \$this->setDecorators(array(\r\n";
        $codigo .= "            'FormElements',\r\n";
        $codigo .= "            array(\r\n";
        $codigo .= "                array(\r\n";
        $codigo .= "                    'data' => 'HtmlTag'\r\n";
        $codigo .= "                ),\r\n";
        $codigo .= "                array(\r\n";
        $codigo .= "                    'tag' => 'div',\r\n";
        $codigo .= "                    'class' => 'formulario'\r\n";
        $codigo .= "                )\r\n";
        $codigo .= "            ),\r\n";
        $codigo .= "            'Form'\r\n";
        $codigo .= "        ));\r\n";

        $codigo .= "\r\n";

        $codigo .= "        // Sobrescreve decorators existentes com os customizados.\r\n";
        $codigo .= "        \$this->setElementDecorators(array('Composite'));\r\n";
        $codigo .= "    }\r\n";
        $codigo .= "}\r\n";

        $this->codigo = $codigo;

        return true;
    }

    /**
     * Método responsável em retornar o código gerado
     *
     * @return string
     */
    public function getCodigo() {
        return $this->codigo;
    }
    
}