<?php

class Zend_View_Helper_CampoTabela {

    private $tamanho = '16x16';
    public $nome;
    public $imagem;
    public $tipo;

    public function campoTabela($nome, $dados) {

        $imagem = 'db.Column';

        if ($dados['chave_primaria']) {
            $imagem = 'db.Column.pk';
        } else if ($dados['chave_estrangeira']) {
            if ($dados['nulo']) {
                $imagem = 'db.Column.fk';
            } else {
                $imagem = 'db.Column.fknn';
            }
        }

        $this->imagem = '<img src="/images/db/' . $imagem . '.16x16.png">';
        $this->nome = $nome;

        $tipo = $dados['tipo'];

        if (!empty($dados['tamanho'])) {
            $tipo .= '(' . $dados['tamanho'] . ')';
        }

        $this->tipo = $tipo;
        
        return $this;
    }

}
