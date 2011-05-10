<?php

/**
 * Class responsavel em limpar diretorio ou excluir arquivos
 * @author Cristian Cardoso
 */
class Numenor_Diretorio_Excluir {

    /**
     * Método responsavel em apagar arquivos de um diretorio
     * e pode apagar o proprio ou somente apagar seus filhos.
     *
     * @param string $diretorio caminho do diretorio
     * @param bool $apagar define se apaga o proprio diretorio ou nao
     * @return <type>
     */
    public static function diretorio($diretorio, $apagar = false) {
        if (is_dir($diretorio . '/')) {
            if (!$dh = opendir($diretorio))
                return;
            while (($obj = readdir($dh))) {
                if ($obj == '.' || $obj == '..')
                    continue;
                if (is_file($diretorio . '/' . $obj)) {
                    unlink($diretorio . '/' . $obj);
                } else if (is_dir($diretorio . '/' . $obj)) {
                    self::diretorio($diretorio . '/' . $obj, true);
                }
            }
            if ($apagar) {
                closedir($dh);
                rmdir($diretorio);
            }
        }
    }
    
}
