<?php

class Numenor_Gerador {

    private $db;
    private $objGerador;
    private $dadosConexao;
    private $tipoBanco;
    private $tabela;
    private $schema;
    private $campoSelect = array();
    private $codigo;
    private $projeto = array();
    private $dependencias = array();
    private $controllerAction = array();

    public function __construct($dados) {

        $this->dadosConexao = $dados;
        $this->estruturaProjeto();

        $this->tipoBanco = strtoupper($dados['banco']);

        /**
         * Abre a conexão com o banco de dados padrão
         */
        $this->db = Zend_Db::factory($dados['banco'], array(
                    'host' => $dados['host'],
                    'username' => $dados['usuario'],
                    'password' => $dados['senha'],
                    'dbname' => $dados['dbnome'])
        );

        /**
         * Instancia a classe que será responsável em buscar as informações do banco
         */
        switch ($this->tipoBanco) {
            case 'PDO_MYSQL':
                $this->objGerador = new Numenor_Gerador_MySql($this->db);
                break;

            case 'PDO_PGSQL':
                $this->objGerador = new Numenor_Gerador_PostgreSql($this->db);
                break;
        }
    }

    private function estruturaProjeto() {
        $this->projeto = array(
            'Projeto' => array(
                'type' => 'folder',
                'application' => array(
                    'type' => 'folder',
                    'configs' => array(
                        'type' => 'folder',
                    ),
                    'controllers' => array(
                        'type' => 'folder',
                    ),
                    'forms' => array(
                        'type' => 'folder',
                    ),
                    'models' => array(
                        'type' => 'folder',
                    ),
                    'views' => array(
                        'type' => 'folder',
                        'scripts' => array(
                            'type' => 'folder',
                        ),
                    ),
                ),
                'library' => array(
                    'type' => 'folder',
                    'Numenor' => array(
                        'type' => 'folder',
                        'Db' => array(
                            'type' => 'folder',
                        ),
                    ),
                ),
                'public' => array(
                    'type' => 'folder',
                ),
            ),
        );

        $caminhoContudo = 'library/Numenor/Gerador/Bootstrap.php';
        $conteudo = file_get_contents(APPLICATION_PATH . '/../' . $caminhoContudo);
        $caminho = 'application/Bootstrap.php';
        $this->addArquivoProjeto($caminho, 'php', $conteudo);

        $caminho = 'library/Numenor/Gerador/Nomes.php';
        $conteudo = file_get_contents(APPLICATION_PATH . '/../' . $caminho);
        $this->addArquivoProjeto($caminho, 'php', $conteudo);

        $caminho = 'library/Numenor/Db/DataMapperAbstract.php';
        $conteudo = file_get_contents(APPLICATION_PATH . '/../' . $caminho);
        $this->addArquivoProjeto($caminho, 'php', $conteudo);

        $caminho = 'library/Numenor/Db/DomainObjectAbstract.php';
        $conteudo = file_get_contents(APPLICATION_PATH . '/../' . $caminho);
        $this->addArquivoProjeto($caminho, 'php', $conteudo);

        $caminho = 'library/Numenor/Form/Decorator/Composite.php';
        $conteudo = file_get_contents(APPLICATION_PATH . '/../' . $caminho);
        $this->addArquivoProjeto($caminho, 'php', $conteudo);

        $caminho = 'library/Numenor/Form/Translate/pt_BR/Zend_Validate.php';
        $conteudo = file_get_contents(APPLICATION_PATH . '/../' . $caminho);
        $this->addArquivoProjeto($caminho, 'php', $conteudo);
    }

    private function addArquivoProjeto($caminho, $tipo, $conteudo) {

        $caminho = explode('/', $caminho);

        $projeto = &$this->projeto['Projeto'];

        $quantidade = count($caminho);
        $i = 1;
        foreach ($caminho as $pasta) {

            if (isset($projeto[$pasta])) {
                $projeto = &$projeto[$pasta];
            } else {
                if ($quantidade == $i) {
                    $projeto[$pasta] = array(
                        'type' => 'file',
                        'tipo' => $tipo,
                        'content' => $conteudo
                    );
                } else {
                    $projeto[$pasta] = array(
                        'type' => 'folder'
                    );

                    $projeto = &$projeto[$pasta];
                }
            }

            $i++;
        }
    }

