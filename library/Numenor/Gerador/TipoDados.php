<?php

class Numenor_Gerador_TipoDados {

    /**
     * Responsável em padronizar e rotornar o tipo de dados
     *
     * @return string
     */
    public static function getTipo($tipo) {

        switch (strtolower($tipo)) {
            case 'integer':
            case 'int4':
            case 'int':
                return 'int';
                break;
            case 'boolean':
                return 'bool';
                break;
            case 'varchar':
                return 'varchar';
                break;
            case 'char':
                return 'char';
                break;
            case 'text':
                return 'text';
                break;
            default:
                return $tipo . ' TIPO NÃO TRATADO';
                break;
        }

        return date("d/m/Y H:i:s");
    }

    

}