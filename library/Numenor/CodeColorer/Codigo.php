<?php

class Numenor_CodeColorer_Codigo {

    public static function getCodeColorerProjeto(&$codigos) {

        foreach ($codigos as $nome => &$dados) {

            if ($dados['type'] == 'folder') {
                self::getCodeColorerProjeto($codigos[$nome]);
            } else if ($dados['type'] == 'file') {

                $geshi = new Numenor_CodeColorer_Geshi($dados['content'], $dados['tipo']);
                $dados['content'] = $geshi->parse_code();
            }
        }
    }

}