    public function setDadosFormularios($formularios) {

        foreach ($formularios as $form) {

            $this->setControllerAction($form['nome_controller'], $form['nome_action'], array(
                'nome_formulario' => $form['nome_formulario'],
                'campos_formulario' => $form['campos_formulario']
            ));
        }
    }

    private function setControllerAction($controller, $action, $dadosForm) {

        if (empty($controller)) {
            $controller = 'index';
        }
        if (empty($action)) {
            $action = 'index';
        }

        if (array_key_exists($controller, $this->controllerAction)) {

            $nomeCorreto = true;
            $i = 1;
            $novaAction = $action;
            while ($nomeCorreto) {

                if (!array_key_exists($novaAction, $this->controllerAction[$controller])) {
                    $this->controllerAction[$controller][$novaAction] = $dadosForm;
                    $nomeCorreto = false;
                } else {
                    $novaAction = $action . $i;
                }
                $i++;
            }
        } else {
            $this->controllerAction[$controller][$action] = $dadosForm;
        }
    }

    private function gerarDependencias() {

        foreach ($this->dependencias as $schemaTabela => $dados) {

            $this->setSchemaTabela($schemaTabela);
            $this->campoSelect = $dados['select'];

            $this->getDbTable();
            $this->getDTO();
            $this->getMapper();
        }
    }

    private function setDependencias($dep) {

        foreach ($dep as $schemaTabela => $valor) {

            if (array_key_exists($schemaTabela, $this->dependencias)) {

                foreach ($valor['select'] as $select) {

                    if (!empty($select)) {
                        if (empty($this->dependencias[$schemaTabela]['select'])) {
                            $this->dependencias[$schemaTabela]['select'][] = $select;
                        } else if (!in_array($select, $this->dependencias[$schemaTabela]['select'])) {
                            $this->dependencias[$schemaTabela]['select'][] = $select;
                        }
                    }
                }
            } else {
                $this->dependencias[$schemaTabela] = $valor;
            }
        }
    }

    public function gerar() {


        if (!empty($this->controllerAction)) {
            
            $ini = new Numenor_Gerador_Configs($this->dadosConexao);
            if ($ini->gerar()) {
                
                $caminho = 'application/configs/application.ini';
                $this->addArquivoProjeto($caminho, 'ini', $ini->getCodigo());
            }

            foreach ($this->controllerAction as $controller => $actions) {

                $controllerGen = new Numenor_Gerador_Controller($controller, $actions);

                if ($controllerGen->gerar()) {
                    $caminho = 'application/controllers/' . $controllerGen->getNome() . '.php';
                    $this->addArquivoProjeto($caminho, 'php', $controllerGen->getCodigo());
                }

                foreach ($actions as $action => $dadosForm) {

                    $viewGen = new Numenor_Gerador_View($controller, $action, $dadosForm);

                    if ($viewGen->gerar()) {
                        $caminho = 'application/views/scripts/' . $viewGen->getNomeController() . '/' . $viewGen->getNomeView() . '.phtml';
                        $this->addArquivoProjeto($caminho, 'php', $viewGen->getCodigo());
                    }

                    $formulario = new Numenor_Gerador_Form($dadosForm);
                    if ($formulario->gerar()) {

                        $caminho = 'application/forms/' . $formulario->getNome() . '.php';
                        $this->addArquivoProjeto($caminho, 'php', $formulario->getCodigo());

                        $this->setDependencias($formulario->getDependencias());
                    }
                }
            }

            if (!empty($this->dependencias)) {
                $this->gerarDependencias();
            }

            
        }

        return true;
    }

    public function getCodigo() {

        return $this->codigo;
    }

    public function getProjeto() {
        
        return $this->projeto;
    }

    public function getTabelasFormularios($formularios) {

        $tabela = array();

        foreach ($formularios as $formulario) {

            foreach ($formulario as $campo) {
                if (isset($campo['campo_tabela'])) {
                    
                }
            }
        }
    }

    public function setSchemaTabela($schemaTabela) {

        $splitCampo = explode('.', $schemaTabela);

        if (count($splitCampo) == 2) {
            $this->setSchema($splitCampo[0]);
            $this->setTabela($splitCampo[1]);
        } else {
            $this->setTabela($splitCampo[0]);
        }
    }

