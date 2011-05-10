
$(function(){
    TipoValidacao.buscarDados();
    
    $('#tipo_validacao_salvar').click(function(){
        TipoValidacao.salvar();
    });
    
});

var TipoValidacao = {
    
    dados : [],
    
    buscarDados : function(){
        
        Formulario.DesmarcaSelecionado();
        
        Dialog.OpenAguardando(Msg.BuscarTipoValidacao, 255);
        
        $.ajax({
            type: 'POST',
            url: BASE_URL + "/tipo-validacao/",
            dataType: 'json',
            cache: false,
            success: function(json){

                if (json.retorno == 'sucesso'){
                        
                    TipoValidacao.dados = json.dados;
                        
                } else if (json.retorno == 'erro'){
                    Dialog.OpenMsgNotificacao(MsgErro.BuscarTipoValidacao, TituloDialog.TipoValidacao);
                }
                        
                Dialog.CloseAguardando();
            },
            error : function(){
                Dialog.CloseAguardando();
                Dialog.OpenMsgNotificacao(MsgErro.BuscarTipoValidacao, TituloDialog.TipoValidacao);
            }
        });
    },
    
    addLinhaListagem : function(json){
        var tr = $('<tr class="linha-tipo-validacao"></tr>');                            
      
        var td = '';                            
        td += '<td>' + json.nome + '</td>';                           
        td += '<td>' + json.expressao_regular + '</td>';                           
        
        td = $(td);
        
        var tdAcoes = '';
        tdAcoes += '<td class="Center">';                           
        tdAcoes += '<input type="hidden" class="val_id" value="' + json.id_tipo_validacao + '" />';                           
        tdAcoes += '<input type="hidden" class="val_nome" value="' + json.nome + '" />';                           
        tdAcoes += '<input type="hidden" class="val_nome_logico" value="' + json.nome_logico + '" />';                           
        tdAcoes += '<input type="hidden" class="val_regexp" value="' + json.expressao_regular + '" />';                           
        tdAcoes += '</td>';      
        
        tdAcoes = $(tdAcoes);
        
        var editar = $('<input type="button" class="botao-editar" />');
        var excluir = $('<input type="button" class="botao-excluir" />');                           
        
        editar.bind({
            click : function(){
                TipoValidacao.editar($(this));
            }
        });
        
        excluir.bind({
            click : function(){
                TipoValidacao.excluir($(this));
            }
        });

        tdAcoes.append(editar);
        tdAcoes.append(excluir);
        
        tr.append(td);
        tr.append(tdAcoes);
        
        $('#listagem-tipo-validacao').append(tr);
      
    },
    
    montaListagem : function(){
        
        $('#tipo_validacao_id').val(''),
        $('#tipo_validacao_nome').val(''),
        $('#tipo_validacao_nome_logico').val(''),
        $('#tipo_validacao_regexp').val('')
        
        $('.linha-tipo-validacao').remove();
        
        for (var i in TipoValidacao.dados){
            TipoValidacao.addLinhaListagem(TipoValidacao.dados[i]);
        }
    },
    
    editar : function(botao){
      
        $('#tipo_validacao_id').val($(botao).parent('td').find('input.val_id').val()),
        $('#tipo_validacao_nome').val($(botao).parent('td').find('input.val_nome').val()),
        $('#tipo_validacao_nome_logico').val($(botao).parent('td').find('input.val_nome_logico').val()),
        $('#tipo_validacao_regexp').val($(botao).parent('td').find('input.val_regexp').val())
        
        botao.parents('tr.linha-tipo-validacao:first').remove();
    },
    
    salvar : function(){
        
        Dialog.OpenAguardando(Msg.SalvandoTipoValidacao, 255);
        
        var dados = {
            id_tipo_validacao : $('#tipo_validacao_id').val(),
            nome : $('#tipo_validacao_nome').val(),
            nome_logico : $('#tipo_validacao_nome_logico').val(),
            expressao_regular : $('#tipo_validacao_regexp').val()
        };
        
        $.ajax({
            type: 'POST',
            url: BASE_URL + "/tipo-validacao/cadastrar/",
            dataType: 'json',
            cache: false,
            data : dados,
            success: function(json){

                if (json.retorno == 'sucesso'){
                    
                    if ($.trim(json.id_tipo_validacao) != ''){
                        dados.id_tipo_validacao = json.id_tipo_validacao;
                    }
                    
                    TipoValidacao.addLinhaListagem(dados);
                    
                    $('#tipo_validacao_id').val(''),
                    $('#tipo_validacao_nome').val(''),
                    $('#tipo_validacao_nome_logico').val(''),
                    $('#tipo_validacao_regexp').val('')
                        
                } else if (json.retorno == 'erro'){
                    Dialog.OpenMsgNotificacao(MsgErro.SalvarTipoValidacao, TituloDialog.TipoValidacao);
                }
                        
                Dialog.CloseAguardando();
            },
            error : function(){
                Dialog.CloseAguardando();
                Dialog.OpenMsgNotificacao(MsgErro.SalvarTipoValidacao, TituloDialog.TipoValidacao);
            }
        });
    },
    
    excluir : function(botao){
        
        Dialog.OpenAguardando(Msg.ExcluindoTipoValidacao, 255);
        
        var id = $(botao).parent('td').find('input.val_id').val();
        var linha = $(botao).parents('tr.linha-tipo-validacao:first');
        
        $.ajax({
            type: 'POST',
            url: BASE_URL + "/tipo-validacao/excluir/",
            dataType: 'json',
            cache: false,
            data : {
                id_tipo_validacao : id
            },
            success: function(json){

                if (json.retorno == 'sucesso'){
                    
                    linha.remove();
                        
                } else if (json.retorno == 'erro'){
                    Dialog.OpenMsgNotificacao(MsgErro.ExcluirTipoValidacao, TituloDialog.TipoValidacao);
                }
                        
                Dialog.CloseAguardando();
            },
            error : function(){
                Dialog.CloseAguardando();
                Dialog.OpenMsgNotificacao(MsgErro.ExcluirTipoValidacao, TituloDialog.TipoValidacao);
            }
        });
    }

}
