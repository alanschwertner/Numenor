<?php

abstract class Numenor_Db_DomainObjectAbstract {

    protected $_mapper = null;

    public function __construct(array $options = null) {
        if (is_array($options)) {
            $this->setOptions($options);
        }
    }

    public function setOptions(array $options) {

        $methods = get_class_methods($this);

        foreach ($options as $key => $value) {
            $method = Numenor_Gerador_Nomes::getNomeMetodo('set ' . $key);

            if (in_array($method, $methods)) {
                $this->$method($value);
            }
        }

        return $this;
    }

    public function getId() {

        $id = array();
        $methods = get_class_methods($this);

        foreach ($this->_primary as $key) {

            $method = Numenor_Gerador_Nomes::getNomeMetodo('get ' . $key);

            if (in_array($method, $methods)) {
                $valor = $this->$method();
            } else {
                throw new Exception('Chave primaria não existe.');
            }

            if (!empty($valor)) {
                $id[$key] = $valor;
            }
        }

        if (empty($id)) {
            return false;
        }
        return true;
    }

    public function getMapper() {
        $m = new $this->_mapper;
        return $m;
    }

    public function save() {
        $this->getMapper()->save($this);
    }

    public function fetchAll() {
        return $this->getMapper()->fetchAll();
    }

    public function find($id) {
        return $this->getMapper()->find($id);
    }

    public function delete($id) {
        return $this->getMapper()->delete($id);
    }

    public function getLastInsertId() {
        return $this->getMapper()->getLastInsertId();
    }

}