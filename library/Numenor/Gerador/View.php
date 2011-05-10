<?php

/**
 * Classe responsável em gerar o código fonte para as view
 *
 * @author Cristian Cardoso
 * @email ctncardoso@ctncardoso.com.br
 * @version 1.5
 * @data_modificacao 12/03/2011 00:10
 */
class Numenor_Gerador_View {

    private $controller;
    private $action;
    private $dadosForm;
    private $codigo;

    public function __construct($controller, $action, $dadosForm) {
        $this->controller = $controller;
        $this->action = $action;
        $this->dadosForm = $dadosForm;
    }

    // fazer tratamento para o nome da pasta do controller
    public function getNomeController() {
        return $this->controller;
    }

    public function getNomeView() {
        return $this->action;
    }

    /**
     * Retorna o código gerado
     * 
     * @return string 
     */
    public function getCodigo() {
        return $this->codigo;
    }

    public function gerar() {

        $codigo = '';
        
        foreach ($this->dadosForm['campos_formulario'] as $campo) {

            switch ($campo['tipo_campo']) {
                case 'titulo' :

                    if (!empty($campo['titulo'])) {
                        $codigo .= "<h1 class=\"{$campo['titulo_alinhamento']}\">{$campo['titulo']}</h1>\r\n";
                    }
                    if (!empty($campo['sub_titulo'])) {
                        $codigo .= "<h2 class=\"{$campo['sub_titulo_alinhamento']}\">{$campo['sub_titulo']}</h2>\r\n";
                    }
                    break;
            }
        }

        $codigo .= "\r\n";
        $codigo .= "<?php echo \$this->form; ?>\r\n";

        $this->codigo = $codigo;

        return true;
    }

}