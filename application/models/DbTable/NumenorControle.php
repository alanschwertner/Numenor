<?php

/**
 * Classe para a tabela numenor.controle
 *
 * Versão: 1.0
 * Criado Por: Númenor
 * Data Criação: 27/02/2011 21:42:18
 * Modificado Por:
 * Data Modificação:
 */
class DbTable_NumenorControle extends Zend_Db_Table_Abstract {

    protected $_schema = 'numenor';
    protected $_name = 'controle';
    protected $_primary = array(
        'id_controle',
    );
}
