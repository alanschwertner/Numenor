<?php

/**
 * Classe responsável em gerar o código fonte para o arquivo application.ini
 *
 * @author Cristian Cardoso
 * @email ctncardoso@ctncardoso.com.br
 * @version 1.5
 * @data_modificacao 22/04/2011 14:25
 */
class Numenor_Gerador_Configs {

    private $dadosConexao;

    public function __construct($dadosConexao) {
        $this->dadosConexao = $dadosConexao;
    }

    /**
     * Método responsável em gerar o código fonte para o arquivo
     *
     * Retorna TRUE em caso de sucesso ou FALSE caso contrário
     *
     * @return bool
     */
    public function gerar() {

        $dataCriacao = Numenor_Gerador_Info::getDataCriacao();
        $versao = Numenor_Gerador_Info::getVersion();
        $criadoPor = Numenor_Gerador_Info::getCriador();

        $codigo = '';

        $codigo .= "includePaths.models = APPLICATION_PATH \"/models\"\r\n";
        $codigo .= "appnamespace = \"\"\r\n";
        $codigo .= "\r\n";

        $codigo .= "resources.db.adapter = {$this->dadosConexao['banco']}\r\n";
        $codigo .= "resources.db.params.host = {$this->dadosConexao['host']}\r\n";
        $codigo .= "resources.db.params.username = {$this->dadosConexao['usuario']}\r\n";
        $codigo .= "resources.db.params.password = {$this->dadosConexao['senha']}\r\n";
        $codigo .= "resources.db.params.dbname = {$this->dadosConexao['dbnome']}\r\n";
        $codigo .= "resources.db.isDefaultTableAdapter = true\r\n";

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