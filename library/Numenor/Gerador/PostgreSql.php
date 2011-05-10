<?php

/**
 * Classe responsável em buscar e retornar as informações sobre a estrutura do
 * banco PostgreSQL.
 *
 * Podendo retornar:
 * Informações de uma unica tabela;
 * Informações de um schema e todas as suas tabelas;
 * Informações da base da dado inteira com todos os seus schemas e todas as suas tabelas;
 */
class Numenor_Gerador_PostgreSql {

    /**
     * Variavel responsável em obtar a conexão de banco de dados
     *
     * @var Zend_Db
     */
    private $db;

    /**
     * Classe responsável em buscar e retornar as informações sobre a estrutura do
     * banco PostgreSQL.
     *
     * @param Zend_Db $db Conexão com o banco de dados
     */
    public function __construct($db) {
        $this->db = $db;
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
                    table_schema AS schema
                FROM
                    information_schema.COLUMNS
                WHERE
                    table_schema <> 'information_schema'
                AND
                    table_schema <> 'pg_catalog'
                GROUP BY
                    table_name,
                    table_schema
                ORDER BY
                    table_schema,
                    table_name";

        $tabelas = $this->db->fetchAll($sql);

        $banco = array();

        foreach ($tabelas as $tabela) {
            $banco[$tabela['schema']][$tabela['tabela']] = $this->getTabela($tabela['tabela'], $tabela['schema']);
        }

        return $banco;
    }

    /**
     * Método responsável em efetuar uma consulta ao banco de dados para buscar
     * todas as tabelas de um determinado schema os campos e suas informações.
     *
     * @param string $schema Nome do schema
     * @return array
     */
    public function getSchema($schema = 'default') {

        $sql = "SELECT
                    table_name AS tabela
                FROM
                    information_schema.columns
                WHERE
                    table_schema <> 'information_schema'
                AND
                    table_schema <> 'pg_catalog'
                AND
                    table_schema = ?
                GROUP BY
                    table_name";

        $tabelas = $this->db->fetchAll($sql, $schema);

        $dados = array();

        foreach ($tabelas as $tabela) {
            $dados[$tabela['tabela']] = $this->getTabela($tabela['tabela'], $schema);
        }

        return $dados;
    }

    /**
     * Método responsável em efetuar uma consulta ao banco de dados para buscar
     * todos os campos e suas informações.
     *
     * @param string $tabela Nome da tabela
     * @param string $schema Nome do schema
     * @return array
     */
    public function getTabela($tabela, $schema = 'default') {

        $sql = "SELECT
                    column_name AS nome,
                    udt_name AS tipo,
                    character_maximum_length AS tamanho,
                    is_nullable AS nulo
                FROM
                    information_schema.columns
                WHERE
                    table_name = ?
                AND
                    table_schema = ?";

        $campos = $this->db->fetchAll($sql, array($tabela, $schema));

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
        $foreignKey = $this->getForeignKey($tabela, $schema);

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
        $primaryKey = $this->getPrimaryKey($tabela, $schema);

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
     * @param string $schema Nome do schema
     * @return array
     */
    public function getForeignKey($tabela, $schema = 'default') {

        $sql = "SELECT
                    n.nspname AS nome_schema,
                    cl.relname AS nome_tabela,
                    a.attname AS nome_campo,
                    ct.conname AS nome_chave_ref,
                    nf.nspname AS nome_schema_ref,
                    clf.relname AS nome_tabela_ref,
                    af.attname AS nome_campo_ref
                FROM
                    pg_catalog.pg_attribute a
                JOIN
                    pg_catalog.pg_class cl ON (a.attrelid = cl.oid AND cl.relkind = 'r')
                JOIN
                    pg_catalog.pg_namespace n ON (n.oid = cl.relnamespace)
                JOIN
                    pg_catalog.pg_constraint ct ON (a.attrelid = ct.conrelid AND ct.confrelid != 0 AND ct.conkey[1] = a.attnum)
                JOIN
                    pg_catalog.pg_class clf ON (ct.confrelid = clf.oid AND clf.relkind = 'r')
                JOIN
                    pg_catalog.pg_namespace nf ON (nf.oid = clf.relnamespace)
                JOIN
                    pg_catalog.pg_attribute af ON (af.attrelid = ct.confrelid AND af.attnum = ct.confkey[1])
                WHERE
                    cl.relname = ?
                AND
                    n.nspname = ?";

        return $this->db->fetchAll($sql, array($tabela, $schema));
    }

    /**
     * Método responsável em efetuar uma consulta ao banco de dados para buscar
     * todas as Primary Key de uma determinada tablela
     *
     * @param string $tabela Nome da tabela
     * @param string $schema Nome do schema
     * @return array
     */
    public function getPrimaryKey($tabela, $schema = 'default') {

        $sql = "SELECT
                    column_name AS nome_campo,
                    ordinal_position AS posicao
                FROM
                    information_schema.table_constraints tc
                INNER JOIN
                    information_schema.key_column_usage kcu ON tc.constraint_name = kcu.constraint_name
                WHERE
                    tc.table_name = ?
                AND
                    tc.constraint_schema = ?
                AND
                    constraint_type = 'PRIMARY KEY'
                ORDER BY
                    ordinal_position";

        return $this->db->fetchAll($sql, array($tabela, $schema));
    }

    /**
     * Método responsável em buscar o nome de uma sequência
     *
     * @param string $tabela Nome da tabela
     * @param string $schema Nome do schema
     * @return null|array Casso não encontre um sequência  retorna NULL, se encontrar retorna um array bidimensional
     */
    public function getNameLastInsertId($tabela, $schema = 'default') {

        $chaves = $this->getPrimaryKey($tabela, $schema);

        if (!empty($chaves)) {

            $retorno = array();
            $sql = "SELECT pg_get_serial_sequence(?, ?) as nome_sequencia;";

            foreach ($chaves as $chave) {
                $resultado = $this->db->fetchRow($sql, array($schema . '.' . $tabela, $chave['nome_campo']));
                if (!empty($resultado)) {
                    $retorno[] = array(
                        'nome_campo' => $chave['nome_campo'],
                        'nome_sequencia' => $resultado['nome_sequencia']
                    );
                }
            }

            if (!empty($retorno)) {
                return $retorno;
            }
        }

        return null;
    }

}