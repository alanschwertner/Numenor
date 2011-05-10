<?php

class Numenor_Gerador_Info {

    /**
     * Responsável em rotornar a versão do Gerador
     *
     * @return string
     */
    public static function getDataCriacao() {
        return date("d/m/Y H:i:s");
    }

    /**
     * Responsável em rotornar a versão do Gerador
     *
     * @return string
     */
    public static function getVersion() {
        return '1.5';
    }

    /**
     * Responsável em rotornar o nome do Gerador
     *
     * @return string
     */
    public static function getCriador() {
        return 'Númenor';
    }

}