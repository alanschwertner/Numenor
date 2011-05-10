<?php

/**
 * Classe responsável em gerar o código fonte para as classes DTO
 *
 * @author Cristian Cardoso
 * @email ctncardoso@ctncardoso.com.br
 * @version 1.5
 * @data_modificacao 13/03/2011 11:00
 */
class Numenor_Gerador_DTO {

    private $campos;
    private $tabela;
    private $schema = null;
    private $nomeClasse;
    private $schemaTabela;
    private $primaryKey = array();
    private $codigo;
    private $campoSelect = array();

    public function __construct($campos = null, $tabela = null, $schema = null) {
        if (!empty($campo)) {
            $this->setCampos($campos);
        }
        if (!empty($tabela)) {
            $this->setTabela($tabela);
        }
        if (!empty($schema)) {
            $this->setSchema($schema);
        }
    }

    /**
     * Retorna o nome da Classe
     * 
     * @return string 
     */
    public function getNome() {
        return $this->nomeClasse;
    }

    /**
     * Responsável em receber o nome dos campos que vão servir para o preenchimento
     * das options de um campo select.
     *
     * @param string $campoValue Nome do campo que ira preencher o value nas options
     * @param string $campoText Nome do campo que ira preencher o text nas options
     */
    public function setCampoSelect($campoValue, $campoText) {
        $select = array(
            'value' => $campoValue,
            'text' => $campoText
        );

        if (!in_array($select, $this->campoSelect)) {
            $this->campoSelect[] = $select;
        }
    }

    /**
     * Responsável em receber os campos da tabela
     *
     * @param array $campos Array contendo todas as informações do campo
     * @return Numenor_Gerador_DTO
     * @throw Numenor_Gerador_Exception
     */
    public function setCampos($campos) {

        if (is_array($campos)) {
            $this->campos = $campos;

            foreach ($this->campos as $nome => $dados) {
                if ($dados['chave_primaria'] === true) {
                    $this->primaryKey[$nome] = $dados;
                }
            }

            return $this;
        } else {
            throw new Numenor_Gerador_Exception('Valor informado não é um array valido');
        }
    }

    /**
     * Responsável em receber o nome da Tabela ao qual o código deverá ser gerado
     *
     * @param string $tabela Nome da Tabela
     * @return Numenor_Gerador_DTO
     */
    public function setTabela($tabela) {

        $this->tabela = $tabela;
        return $this;
    }

    /**
     * Responsável em receber o nome do Schema ao qual a tabela pertence
     *
     * @param string $schema Nome do Schema
     * @return Numenor_Gerador_DTO
     */
    public function setSchema($schema) {

        $this->schema = $schema;
        return $this;
    }

