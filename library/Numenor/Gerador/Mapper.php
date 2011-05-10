<?php

/**
 * Classe responsável em gerar o código fonte para as classes Mapper
 *
 * @author Cristian Cardoso
 * @email ctncardoso@ctncardoso.com.br
 * @version 1.5
 * @data_modificacao 12/03/2011 00:10
 */
class Numenor_Gerador_Mapper {

    private $campos;
    private $tabela;
    private $schema = null;
    private $foreignKey = array();
    private $primaryKey = array();
    private $nomeClasse;
    private $schemaTabela;
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
        return Numenor_Gerador_Nomes::getNomeClasse($this->schemaTabela . ' Mapper');
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
     * @return Numenor_Gerador_Mapper
     * @throw Numenor_Gerador_Exception
     */
    public function setCampos($campos) {

        if (is_array($campos)) {

            $this->campos = $campos;

            if (!empty($campos)) {
                $this->primaryKey = array();
                $this->foreignKey = array();

                foreach ($campos as $nome => $dados) {
                    if ($dados['chave_primaria'] === true) {
                        $this->primaryKey[$nome] = $dados;
                    }

                    if ($dados['chave_estrangeira'] === true) {
                        $this->foreignKey[$nome] = $dados;
                    }
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
     * @return Numenor_Gerador_Mapper
     */
    public function setTabela($tabela) {

        $this->tabela = $tabela;
        return $this;
    }

    /**
     * Responsável em receber o nome do Schema ao qual a tabela pertence
     *
     * @param string $schema Nome do Schema
     * @return Numenor_Gerador_Mapper
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
        $this->nomeClasse = Numenor_Gerador_Nomes::getNomeClasse($this->schemaTabela . ' Mapper');


        $codigo = '';
        $codigo .= "<?php\r\n\r\n";
        $codigo .= "/**\r\n";
        $codigo .= " * Classe Mapper para a tabela {$this->schemaTabela}\r\n";
        $codigo .= " *\r\n";
        $codigo .= " * Versão: {$versao}\r\n";
        $codigo .= " * Criado Por: {$criadoPor}\r\n";
        $codigo .= " * Data Criação: {$dataCriacao}\r\n";
        $codigo .= " * Modificado Por:\r\n";
        $codigo .= " * Data Modificação:\r\n";
        $codigo .= " */\r\n";
        $codigo .= "class {$this->nomeClasse} extends Numenor_Db_DataMapperAbstract {\r\n";

        $codigo .= "\r\n";
        $codigo .= $this->getPropriedades();
        $codigo .= "\r\n";
        $codigo .= $this->getMetodoInsert();
        $codigo .= "\r\n";
        $codigo .= $this->getMetodoUpdate();
        $codigo .= "\r\n";

        if (!empty($this->foreignKey)) {
            $codigo .= $this->getMetodoFind();
            $codigo .= "\r\n";
            $codigo .= $this->getMetodoFetchAll();
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

        $nomeClass = Numenor_Gerador_Nomes::getNomeClasse($this->schemaTabela);

        $codigo = '';
        $codigo .= "    // Define as propriedades\r\n";
        $codigo .= "    protected \$_dbTable = \"DbTable_{$nomeClass}\";\r\n";
        $codigo .= "    protected \$_model = \"{$nomeClass}\";\r\n\r\n";

        return $codigo;
    }

    /**
     * Método responsável em fornecer a estrutura do método update para as tabelas
     *
     * @return string método insert
     */
    private function getMetodoInsert() {

        $codigo = '';
        $codigo .= "    protected function _insert(Numenor_Db_DomainObjectAbstract \$obj) {\r\n";
        $codigo .= "        try {\r\n";
        $codigo .= "            \$dbTable = \$this->getDbTable();\r\n";
        $codigo .= "            \$data = array(\r\n";

        foreach ($this->campos as $nome => $dados) {

            $nomeMetodo = Numenor_Gerador_Nomes::getNomeMetodo('get ' . $nome);

            if ($dados['chave_primaria'] === true) {
                if ($dados['chave_estrangeira'] === true) {
                    $codigo .= "                '{$nome}' => \$obj->{$nomeMetodo}(),\r\n";
                }
            } else {
                $codigo .= "                '{$nome}' => \$obj->{$nomeMetodo}(),\r\n";
            }
        }

        $codigo .= "            );\r\n";
        $codigo .= "            \$dbTable->insert(\$data);\r\n";
        $codigo .= "            return true;\r\n";
        $codigo .= "        } catch (Zend_Exception \$e) {\r\n";
        $codigo .= "            return false;\r\n";
        $codigo .= "        }\r\n";
        $codigo .= "    }\r\n";

        return $codigo;
    }

    /**
     * Método responsável em fornecer a estrutura do método update para as tabelas
     *
     * @return string método update
     */
    private function getMetodoUpdate() {

        $codigo = '';
        $codigo .= "    protected function _update(Numenor_Db_DomainObjectAbstract \$obj) {\r\n";
        $codigo .= "        try {\r\n";
        $codigo .= "            \$dbTable = \$this->getDbTable();\r\n";
        $codigo .= "\r\n";
        $codigo .= "            \$data = array(\r\n";

        foreach ($this->campos as $nome => $dados) {

            $nomeMetodo = Numenor_Gerador_Nomes::getNomeMetodo('get ' . $nome);

            if ($dados['chave_primaria'] === true) {
                if ($dados['chave_estrangeira'] === true) {
                    $codigo .= "                '{$nome}' => \$obj->{$nomeMetodo}(),\r\n";
                }
            } else {
                $codigo .= "                '{$nome}' => \$obj->{$nomeMetodo}(),\r\n";
            }
        }

        $codigo .= "            );\r\n";
        $codigo .= "\r\n";

        $codigo .= "            \$where = array();\r\n";
        foreach ($this->primaryKey as $nome => $dados) {
            $nomeMetodo = Numenor_Gerador_Nomes::getNomeMetodo('get ' . $nome);

            $codigo .= "            \$where['{$nome} = ?'] = \$obj->{$nomeMetodo}();\r\n";
        }
        $codigo .= "\r\n";
        $codigo .= "            \$dbTable->update(\$data, \$where);\r\n";

        $codigo .= "            return true;\r\n";
        $codigo .= "        } catch (Zend_Exception \$e) {\r\n";
        $codigo .= "            return false;\r\n";
        $codigo .= "        }\r\n";
        $codigo .= "    }\r\n";

        return $codigo;
    }

    private function getMetodoDelete() {
        
        $codigo .= "    /**\r\n";
        $codigo .= "     * Método em efetuar a exclusao de um determinado registro\r\n";
        $codigo .= "     *\r\n";
        
        $parametro = '';
        $where = '';
        foreach ($this->primaryKey as $nome => $dados) {
            $nomeVar = Numenor_Gerador_Nomes::getNomePropriedade($nome);
            $parametro .= empty($parametro) ? $nomeVar : ', ' . $nomeVar;

            $codigo .= "     * @param {$dados['tipo']} \${$nomeVar}\r\n";
        }
        
        $codigo = '';
        $codigo .= "    public function delete({$parametro}) {\r\n";
        $codigo .= "        try {\r\n";
        $codigo .= "            \$dbTable = \$this->getDbTable();\r\n";
        $codigo .= "            \$dbTable->delete(\$data, \$where);\r\n";
        
        $codigo .= "        } catch (Zend_Exception \$e) {\r\n";
        $codigo .= "            return false;\r\n";
        $codigo .= "        }\r\n";
        
        $codigo .= "\r\n";
        
        $codigo .= "    }\r\n";

        return $codigo;
    }

    /**
     * Método responsável em fornecer a estrutura do método find para tabelas
     * que possuam chave estrangeira
     *
     * @return string método find
     */
    private function getMetodoFind() {

        $codigo = '';
        $codigo .= "    /**\r\n";
        $codigo .= "     * Método responsável em retornar apenas um registro da tabela\r\n";
        $codigo .= "     *\r\n";

        $countPrimaryKey = count($this->primaryKey);
        $parametro = '';
        $where = '';
        foreach ($this->primaryKey as $nome => $dados) {
            $nomeVar = Numenor_Gerador_Nomes::getNomePropriedade($nome);
            $parametro .= empty($parametro) ? '$' . $nomeVar : ', $' . $nomeVar;

            $codigo .= "     * @param {$dados['tipo']} \${$nomeVar}\r\n";
            $where .= "                ->where('{$nome} = ?', \$obj->{$nomeVar}())";
            $where .= (--$countPrimaryKey == 0) ? ";\r\n" : "\r\n";
        }

        $codigo .= "     * @return\r\n";
        $codigo .= "     */\r\n";
        $codigo .= "    public function find({$parametro}) {\r\n";
        $codigo .= "        \$db = \$this->getDb();\r\n";
        $codigo .= "        \$query = \$db->select();\r\n";
        $codigo .= "        \$query->from('{$this->schemaTabela}')\r\n";
        $codigo .= $where;
        $codigo .= "\r\n";

        $varDados = Numenor_Gerador_Nomes::getNomeVariavel('dados ' . $this->schemaTabela);

        $codigo .= "        \${$varDados} = \$db->fetchRow(\$query);\r\n";
        $codigo .= "\r\n";
        $codigo .= "        if (\${$varDados}) {\r\n";

        foreach ($this->foreignKey as $nome => $dados) {

            $nomeSchema = $dados['referencia']['nome_schema_ref'];
            $nomeTabela = $dados['referencia']['nome_tabela_ref'];
            $nomeCampo = $dados['referencia']['nome_campo_ref'];

            $varForeignKey = Numenor_Gerador_Nomes::getNomeVariavel($nomeSchema . ' ' . $nomeTabela);
            $classeForeignKeyMapper = Numenor_Gerador_Nomes::getNomeClasse($nomeSchema . ' ' . $nomeTabela . ' Mapper');
            $varDadosforeignKey = Numenor_Gerador_Nomes::getNomeVariavel('dados ' . $nomeSchema . ' ' . $nomeTabela);


            $codigo .= "            \${$varForeignKey} = new {$classeForeignKeyMapper}();\r\n";
            $codigo .= "            \${$varDadosforeignKey} = \${$varForeignKey}->find(\${$varDados}['{$nomeCampo}']);\r\n";
            $codigo .= "            \${$varDados}['{$nomeCampo}'] = \${$varDadosforeignKey};\r\n";
        }

        $codigo .= "        }\r\n";
        $codigo .= "\r\n";
        $codigo .= "        return \$this->_populate(\${$varDados});\r\n";
        $codigo .= "    }\r\n";

        return $codigo;
    }

    /**
     * Método responsável em fornecer a estrutura do método fetchAll para tabelas
     * que possuam chave estrangeira
     *
     * @return string método fetchAll
     */
    private function getMetodoFetchAll() {

        $codigo = '';

        $codigo .= "    /**\r\n";
        $codigo .= "     * Método responsável retornar todos os registro da tabela\r\n";
        $codigo .= "     *\r\n";
        $codigo .= "     * @return\r\n";
        $codigo .= "     */\r\n";
        $codigo .= "    public function fetchAll() {\r\n";
        $codigo .= "        \$db = \$this->getDb();\r\n";
        $codigo .= "        \$query = \$db->select();\r\n";
        $codigo .= "        \$query->from('{$this->schemaTabela}');\r\n";
        $codigo .= "\r\n";

        $varDados = Numenor_Gerador_Nomes::getNomeVariavel('dados ' . $this->schemaTabela);

        $codigo .= "        \${$varDados} = \$db->fetchAll(\$query);\r\n";
        $codigo .= "\r\n";

        $varDadosNovos = Numenor_Gerador_Nomes::getNomeVariavel('novos dados ' . $this->schemaTabela);
        $codigo .= "        \${$varDadosNovos} = array();\r\n";
        $codigo .= "\r\n";

        foreach ($this->foreignKey as $nome => $dados) {

            $nomeSchema = $dados['referencia']['nome_schema_ref'];
            $nomeTabela = $dados['referencia']['nome_tabela_ref'];

            $varForeignKey = Numenor_Gerador_Nomes::getNomePropriedade($nomeSchema . ' ' . $nomeTabela);
            $classForeignKeyClasse = Numenor_Gerador_Nomes::getNomeClasse($nomeSchema . ' ' . $nomeTabela . ' Mapper');

            $codigo .= "        \${$varForeignKey} = new {$classForeignKeyClasse}();\r\n";
        }

        $codigo .= "\r\n";
        $codigo .= "        foreach (\${$varDados} as \$dados) {\r\n";

        foreach ($this->foreignKey as $nome => $dados) {

            $nomeSchema = $dados['referencia']['nome_schema_ref'];
            $nomeTabela = $dados['referencia']['nome_tabela_ref'];
            $nomeCampo = $dados['referencia']['nome_campo_ref'];

            $varforeignKey = Numenor_Gerador_Nomes::getNomeVariavel($nomeSchema . ' ' . $nomeTabela);
            $varDadosForeignKey = Numenor_Gerador_Nomes::getNomeVariavel('dados ' . $nomeSchema . ' ' . $nomeTabela);

            $codigo .= "            \${$varDadosForeignKey} = \${$varforeignKey}->find(\$dados['{$nomeCampo}']);\r\n";
            $codigo .= "            \$dados['{$nomeCampo}'] = \${$varDadosForeignKey};\r\n";
            $codigo .= "            \${$varDadosNovos}[] = \$this->_populate(\$dados);\r\n";
        }

        $codigo .= "        }\r\n";
        $codigo .= "\r\n";
        $codigo .= "        return \${$varDadosNovos};\r\n";
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

                $codigo .= "        \$db = \$this->getDb();\r\n";
                $codigo .= "        \$query = \$db->select();\r\n";
                $codigo .= "        \$query->from('{$this->schemaTabela}', array('{$campo['value']}', '{$campo['text']}'))\r\n";
                $codigo .= "                ->order('{$campo['text']} asc');\r\n";

                $codigo .= "        return \$db->fetchPairs(\$query);\r\n";

                $codigo .= "    }\r\n";
                $codigo .= "\r\n";
            }
        }

        return $codigo;
    }

}