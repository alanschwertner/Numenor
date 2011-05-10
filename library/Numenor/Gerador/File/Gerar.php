<?php

class Numenor_Gerador_File_Gerar {

    public static function projeto($projeto, $diretorio = null) {


        $dir = '';

        if ($diretorio == null) {

            $diretorio = APPLICATION_PATH . '/../public/arquivos/';

            Numenor_Diretorio_Excluir::diretorio($diretorio);

            $dir = date("Y_m_d_H_i_s");
            $diretorio .= $dir . '/';
            mkdir($diretorio);
        }

        foreach ($projeto as $nome => $dados) {

            if ($dados['type'] == 'folder') {

                $pasta = $diretorio . $nome . '/';
                mkdir($pasta);
                self::projeto($projeto[$nome], $pasta);
            } else if ($dados['type'] == 'file') {

                $fp = fopen($diretorio . $nome, "a");
                fwrite($fp, $dados['content']);
                fclose($fp);
            }
        }

        if ($dir != '') {
            return $dir;
        } else {
            return true;
        }
    }

    public static function zip($diretorio) {

        $dir = APPLICATION_PATH . '/../public/arquivos/' . $diretorio . '/projeto/';

        if (is_dir($dir)) {

            $zip = new ZipArchive();
            $zip->open(APPLICATION_PATH . '/../public/zip/Numenor_' . $diretorio . '.zip', ZipArchive::CREATE);

            self::zipDiretorio($dir, $zip, 'Numenor_' . $diretorio . '/');

            return '/zip/Numenor_' . $diretorio . '.zip';
        } else {
            return false;
        }
    }

    private static function zipDiretorio($dir, &$zipArchive, $zipdir = '') {
        //echo $dir . '<br>';

        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {

                //Add the directory
                $zipArchive->addEmptyDir($zipdir);

                // Loop through all the files
                while (($file = readdir($dh)) !== false) {

                    //If it's a folder, run the function again!
                    if (!is_file($dir . $file)) {
                        // Skip parent and root directories
                        if (($file !== ".") && ($file !== "..")) {
                            self::zipDiretorio($dir . $file . '/', $zipArchive, $zipdir . $file . "/");
                        }
                    } else {
                        // Add the files
                        $zipArchive->addFile($dir . $file, $zipdir . $file);
                    }
                }
            }
        }
    }

}