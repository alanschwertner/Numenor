<?php

class Numenor_Gerador_Nomes {

    public static function getNomeVariavel($string) {

        $camposEmPartes = explode("_", strtolower(preg_replace('/[^a-zA-Z]/', '_', trim($string))));
        $i = 0;
        $nomeVar = '';
        foreach ($camposEmPartes as $parte) {
            $nomeVar .= ( ($i != 0) ? ucfirst($parte) : $parte);
            $i++;
        }

        return $nomeVar;
    }

    /**
     * Ira padronizar a geração do nome das propriedades.
     *
     * @param string $string
     * @return string
     */
    public static function getNomePropriedade($string) {
        return self::getNomeVariavel($string);
    }

    /**
     * Ira padronizar a geração do nome dos métodos.
     *
     * @param string $string
     * @return string
     */
    public static function getNomeMetodo($string) {
        return self::getNomeVariavel($string);
    }

    /**
     * Responsável em padronizar a geração do nome das classes
     *
     * @param string $string Nome que deverá ser transformado em nome de classe
     * @return string
     */
    public static function getNomeClasse($string) {

        $tabelaEmPartes = explode("_", preg_replace('/[^a-zA-Z]/', '_', $string));
        return str_replace(' ', '', ucwords(implode(" ", $tabelaEmPartes)));
    }

    /**
     * Responsável em padronizar a geração do nome dos campos do formulário
     * 
     * @param string $nome
     * @return string 
     */
    public static function getNomeCampo($nome) {
        return preg_replace('/[^a-zA-Z]/', '_', $nome);
    }

}