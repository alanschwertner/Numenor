<?php

/**
 * Classe responsável em gerar o código fonte para os Controllers
 *
 * @author Cristian Cardoso
 * @email ctncardoso@ctncardoso.com.br
 * @version 1.5
 * @data_modificacao 19/03/2011 02:10
 */
class Numenor_Gerador_Controller {

    private $controller;
    private $actions;
    private $codigo;

    public function __construct($controller, $actions) {
        $this->controller = $controller;
        $this->actions = $actions;
    }

    /**
     * Método responsável em retornar o nome da action
     * 
     * @return string 
     */
    public function getNome() {
        return Numenor_Gerador_Nomes::getNomeClasse($this->controller . ' Controller');
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
        $codigo .= "<?php\r\n";
        $codigo .= "\r\n";
        $codigo .= "class {$this->getNome()} extends Zend_Controller_Action {\r\n";
        $codigo .= "\r\n";

        foreach ($this->actions as $action => $dadosForm) {
            $codigo .= $this->getAction($action, $dadosForm);
        }

        $codigo .= "    \r\n";
        $codigo .= "}\r\n";

        $this->codigo = $codigo;

        return true;
    }

    private function getAction($action, $dadosForm) {

        $nomeAction = Numenor_Gerador_Nomes::getNomeMetodo($action . ' Action');
        $nomeForm = Numenor_Gerador_Nomes::getNomeClasse($dadosForm['nome_formulario']);

        $tabelaCampos = array();

        $naoGerar = array('titulo', 'submit');

        foreach ($dadosForm['campos_formulario'] as $dadosCampo) {

            if (!in_array($dadosCampo['tipo_campo'], $naoGerar)) {
                $schemaTabela = '';
                $campo = '';

                if (empty($dadosCampo['campo_tabela'])) {
                    
                } else {

                    $campoTabela = explode('.', $dadosCampo['campo_tabela']);
                    if (count($campoTabela) == 3) {
                        $schemaTabela = $campoTabela[0] . '.' . $campoTabela[1];
                        $campo = $campoTabela[2];
                    } else {
                        $schemaTabela = $campoTabela[0];
                        $campo = $campoTabela[1];
                    }
                }

                if (array_key_exists($schemaTabela, $tabelaCampos)) {

                    $tabelaCampos[$schemaTabela] [] = $campo;
                } else {
                    $tabelaCampos[$schemaTabela] [] = $campo;
                }
            }
        }


        $codigo = '';
        $codigo .= "    public function {$nomeAction}() {\r\n";
        $codigo .= "\r\n";
        $codigo .= "        // Instância do formulário\r\n";
        $codigo .= "        \$form = new Form_{$nomeForm}();\r\n";
        $codigo .= "        // pega a requisição\r\n";
        $codigo .= "        \$requisicao = \$this->getRequest();\r\n";
        $codigo .= "        // verifica se a requisição foi postada\r\n";
        $codigo .= "        if (\$requisicao->isPost()) {\r\n";
        $codigo .= "            // verifica se o formulario for preenchido corretamente\r\n";
        $codigo .= "            if (\$form->isValid(\$this->_request->getPost())) {\r\n";
        $codigo .= "                \r\n";

        foreach ($tabelaCampos as $tabela => $campos) {

            $varDados = Numenor_Gerador_Nomes::getNomeVariavel('dados ' . $tabela);

            $codigo .= "                \${$varDados} = array(\r\n";
            foreach ($campos as $campo) {
                
                $nomeCampo = Numenor_Gerador_Nomes::getNomeCampo($tabela . ' ' . $campo);
                
                $codigo .= "                    '{$campo}' => \$form->getValue('{$nomeCampo}'),\r\n";
            }
            $codigo .= "                );\r\n";
            $codigo .= "\r\n";


            $nomeClass = Numenor_Gerador_Nomes::getNomeClasse($tabela);
            $varNomeClass = Numenor_Gerador_Nomes::getNomeVariavel($tabela);

            $codigo .= "                \${$varNomeClass} = new {$nomeClass}(\${$varDados});\r\n";
            $codigo .= "                \${$varNomeClass}->save();\r\n";
            $codigo .= "\r\n";
        }

        $codigo .= "            }\r\n";
        $codigo .= "        }\r\n";

        $codigo .= "\r\n";
        $codigo .= "        \$this->view->form = \$form;\r\n";
        $codigo .= "    }\r\n";

        return $codigo;
    }

}