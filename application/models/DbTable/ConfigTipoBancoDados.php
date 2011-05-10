<?php

/**
 * Classe para a tabela config.tipo_banco_dados
 *
 * Versão: 1.0
 * Criado Por: Númenor
 * Data Criação: 27/02/2011 21:41:38
 * Modificado Por:
 * Data Modificação:
 */
class DbTable_ConfigTipoBancoDados extends Zend_Db_Table_Abstract {

    protected $_schema = 'config';
    protected $_name = 'tipo_banco_dados';
    protected $_primary = array(
        'id_tipo_banco_dados',
    );
}