    /**
     * Responsável em receber o nome da Tabela ao qual o código deverá ser gerado
     *
     * @param string $tabela Nome da Tabela
     * @return Numenor_Gerador
     */
    public function setTabela($tabela) {

        $this->tabela = $tabela;
        return $this;
    }

    /**
     * Responsável em receber o nome do Schema ao qual a tabela pertence
     *
     * @param string $schema Nome do Schema
     * @return Numenor_Gerador
     */
    public function setSchema($schema) {

        $this->schema = $schema;
        return $this;
    }

    /**
     * Método retorna a estrutura do banco de dados contendo todos os schemas, tabelas e campos
     *
     * @return array
     */
    public function getEstruturaBanco() {

        return $this->objGerador->getBanco();
    }

    public function getView($controller, $nome, $dadosTitulo) {

        $view = new Numenor_Gerador_View($nome, $dadosTitulo);

        if ($view->gerar()) {

            $caminho = 'application/views/scripts/' . $controller . '/' . $view->getNome() . '.php';
            $this->addArquivoProjeto($caminho, 'php', $view->getCodigo());

            return true;
        } else {
            return false;
        }
    }

    /**
     * Método o código gerado para a classe DbTable através das informações como tabla e schema
     *
     * @return string|false
     */
    public function getDbTable() {

        $dbTable = new Numenor_Gerador_DbTable();
        $dbTable->setTabela($this->tabela)
                ->setPrimaryKey($this->getPrimaryKey())
                ->setSchema($this->schema);

        if ($dbTable->gerar()) {

            $caminho = 'application/models/DbTable/' . $dbTable->getNome() . '.php';
            $this->addArquivoProjeto($caminho, 'php', $dbTable->getCodigo());

            return true;
        } else {
            return false;
        }
    }

    /**
     * Método o código gerado para a classe DTO através das informações como schema, tabla e campos
     *
     * @return string|false
     */
    public function getDTO() {

        $dto = new Numenor_Gerador_DTO();
        $dto->setCampos($this->getCamposTabela())
                ->setTabela($this->tabela)
                ->setSchema($this->schema);

        foreach ($this->campoSelect as $campo) {
            $dto->setCampoSelect($campo['value'], $campo['text']);
        }


        if ($dto->gerar()) {

            $caminho = 'application/models/' . $dto->getNome() . '.php';
            $this->addArquivoProjeto($caminho, 'php', $dto->getCodigo());

            return true;
        } else {
            return false;
        }
    }

    /**
     * Método o código gerado para a classe Mapper através das informações como schema, tabla e campos
     *
     * @return string|false
     */
    public function getMapper() {


        $mapper = new Numenor_Gerador_Mapper();
        $mapper->setCampos($this->getCamposTabela())
                ->setTabela($this->tabela)
                ->setSchema($this->schema);

        foreach ($this->campoSelect as $campo) {
            $mapper->setCampoSelect($campo['value'], $campo['text']);
        }

        if ($mapper->gerar()) {

            $caminho = 'application/models/' . $mapper->getNome() . '.php';
            $this->addArquivoProjeto($caminho, 'php', $mapper->getCodigo());

            return true;
        } else {
            return false;
        }
    }

    /**
     * Método retorna a estrutura da tabela em formato de array contendo todos os campos
     *
     * @return array
     */
    public function getCamposTabela() {

        switch ($this->tipoBanco) {
            case 'PDO_MYSQL':
                return $this->objGerador->getTabela($this->tabela);
                break;

            case 'PDO_PGSQL':
                return $this->objGerador->getTabela($this->tabela, $this->schema);
                break;
        }
    }

    /**
     * Método retorna todas as PrimaryKey de uma determinada tabela
     *
     * @return array
     */
    public function getPrimaryKey() {

        switch ($this->tipoBanco) {
            case 'PDO_MYSQL':
                return $this->objGerador->getPrimaryKey($this->tabela);
                break;

            case 'PDO_PGSQL':
                return $this->objGerador->getPrimaryKey($this->tabela, $this->schema);
                break;
        }
    }

    /**
     * Método retorna os valores para preenchimento das options de um campo select
     *
     * @return array
     */
    public function getOptionsCampoSelect($schemaTabela, $campoValue, $campoText) {

        $query = $this->db->select();
        $query->from($schemaTabela, array('value' => $campoValue, 'text' => $campoText))
                ->order($campoText . ' asc');

        return $this->db->fetchAll($query);
    }

}