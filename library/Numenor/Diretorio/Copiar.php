<?php

final class Cigam_Diretorio_Copiar {


    private function  __construct() {}

    public static function copiaRecursiva($dirOrigem, $dirDestino) {
        $dir = opendir($dirOrigem);
        @mkdir($dirDestino);
        while (false !== ( $file = readdir($dir))) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if (is_dir($dirOrigem . '/' . $file)) {
                    self::copiaRecursiva($dirOrigem . '/' . $file, $dirDestino . '/' . $file);
                } else {
                    copy($dirOrigem . '/' . $file, $dirDestino . '/' . $file);
                }
            }
        }
        closedir($dir);
    }
    
}

