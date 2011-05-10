var Gerador = {

    Dados : [],
    CodigoGerado : {},
    Zip : '',

    GetDadosFormularios : function(){
        Gerador.Dados = [];
        
        $('.area-formulario').each(function(){
            
            var idForm = $(this).parent().attr('id');
            var nomeForm = $("a[href$=#" + idForm + "]").text();
            
            var camposFormulario = [];
            
            $(this).find('.itens-formulario li').each(function(){
                camposFormulario[camposFormulario.length] = Gerador.GetDadosCampo($(this));
            });
            
            var formulario = {
                nome_formulario : nomeForm,
                nome_controller : $(this).find('.controller').val(),
                nome_action : $(this).find('.action').val(),
                campos_formulario : camposFormulario
            };
            
            Gerador.Dados[Gerador.Dados.length] = formulario;
        });
        
        return Gerador.Dados;
    },
    
    ExibeCodigoArquivo : function(arq){
        
        var arquivo = arq.split('/');
        
        var diretorios = Gerador.CodigoGerado;
        
        for(var i in arquivo){
            diretorios = diretorios[arquivo[i]];
        }
        
        $('#codigo-arquivo').html(diretorios.content);
        
    },
    
    GerarCodigoProjeto : function(){
        Dialog.OpenAguardando(Msg.GerandoCodigo, 200);
        
        var dadosFormularios = Gerador.GetDadosFormularios();
        var dadosProjeto = Projeto.DadosProjeto;
        
        $.ajax({
            type: 'POST',
            data: {
                dados_conexao : Projeto.DadosProjeto.conexao,
                dados_formularios : dadosFormularios
            },
            url: BASE_URL + "/gerador/gerar-codigo/",
            dataType: 'json',
            cache: false,
            success: function(json){
                Dialog.CloseAguardando();

                if (json.retorno == 'sucesso'){
                    
                    Gerador.Zip = json.zip;
                    Gerador.CodigoGerado = json.codigo;
                    
                    var html = '';
                    html += '<ul id="projeto-gerado" class="filetree">';
                    html += MenuTree.MontaMenu(Gerador.CodigoGerado, '');
                    html += '</ul>';
                    
                    html = $(html);
                    
                    html.find('.arquivo').bind({
                        click : function(){
                            Gerador.ExibeCodigoArquivo($(this).attr('arq'));
                            
                        }
                    })
                    
                    $('#menu-codigo-gerado').html('');
                    $('#menu-codigo-gerado').append(html);
                    $("#projeto-gerado").treeview();
                    
                } else {
                    Dialog.OpenMsgNotificacao(MsgErro.GerarCodigo, TituloDialog.ErroGerarCodigo);
                }

            },
            error : function(){
                Dialog.CloseAguardando();
                Dialog.OpenMsgNotificacao(MsgErro.GerarCodigo, TituloDialog.ErroGerarCodigo);
            }
        });
    },

    BuscarOptionsCampoSelect : function(schemaTabela, campoValue, campoText){
        
        Dialog.OpenAguardando('Buscando valores para o select...', 300);
        
        $.ajax({
            type: 'POST',
            data: {
                dados_conexao : Projeto.DadosProjeto.conexao,
                schema_tabela : schemaTabela,
                campo_value : campoValue,
                campo_text : campoText
            },
            url: BASE_URL + "/gerador/options-select/",
            dataType: 'json',
            cache: false,
            success: function(json){

                if (json.retorno == 'sucesso'){
                    
                    Grid.SetDataCampoSelect(json.dados);
                    
                } else if (json.retorno == 'erro'){
                }
            
                Grid.ExibePropriedades();
                Dialog.CloseAguardando();

            },
            error : function(){
                Dialog.CloseAguardando();

                $('#proj_resultado_conexao').val('false');
                $('#aguardando_teste_conexao').html(Img.Erro);
                Dialog.OpenMsgNotificacao('MsgErro.TipoDadosTesteConexao', 'TituloDialog.ErroTestarConexao');
            }
        });
    },
    
    /* ------------------ Busca informação dos campos --------------------- */

    GetDadosCampo : function(li){

        var retorno = '';
        var Classes = li.attr('class').split(' ');

        $.each(Classes, function(key, value){

            switch(value){
                case 'campo-input-text':
                    retorno = Gerador.GetDadosCampoText(li);
                    break;
                case 'campo-input-password':
                    break;
                case 'campo-input-submit':
                    retorno = Gerador.GetDadosCampoSubmit(li);
                    break;
                case 'campo-select':
                    retorno = Gerador.GetDadosCampoSelect(li);
                    break;
                case 'campo-textarea':
                    retorno = Gerador.GetDadosCampoTextarea(li);
                    break;
                case 'titulo':
                    retorno = Gerador.GetDadosTitulo(li);
                    break;
            }
        });

        return retorno;

    },

    GetDadosTitulo : function(li){
        var LabelTitulo = li.find('h2');
        var LabelSubTitulo = li.find('h5');

        var dados = {
            tipo_campo : 'titulo',
            titulo : LabelTitulo.text(),
            titulo_alinhamento : Gerador.GetAlinhamento(LabelTitulo),
            sub_titulo : LabelSubTitulo.text(),
            sub_titulo_alinhamento : Gerador.GetAlinhamento(LabelSubTitulo)
        }

        return dados;
    },

    GetDadosCampoText : function(li){

        var campo = li.find('input');
        var label = li.find('div.label-campo-formulario');

        var dados = {
            tipo_campo : 'text',
            titulo : Gerador.GetTituloCampo(label),
            titulo_alinhamento : Gerador.GetAlinhamento(label),
            titulo_largura : Gerador.GetLargura(label),
            requerido : Gerador.GetRequerido(li),
            tipo_validacao : Gerador.GetTipoValidacao(campo),
            campo_largura : Gerador.GetLargura(campo),
            valor_padrao : campo.val(),
            campo_tabela : Gerador.GetCampoTabela(campo)
        };

        return dados;
    },
    
    GetDadosCampoSubmit : function(li){

        var campo = li.find('input:submit');
        var divCampo = li.find('div.input-formulario');

        var dados = {
            tipo_campo : 'submit',
            titulo : campo.val(),
            alinhamento : Gerador.GetAlinhamento(divCampo),
            largura : Gerador.GetLargura(campo)
        };

        return dados;
    },

    GetDadosCampoTextarea : function(li){

        var campo = li.find('textarea');
        var label = li.find('div.label-campo-formulario');

        var dados = {
            tipo_campo : 'textarea',
            titulo : Gerador.GetTituloCampo(label),
            titulo_alinhamento : Gerador.GetAlinhamento(label),
            titulo_largura : Gerador.GetLargura(label),
            requerido : Gerador.GetRequerido(li),
            campo_largura : Gerador.GetLargura(campo),
            campo_altura : Gerador.GetAltura(campo),
            valor_padrao : campo.val(),
            campo_tabela : Gerador.GetCampoTabela(campo)
        };

        return dados;
    },

    GetDadosCampoSelect : function(li){

        var campo = li.find('select');
        var label = li.find('div.label-campo-formulario');

        var dados = {
            tipo_campo : 'select',
            titulo : Gerador.GetTituloCampo(label),
            titulo_alinhamento : Gerador.GetAlinhamento(label),
            titulo_largura : Gerador.GetLargura(label),
            requerido : Gerador.GetRequerido(li),
            campo_largura : Gerador.GetLargura(campo),
            valor_padrao : campo.val(),
            campo_tabela : Gerador.GetCampoTabela(campo),
            values : Gerador.GetOptionsSelect(campo),
            tabela_options : Gerador.GetTabelaOptionSelect(campo),
            campo_option_text : Gerador.GetOptionTextSelect(campo),
            campo_option_value : Gerador.GetOptionValueSelect(campo)
            
        };
        return dados;
    },

    GetTituloCampo : function(obj){
        return obj.remove('span.campo-requerido').text();
    },

    GetAlinhamento : function(obj){

        if (obj.is('.Left')){
            return 'Left';
        } else if (obj.is('.Right')){
            return 'Right';
        }else{
            return 'Center';
        }

    },

    GetLargura : function(obj){
        return obj.width();
    },

    GetAltura : function(obj){
        return obj.height();
    },

    GetRequerido : function(li){
        if(li.find('span').is('.campo-requerido')){
            return 'sim';
        } else {
            return 'nao';
        }
    },

    GetTipoValidacao : function(obj){
        return obj.attr('valida');
    },

    GetCampoTabela : function(obj){
        return obj.attr('tabela_campo');
    },

    GetOptionsSelect : function(obj){
        var dados = [];

        obj.find('option').each(function(){

            dados[dados.length] = {
                value : $(this).val(),
                text : $(this).text()
            }
        });

        return dados;
    },
        
    GetTabelaOptionSelect : function(obj){
        return obj.attr('tabela_options');
    },
            
    GetOptionValueSelect : function(obj){
        return obj.attr('campo_option_value');
    },
          
    GetOptionTextSelect : function(obj){
        return obj.attr('campo_option_text');
    }
    
    
}