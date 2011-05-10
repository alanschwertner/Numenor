<?php

/**
 * Classe responsável em gerar o código fonte para as classes DbTable
 *
 * @author Cristian Cardoso
 * @email ctncardoso@ctncardoso.com.br
 * @version 1.5
 * @data_modificacao 13/03/2011 22:30
 */
class Numenor_Gerador_DbTable {

    private $tabela;
    private $primaryKey;
    private $schema = null;
    private $nomeClasse;
    private $schemaTabela;
    private $codigo;

    public function __construct($tabela = null, $primaryKey = null, $schema = null) {
        if (!empty($tabela)) {
            $this->setTabela($tabela);
        }
        if (!empty($primaryKey)) {
            $this->setPrimaryKey($primaryKey);
        }
        if (!empty($schema)) {
            $this->setSchema($schema);
        }
    }

    /**
     * Responsável em receber os campos da tabela
     *
     * @param array $campos Array contendo todas as informações do campo
     * @return Numenor_Gerador_DbTable
     */
    public function setPrimaryKey($primaryKey) {

        $this->primaryKey = $primaryKey;
        return $this;
    }

    /**
     * Responsável em receber o nome da Tabela ao qual o código deverá ser gerado
     *
     * @param string $tabela Nome da Tabela
     * @return Numenor_Gerador_DbTable
     */
    public function setTabela($tabela) {

        $this->tabela = $tabela;
        return $this;
    }

    /**
     * Responsável em receber o nome do Schema ao qual a tabela pertence
     *
     * @param string $schema Nome do Schema
     * @return Numenor_Gerador_DbTable
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

        if (empty($this->primaryKey) || empty($this->tabela)) {
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
        $codigo .= " * Versão: {$versao}\r\n";
        $codigo .= " * Criado Por: {$criadoPor}\r\n";
        $codigo .= " * Data Criação: {$dataCriacao}\r\n";
        $codigo .= " * Modificado Por:\r\n";
        $codigo .= " * Data Modificação:\r\n";
        $codigo .= " */\r\n";
        $codigo .= "class DbTable_{$this->nomeClasse} extends Zend_Db_Table_Abstract {\r\n";
        
        $codigo .= "\r\n";
        $codigo .= $this->getPropriedades();
        $codigo .= "\r\n";

        $codigo .= "}\r\n";
        /* $codigo .= "?>"; */

        $this->codigo = $codigo;

        return true;
    }

    /**
     * Retorna o nome da Classe
     * 
     * @return string 
     */
    public function getNome() {
        return Numenor_Gerador_Nomes::getNomeClasse($this->schemaTabela);
    }
    
    /**
     * Método responsável em fornecer todas as propriedades que serão utilizadas na classe
     *
     * @return string código das propriedades
     */
    private function getPropriedades() {

        $codigo = '';
        if (!empty($this->schema)) {
            $codigo .= "    protected \$_schema = '{$this->schema}';\r\n";
        }

        $codigo .= "    protected \$_name = '{$this->tabela}';\r\n";

        if (is_string($this->primaryKey)) {
            $codigo .= "    protected \$_primary = '{$this->primaryKey}';\r\n";
        } else if (is_array($this->primaryKey)) {
            $codigo .= "    protected \$_primary = array(\r\n";
            foreach ($this->primaryKey as $primary) {
                $codigo .= "        '{$primary['nome_campo']}',\r\n";
            }
            $codigo .= "    );\r\n";
        }

        return $codigo;
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