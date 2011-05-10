<?php

/**
 * Classe responsável em buscar e retornar as informações sobre a estrutura do
 * banco MySql.
 *
 * Podendo retornar:
 * Informações de uma unica tabela;
 * Informações de um schema e todas as suas tabelas;
 * Informações da base da dado inteira com todos os seus schemas e todas as suas tabelas;
 */
class Numenor_Gerador_MySql {

    /**
     * Variavel responsável em obtar a conexão de banco de dados
     *
     * @var Zend_Db
     */
    private $db;

    /**
     * Propriedade contendo o nome da base de dados
     *
     * @var string
     */
    private $schema;

    /**
     * Classe responsável em buscar e retornar as informações sobre a estrutura do
     * banco MySql.
     * 
     * @param Zend_Db $db Conexão com o banco de dados
     */
    public function __construct($db) {
        $this->db = $db;
        $connection = $this->db->getConfig();
        $this->schema = $connection['dbname'];
    }

    /**
     * Método responsável em buscar informações de todos os schemas tabelas e
     * campos do banco de dados.
     *
     * @return array
     */
    public function getBanco() {

        $sql = "SELECT
                    table_name AS tabela,
                    table_schema AS nome_schema
                FROM
                    information_schema.tables
                WHERE
                    table_schema = ?
                GROUP BY
                    table_name,
                    table_schema
                ORDER BY
                    table_schema,
                    table_name";

        $tabelas = $this->db->fetchAll($sql, $this->schema);

        $banco = array();

        foreach ($tabelas as $tabela) {
            $banco[''][$tabela['tabela']] = $this->getTabela($tabela['tabela']);
        }

        return $banco;
    }

    /**
     * Método responsável em efetuar uma consulta ao banco de dados para buscar
     * todos os campos e suas informações.
     *
     * @param string $tabela Nome da tabela
     * @return array
     */
    public function getTabela($tabela) {

        $sql = "SELECT
                    column_name AS nome,
                    data_type AS tipo,
                    character_maximum_length AS tamanho,
                    is_nullable AS nulo
                FROM
                    information_schema.columns
                WHERE
                    table_name = ?
                AND
                    table_schema = ?";

        $campos = $this->db->fetchAll($sql, array($tabela, $this->schema));

        $dados = array();

        /* ------------------ Define a estrutura dos Campos ----------------- */
        foreach ($campos as $campo) {

            $dados[$campo['nome']] = array(
                'tipo' => $campo['tipo'],
                'nulo' => ($campo['nulo'] == 'YES') ? true : false,
                'tamanho' => $campo['tamanho'],
                'chave_primaria' => false,
                'chave_estrangeira' => false
            );
        }
        /* ------------------ Define a estrutura dos Campos ----------------- */


        /* ---------------------- Seta as Foreign Key ----------------------- */
        $foreignKey = $this->getForeignKey($tabela);

        foreach ($foreignKey as $chave) {
            $dados[$chave['nome_campo']]['chave_estrangeira'] = true;
            $dados[$chave['nome_campo']]['referencia'] = array(
                'nome_schema_ref' => $chave['nome_schema_ref'],
                'nome_tabela_ref' => $chave['nome_tabela_ref'],
                'nome_campo_ref' => $chave['nome_campo_ref'],
                'nome_chave_ref' => $chave['nome_chave_ref']
            );
        }
        /* ----------------------- Seta as Foreign Key ---------------------- */


        /* ----------------------- Seta as Primary Key ---------------------- */
        $primaryKey = $this->getPrimaryKey($tabela);

        foreach ($primaryKey as $chave) {
            $dados[$chave['nome_campo']]['chave_primaria'] = true;
        }
        /* ----------------------- Seta as Primary Key ---------------------- */

        return $dados;
    }

    /**
     * Método responsável em efetuar uma consulta ao banco de dados para buscar
     * todas as Foreign Key de uma determinada tablela
     *
     * @param string $tabela Nome da tabela
     * @return array
     */
    public function getForeignKey($tabela) {

        /**
         * Consulta SQL Desenvolvida por Alan Pieske
         */
        $sql = "SELECT
                    tc.table_schema AS nome_schema,
                    tc.table_name AS nome_tabela,
                    kcu.column_name AS nome_campo,
                    tc.constraint_name AS nome_chave_ref,
                    /*kcu.referenced_table_schema*/ '' AS nome_schema_ref,
                    kcu.referenced_table_name AS nome_tabela_ref,
                    kcu.referenced_column_name AS nome_campo_ref
                FROM
                    information_schema.table_constraints tc
                INNER JOIN
                    information_schema.key_column_usage kcu ON tc.constraint_name = kcu.constraint_name
                AND
                    tc.table_schema = kcu.table_schema
                AND
                    tc.table_name = kcu.table_name
                WHERE
                    tc.table_name = ?
                AND
                    tc.constraint_type = 'FOREIGN KEY'
                AND
                    tc.constraint_schema = ?";

        return $this->db->fetchAll($sql, array($tabela, $this->schema));
    }

    /**
     * Método responsável em efetuar uma consulta ao banco de dados para buscar
     * todas as Primary Key de uma determinada tablela
     *
     * @param string $tabela Nome da tabela
     * @return array
     */
    public function getPrimaryKey($tabela) {

        $sql = "SELECT
                    column_name AS nome_campo,
                    ordinal_position AS posicao
                FROM
                    information_schema.table_constraints AS tc
                INNER JOIN
                    information_schema.key_column_usage AS kcu ON tc.constraint_name = kcu.constraint_name
                WHERE
                    tc.table_name = :tabela
                AND
                    tc.constraint_schema = :schema
                AND
                    kcu.table_name = :tabela
                AND
                    kcu.constraint_schema = :schema
                AND
                    constraint_type = 'PRIMARY KEY'
                ORDER BY
                    ordinal_position";

        return $this->db->fetchAll($sql, array(':tabela' => $tabela, ':schema' => $this->schema));
    }

}