    /**
     * Método responsável em gerar o código Mapper a partir das informações setadas
     *
     * Retorna TRUE em caso de sucesso ou FALSE caso contrário
     *
     * @return bool
     */
    public function gerar() {

        if (empty($this->campos) || empty($this->tabela)) {
            return false;
        }

        $dataCriacao = Numenor_Gerador_Info::getDataCriacao();
        $versao = Numenor_Gerador_Info::getVersion();
        $criadoPor = Numenor_Gerador_Info::getCriador();

        $this->schemaTabela = (empty($this->schema)) ? $this->tabela : $this->schema . '.' . $this->tabela;
        $this->nomeClasse = Numenor_Gerador_Nomes::getNomeClasse($this->schemaTabela);

        $codigo = '';
        $codigo .= "<?php\r\n\r\n";
        $codigo .= "/**\r\n";
        $codigo .= " * Classe para a tabela {$this->schemaTabela}\r\n";
        $codigo .= " *\r\n";
        $codigo .= " * Contém a mesma estrutura da tabela, os campos da tabela com os seus Get's e Set's\r\n";
        $codigo .= " *\r\n";
        $codigo .= " * Versão: {$versao}\r\n";
        $codigo .= " * Criado Por: {$criadoPor}\r\n";
        $codigo .= " * Data Criação: {$dataCriacao}\r\n";
        $codigo .= " * Modificado Por:\r\n";
        $codigo .= " * Data Modificação:\r\n";
        $codigo .= " */\r\n";
        $codigo .= "class {$this->nomeClasse} extends Numenor_Db_DomainObjectAbstract {\r\n";

        $codigo .= "\r\n";
        $codigo .= $this->getPropriedades();
        $codigo .= "\r\n";

        foreach ($this->campos as $nome => $dados) {

            $codigo .= $this->getMetodoSet($nome, $dados);
            $codigo .= "\r\n";
            $codigo .= $this->getMetodoGet($nome, $dados);
            $codigo .= "\r\n";
        }

        $codigo .= $this->getMetodoCampoSelect();

        $codigo .= "}\r\n";
        /* $codigo .= "?>"; */

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

    /**
     * Método responsável em fornecer todas as propriedades que serão utilizadas na classe
     *
     * @return string código das propriedades
     */
    private function getPropriedades() {

        $codigo = '';
        $codigo .= "    // Define as propriedades\r\n";
        $codigo .= "    protected \$_mapper = \"{$this->nomeClasse}Mapper\";\r\n";

        if (!empty($this->primaryKey)) {
            $keys = implode("', '", array_keys($this->primaryKey));
            $codigo .= "    protected \$_primary = array('{$keys}');\r\n";
        }

        $codigo .= "\r\n";
        
        foreach ($this->campos as $nome => $campo) {
            $nomePropriedade = Numenor_Gerador_Nomes::getNomePropriedade($nome);
            $codigo .= "    private \${$nomePropriedade};\r\n";
        }

        return $codigo;
    }

    /**
     * Método responsável em gerar o método get para determinado campo
     *
     * @param string $nome Nome do campo
     * @param string $dados Propriedades do campo
     * @return string Código gerado
     */
    private function getMetodoGet($nome, $dados) {

        $nomePropriedade = Numenor_Gerador_Nomes::getNomePropriedade($nome);
        $nomeMetodo = Numenor_Gerador_Nomes::getNomeMetodo('get ' . $nome);
        $tipo = Numenor_Gerador_TipoDados::getTipo($dados['tipo']);

        $codigo = '';

        $codigo .= "    /**\r\n";
        $codigo .= "     * Retorna o valor da propriedade \${$nomePropriedade}\r\n";
        $codigo .= "     *\r\n";
        $codigo .= "     * @access public\r\n";
        $codigo .= "     * @return {$tipo}\r\n";
        $codigo .= "     */\r\n";
        $codigo .= "    public function {$nomeMetodo}() {\r\n";
        $codigo .= "        return \$this->{$nomePropriedade};\r\n";
        $codigo .= "    }\r\n";

        return $codigo;
    }

    /**
     * Método responsável em gerar o método set para determinado campo
     *
     * @param string $nome Nome do campo
     * @param string $dados Propriedades do campo
     * @return string Código gerado
     */
    private function getMetodoSet($nome, $dados) {

        $nomePropriedade = Numenor_Gerador_Nomes::getNomePropriedade($nome);
        $nomeMetodo = Numenor_Gerador_Nomes::getNomeMetodo('set ' . $nome);
        $tipo = Numenor_Gerador_TipoDados::getTipo($dados['tipo']);

        $codigo = '';

        $codigo .= "    /**\r\n";
        $codigo .= "     * Seta um valor à propriedade \${$nomePropriedade}\r\n";
        $codigo .= "     *\r\n";
        $codigo .= "     * @access public\r\n";
        $codigo .= "     * @param {$tipo} \${$nomePropriedade}\r\n";
        $codigo .= "     */\r\n";
        $codigo .= "    public function {$nomeMetodo}(\${$nomePropriedade}) {\r\n";
        $codigo .= "        \$this->{$nomePropriedade} = \${$nomePropriedade};\r\n";
        $codigo .= "    }\r\n";

        return $codigo;
    }

    /**
     * Método responsável em criar o método que sera utilizado em campos select
     * para o preenchimento das options
     *
     * @return string Código Gerado
     */
    private function getMetodoCampoSelect() {

        $codigo = '';

        if (!empty($this->campoSelect)) {

            foreach ($this->campoSelect as $campo) {

                $nomeMetodo = Numenor_Gerador_Nomes::getNomeMetodo('get select ' . $campo['value'] . ' ' . $campo['text']);

                $codigo .= "    /**\r\n";
                $codigo .= "     * Método responsável em buscar as informações para o preenchimento\r\n";
                $codigo .= "     * de um campo Select\r\n";
                $codigo .= "     *\r\n";
                $codigo .= "     * @return arry Retorna dados em um matriz de pares chave-valor.\r\n";
                $codigo .= "     */\r\n";
                $codigo .= "    public function {$nomeMetodo}() {\r\n";
                $codigo .= "        return \$this->getMapper()->{$nomeMetodo}();\r\n";
                $codigo .= "    }\r\n";
                $codigo .= "\r\n";
            }
        }

        return $codigo;
    }

}