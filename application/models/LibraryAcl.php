<?php

class LibraryAcl extends Zend_Acl {

    public function __construct() {

        /* Inicio tipos de usuarios ou grupos */
        $this->addRole(new Zend_Acl_Role('visitantes'));
        $this->addRole(new Zend_Acl_Role('usuarios'), 'visitantes');
        $this->addRole(new Zend_Acl_Role('admins'), 'usuarios');
        /* Fim tipos de usuarios ou grupos */



        /* Inicio recursos */
        $this->add(new Zend_Acl_Resource('default:autenticacao'));
        $this->add(new Zend_Acl_Resource('default:gerador'));
        $this->add(new Zend_Acl_Resource('default:tipo-validacao'));
        $this->add(new Zend_Acl_Resource('default:tipo-banco-dados'));
        $this->add(new Zend_Acl_Resource('default:banco-dados'));
        $this->add(new Zend_Acl_Resource('default:index'));
        $this->add(new Zend_Acl_Resource('default:error'));
        /* Fim recursos */

        $this->allow('visitantes', 'default:autenticacao', array('index', 'logar'));

        /* Inicio permições dos admins */
        $this->allow('admins');
        /* Fim permições dos admins */

        /* Inicio restrições dos admins */
        $this->deny('admins', 'default:autenticacao', 'index');
        /* Fim restrições dos admins */
    }

}