<?php

/**
 * Classe para a tabela config.banco_dados
 *
 * Versão: 1.0
 * Criado Por: Númenor
 * Data Criação: 13/03/2011 22:26:59
 * Modificado Por:
 * Data Modificação:
 */
class DbTable_ConfigBancoDados extends Zend_Db_Table_Abstract {

    protected $_schema = 'config';
    protected $_name = 'banco_dados';
    protected $_primary = array(
        'id_banco_dados',
    );

}
