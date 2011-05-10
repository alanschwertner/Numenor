// http://code.google.com/intl/pt-BR/apis/analytics/docs/tracking/eventTrackerGuide.html

$(function(){

    /* -------------------------- Aguardando -------------------------------
     *
     * Dialog para exibir a ampulieta de aguardando
     **/
    $("#dialog-aguardando").dialog({
        closeOnEscape: false,
        autoOpen: false,
        resizable: false,
        height: 100,
        width: 100,
        modal: true,
        open: function(event, ui) {
            $(this).closest('.ui-dialog').find('.ui-dialog-titlebar-close').hide();
        }
    });
    /* ----------------------- Fim Aguardando ------------------------------ */

    
    /* ---------------------------- Tabelas DB -----------------------------
     *
     * Dialog para exibir todas as tabelas do banco de dados
     **/
    $("#dialog-tabelas-db").dialog({
        autoOpen: false,
        resizable: true,
        height: 550,
        width: 900,
        modal: true,
        open: function(event, ui) {
            $('#dialog-tabelas-db .dialog-tabelas-banco').html();

            var html = '';

            var schemas = Projeto.DadosProjeto.tabelas;
            
            for (var s in schemas){
                var tabelas = schemas[s];

                for (var t in tabelas){
                    var campos = tabelas[t];

                    html += '<span class="tabela ui-widget ui-widget-content ui-helper-clearfix ui-corner-all">';
                    html += '    <div class="nome ui-widget-header ui-corner-all">';
                    html += '        <span class="ui-icon ui-icon-minusthick"></span>';
                    html += '        ' + (($.trim(s) == '') ? t : s + '.' + t);
                    html += '    </div>';
                    html += '    <div class="campos">';
                    html += '        <table>';

                    for (var c in campos){

                        html += '            <tr>';
                        html += '                <td>';
                        html += '                    ' + GetImgCampoTabela(campos[c]);
                        html += '                </td>';
                        html += '                <td>';
                        html += '                    ' + c;
                        html += '                </td>';
                        html += '                <td>';
                        html += '                    ' + GetItipoDados(campos[c]);
                        html += '                </td>';
                        html += '            </tr>';

                    }

                    html += '        </table>';
                    html += '    </div>';
                    html += '</span>';
                }
            }

            $('#dialog-tabelas-db .dialog-tabelas-banco').html(html);

            $( ".dialog-tabelas-banco .tabela").draggable({
                handle : 'div.nome',
                containment: '#dialog-tabelas-db'
            });

        },
        buttons: {
            'Fechar': function() {
                $(this).dialog('close');
            }
        }
    });
    
    function GetItipoDados(campo){
        
        switch( campo.tipo.toUpperCase()){
            case 'INT':
            case 'TEXT':
                return campo.tipo.toUpperCase();
                break;
            case 'VARCHAR':
            case 'CHAR':
                return campo.tipo.toUpperCase() + '(' + campo.tamanho + ')';
                break;
            default :
                return campo.tipo.toUpperCase();
                break;
        }

    }

    function GetImgCampoTabela(campo){

        if (campo.chave_primaria){
            return Img.ChavePrimaria;
        }else if (campo.chave_estrangeira){
            if (campo.nulo){
                return Img.ChaveEstrangeira;
            }else{
                return Img.ChaveEstrangeiraNulo;
            }
        }else{
            return Img.CampoTabela;
        }

    }

    /* -------------------------- Fim Tabelas DB --------------------------- */

    
    /* ----------------------------- Gerador -------------------------------
     *
     * Dialog para exibir todo o codigo gerado com o Númenor
     **/
    $("#dialog-gerar-codigo").dialog({
        autoOpen: false,
        resizable: true,
        height: 550,
        width: 1000,
        modal: true,
        open : function(){
            $('#menu-codigo-gerado').html('');
            $('#codigo-arquivo').html('');
            
            if (!codigoGerado){
                codigoGerado = $("#dialog-gerar-codigo").layout(LayoutCodigoGerado);
            }else{
                codigoGerado.resizeAll();
            }
        },	
        resize:	function(){
            if (codigoGerado){
                codigoGerado.resizeAll();
            }
        },
        buttons: {
            'Download': function() {
                _gaq.push(['_trackPageview', '/dialog/gerar-codigo/download/']);
                window.open(BASE_URL + '/gerador/zip/zip/' + Gerador.Zip);
            },
            'Fechar': function() {
                _gaq.push(['_trackPageview', '/dialog/gerar-codigo/fechar/']);
                $(this).dialog('close');
            }
        }
    });
    /* -------------------------- Fim Gerador ------------------------------ */


    /* ----------------------- Buscar Dados Tabela -------------------------
     *
     * Dialog para exibir todo o codigo gerado com o Númenor
     **/
    $("#dialog-buscar-dados-tabelas").dialog({
        autoOpen: false,
        resizable: true,
        width: 550,
        modal: true,
        buttons: {
            'Fechar': function() {
                _gaq.push(['_trackPageview', '/dialog/buscar-dados-tabela/fechar/']);
                $(this).dialog('close');
            },
            'Buscar': function() {
                _gaq.push(['_trackPageview', '/dialog/buscar-dados-tabela/buscar/']);

                Dialog.OpenAguardando('Buscando Tabelas...', 200)

                $.ajax({
                    type: 'POST',
                    data: {
                        dados_conexao : Projeto.DadosProjeto.conexao
                    },
                    url: BASE_URL + "/gerador/dados-tabelas/",
                    dataType: 'json',
                    cache: false,
                    success: function(json){
                        Dialog.CloseAguardando();

                        if (json.retorno == 'sucesso'){
                            Projeto.DadosProjeto.tabelas = json.dados;
                            $("#dialog-buscar-dados-tabelas").dialog('close');
                        } else if (json.retorno == 'erro'){
                            Dialog.OpenMsgNotificacao(MsgErro.BuscarDadosTabela, TituloDialog.ErroBuscarDadosTabela);
                        }

                    },
                    error : function(){
                        Dialog.CloseAguardando();
                        Dialog.OpenMsgNotificacao(MsgErro.TipoDadosBuscarDadosTabela , TituloDialog.ErroBuscarDadosTabela);
                    }
                });

            }
        }
    });
    /* ----------------------Fim Buscar Dados Tabela ----------------------- */


    /* ---------------------------- Novo Projeto ---------------------------
     *
     * Dialog para criar um novo projeto
     **/

    var DadosNovoProjeto ={
        nome_projeto : $('#proj_nome_projeto'),
        conexao_banco : $('#proj_conexao_banco'),
        banco : $('#proj_banco'),
        host : $('#proj_host'),
        usuario : $('#proj_usuario'),
        senha : $('#proj_senha'),
        dbnome : $('#proj_dbnome'),
        resultado_conexao : $('#proj_resultado_conexao'),
        testar_conexao : $('#proj_testar_conexao'),
        nome_formulario : $('#proj_nome_formulario'),
        nome_controller : $('#proj_nome_controller'),
        nome_action : $('#proj_nome_action')
    }

    $('#proj_conexao_banco').change(function(){
        if(DadosNovoProjeto.conexao_banco.is(':checked')){
            DadosNovoProjeto.banco.attr('disabled', 'disabled');
            DadosNovoProjeto.host.attr('disabled', 'disabled');
            DadosNovoProjeto.usuario.attr('disabled', 'disabled');
            DadosNovoProjeto.senha.attr('disabled', 'disabled');
            DadosNovoProjeto.dbnome.attr('disabled', 'disabled');
            DadosNovoProjeto.testar_conexao.attr('disabled', 'disabled');
        } else {
            DadosNovoProjeto.banco.attr('disabled', '');
            DadosNovoProjeto.host.attr('disabled', '');
            DadosNovoProjeto.usuario.attr('disabled', '');
            DadosNovoProjeto.senha.attr('disabled', '');
            DadosNovoProjeto.dbnome.attr('disabled', '');
            DadosNovoProjeto.testar_conexao.attr('disabled', '');
            
        }
    });

    $('#proj_testar_conexao').click(function(){
        
        if($.trim(DadosNovoProjeto.banco.val()) == '0'){
            Dialog.OpenMsgNotificacao('Preencha corretamente o campo Banco', 'Preencha o(s) campo(s)');
            return false;
        }
        if($.trim(DadosNovoProjeto.host.val()) == ''){
            Dialog.OpenMsgNotificacao('Preencha corretamente o campo Host', 'Preencha o(s) campo(s)');
            return false;
        }
        if($.trim(DadosNovoProjeto.usuario.val()) == ''){
            Dialog.OpenMsgNotificacao('Preencha corretamente o campo Usuário', 'Preencha o(s) campo(s)');
            return false;
        }
        
        if($.trim(DadosNovoProjeto.dbnome.val()) == ''){
            Dialog.OpenMsgNotificacao('Preencha corretamente o campo DB Nome', 'Preencha o(s) campo(s)');
            return false;
        }

        _gaq.push(['_trackPageview', '/dialog/novo-projeto/teste-conexao/']);
        $('#aguardando_teste_conexao').html(Img.Aguardando);

        $.ajax({
            type: 'POST',
            data: {
                banco : DadosNovoProjeto.banco.val(),
                host : DadosNovoProjeto.host.val(),
                usuario : DadosNovoProjeto.usuario.val(),
                senha : DadosNovoProjeto.senha.val(),
                dbnome : DadosNovoProjeto.dbnome.val()
            },
            url: BASE_URL + "/gerador/teste-conexao/",
            dataType: 'json',
            cache: false,
            success: function(json){

                if (json.retorno == 'sucesso'){
                    $('#proj_resultado_conexao').val('true');

                    $('#aguardando_teste_conexao').html(Img.Sucesso);
                } else if (json.retorno == 'erro'){
                    $('#proj_resultado_conexao').val('false');

                    $('#aguardando_teste_conexao').html(Img.Erro);
                    var msg = MsgErro.TesteConexao;
                    msg += json.msg;

                    Dialog.OpenMsgNotificacao(msg, TituloDialog.ErroTestarConexao);
                }

            },
            error : function(){

                $('#proj_resultado_conexao').val('false');
                $('#aguardando_teste_conexao').html(Img.Erro);
                Dialog.OpenMsgNotificacao(MsgErro.TipoDadosTesteConexao, TituloDialog.ErroTestarConexao);
            }
        });
    });

    $("#tabs-novo-projeto").tabs();
    
    $("#dialog-novo-projeto").dialog({
        autoOpen: false,
        resizable: false,
        height: 450,
        width: 450,
        modal: true,
        buttons: {
            'Cancelar': function() {
                _gaq.push(['_trackPageview', '/dialog/novo-projeto/cancelar/']);
                $(this).dialog('close');
            },
            'Criar Projeto': function() {
                _gaq.push(['_trackPageview', '/dialog/novo-projeto/criar-projeto/']);

                if($.trim(DadosNovoProjeto.nome_projeto.val()) == ''){
                    $("#dialog-novo-projeto").tabs({
                        selected: 0
                    });
                    Dialog.OpenMsgNotificacao('Preencha corretamente o campo Nome do Projeto', 'Preencha o(s) campo(s)');
                    return false;
                }
                
                if(!DadosNovoProjeto.conexao_banco.is(':checked')){
                    $("#dialog-novo-projeto").tabs({
                        selected: 1
                    });

                    if($.trim(DadosNovoProjeto.banco.val()) == '0'){
                        Dialog.OpenMsgNotificacao('Preencha corretamente o campo Banco', 'Preencha o(s) campo(s)');
                        return false;
                    }
                    if($.trim(DadosNovoProjeto.host.val()) == ''){
                        Dialog.OpenMsgNotificacao('Preencha corretamente o campo Host', 'Preencha o(s) campo(s)');
                        return false;
                    }
                    if($.trim(DadosNovoProjeto.usuario.val()) == ''){
                        Dialog.OpenMsgNotificacao('Preencha corretamente o campo Usuário', 'Preencha o(s) campo(s)');
                        return false;
                    }
                    if($.trim(DadosNovoProjeto.dbnome.val()) == ''){
                        Dialog.OpenMsgNotificacao('Preencha corretamente o campo DB Nome', 'Preencha o(s) campo(s)');
                        return false;
                    }
                    if($.trim(DadosNovoProjeto.resultado_conexao.val()) == 'false'){
                        Dialog.OpenMsgNotificacao('Efetue o teste de conexão para certificar que os dados estejam corretos', 'Teste de Conexão');
                        return false;
                    }

                }

                if($.trim(DadosNovoProjeto.nome_formulario.val()) == ''){
                    $("#dialog-novo-projeto").tabs({
                        selected: 2
                    });
                    Dialog.OpenMsgNotificacao('Preencha corretamente o campo Nome do Formulário', 'Preencha o(s) campo(s)');
                    return false;
                }

                Projeto.NovoProjeto(DadosNovoProjeto);
                $(this).dialog('close');

                if(!DadosNovoProjeto.conexao_banco.is(':checked')){
                    $("#dialog-buscar-dados-tabelas").dialog('open');
                }
            }
        }
    });
    /* -------------------------- Fim Novo Projeto ------------------------- */
    
    
    /* -------------------------- Novo Formulário --------------------------
     *
     * Dialog para criar um novo formulário
     **/
    $("#tabs-novo-formulario").tabs();
    
    
    var DadosNovoForm = {
        nome_formulario : $('#form_nome_formulario'),
        nome_controller : $('#form_nome_controller'),
        nome_action : $('#form_nome_action')
    }

    $("#dialog-novo-formulario").dialog({
        autoOpen: false,
        resizable: false,
        height: 350,
        width: 450,
        modal: true,
        open : function(){
            DadosNovoForm.nome_formulario.val('');
            DadosNovoForm.nome_controller.val('');
            DadosNovoForm.nome_action.val('');
        },
        buttons: {
            'Cancelar': function() {
                _gaq.push(['_trackPageview', '/dialog/novo-formulario/cancelar/']);
                $(this).dialog('close');
            },
            'Novo': function() {
                _gaq.push(['_trackPageview', '/dialog/novo-formulario/novo/']);
                
                if($.trim(DadosNovoForm.nome_formulario.val()) == ''){
                    Dialog.OpenMsgNotificacao('Preencha corretamente o campo Nome do Formulário', 'Preencha o(s) campo(s)');
                    return false;
                }

                Projeto.NovoFormulario(DadosNovoForm);
                $(this).dialog('close');
            }
        }
    });
    /* ------------------------ Fim Novo Formulário ------------------------ */


    /* ---------------------------- Notificações ---------------------------
     *
     * Dialog para exibir as mensagens de notificação
     **/
    $("#dialog-notificacao").dialog({
        autoOpen: false,
        resizable: false,
        modal: true,
        buttons: {
            'OK': function() {
                _gaq.push(['_trackPageview', '/dialog/notificacao/ok/']);
                $(this).dialog('close');
            }
        }
    });
    /* -------------------------- Fim Notificações ------------------------- */


    /* ------------------------------- Excluir -----------------------------
     *
     * Dialog para confirmação de exclusão de um determinado registro.
     **/
    $("#dialog-excluir-linha-formulario").dialog({
        autoOpen: false,
        resizable: false,
        modal: true,
        width: 400,
        buttons: {
            'Não': function() {
                _gaq.push(['_trackPageview', '/dialog/excluir-linha-formulario/nao/']);
                $(this).dialog('close');
            },
            'Sim': function() {
                _gaq.push(['_trackPageview', '/dialog/excluir-linha-formulario/sim/']);
                $('li.ui-selected').remove();
                Grid.ExibeMenuPropriedadesFormulario();
                initPaneScrollbar('center', outerLayout.panes.center);
                $(this).dialog('close');
            }
        }
    });
    /* ---------------------------- Fim Excluir ---------------------------- */
    
    
    /* --------------------------- Tipo Validação --------------------------
     *
     * Cadastrar e editar os tipos de validações.
     **/
    $("#dialog-tipo-validacao").dialog({
        autoOpen: false,
        resizable: false,
        modal: true,
        width: 600,
        open : function(){
            TipoValidacao.montaListagem();
        },
        close : function(){
            TipoValidacao.buscarDados();
        },
        buttons: {
            'Ok': function() {
                //_gaq.push(['_trackPageview', '/dialog/excluir-linha-formulario/nao/']);
                $(this).dialog('close');
            }
        }
    });
    /* ----------------------- Fim Tipo Validação -------------------------- */


    /* -------------------------- Tabelas Campos ---------------------------
     *
     * Dialog para definis nos campos qual a tebela que devera ser guardada as
     * informações.
     **/
    $("#dialog-tabelas-campos").dialog({
        width : 500,
        autoOpen: false,
        resizable: false,
        modal: true,
        open : function(){
            var cont = 0;

            var tabelaCampo = Grid.TabelaCampo();
            var schema = '';
            var tabela = '';
            var campo = '';
            
            if ($.trim(tabelaCampo) != ''){
                
                tabelaCampo = tabelaCampo.split('.');
                
                if(tabelaCampo.length == 3){
                    schema = tabelaCampo[0];
                    tabela = tabelaCampo[1];
                    campo = tabelaCampo[2];
                }else{
                    schema = '';
                    tabela = tabelaCampo[0];
                    campo = tabelaCampo[1];
                }
            }

            $('#tabelas_banco').find('option').remove();
            $('#campos_tabela').find('option').remove();

            var schemas = Projeto.DadosProjeto.tabelas;
            for (var s in schemas){
                var tabelas = schemas[s];

                for (var t in tabelas){
                    var campos = tabelas[t];

                    var schemaTabela = (($.trim(s) == '') ? t : s + '.' + t);
                    var optionTabela = $("<option></option>").attr("value", schemaTabela).text(schemaTabela);

                    if ((schema == s && tabela == t) || ($.trim(tabela) == '' && cont == 0)){
                        optionTabela.attr('selected','selected');

                        for (var c in campos){

                            var optionCampo = $("<option></option>").attr("value", c).text(c);
                            if ((campo == c) || ($.trim(tabela) == '' && cont == 0)){
                                optionCampo.attr('selected','selected');
                            }

                            $('#campos_tabela').append(optionCampo);
                            cont++;
                        }
                    }
                                        
                    $('#tabelas_banco').append(optionTabela);

                }
            }

        },
        buttons: {
            'Cancelar': function() {
                $(this).dialog('close');
            },
            'OK': function() {

                var schemaTabela = $('#tabelas_banco').val();
                var campoTabela = $('#campos_tabela').val();

                if($.trim(schemaTabela) == '' || $.trim(campoTabela) == ''){

                    Dialog.OpenMsgNotificacao(MsgErro.SelecioneTabelaCampo , TituloDialog.SelecioneTabelaCampo);
                    return false;
                }

                var retorno = schemaTabela + '.' + campoTabela;

                Grid.TabelaCampo(retorno);
                Grid.ExibePropriedades();

                $(this).dialog('close');
            }
        }
    });


    $('#tabelas_banco').change(function(){

        var valor = $(this).val();

        var schemas = Projeto.DadosProjeto.tabelas;
        for (var s in schemas){
            var tabelas = schemas[s];

            for (var t in tabelas){
                var campos = tabelas[t];
                    
                var schemaTabela = (($.trim(s) == '') ? t : s + '.' + t);
                if (valor == schemaTabela){
                    $('#campos_tabela').find('option').remove();

                    for (var c in campos){
                        
                        var option = $("<option></option>").attr("value", c).text(c);
                        $('#campos_tabela').append(option);
                    }
                }
            }
        }
    });
    /* ------------------------ Fim Tabelas Campos ------------------------- */
    
    
    /* ----------------------- Options campo Select ------------------------
     *
     * Dialog para definis nos campos qual a tebela que devera ser guardada as
     * informações.
     **/
    $("#dialog-options-select").dialog({
        autoOpen: false,
        resizable: false,
        modal: true,
        width: 520,
        height: 500,
        open : function(){
            
            $('#listagem-options-select tr.options').remove();
            var dados = Grid.GetDataCampoSelect();
            
            for (var i in dados){
                AddOptionListagem(dados[i].text, dados[i].value);
            }
            
            var tabelaOptions = Grid.GetTabelaOptionSelect();
            var schema = '';
            var tabela = '';
            var optionValue = '';
            var optionText = '';
            
            if ($.trim(tabelaOptions) != ''){
                $('#options-campos-base-dados').attr('checked', 'checked');
                HabilitaOptionsBaseDados();
                
                tabelaOptions = tabelaOptions.split('.');
                if (tabelaOptions.length == 2){
                    schema = tabelaOptions[0];
                    tabela = tabelaOptions[1];
                }else{
                    schema = '';
                    tabela = tabelaOptions[0];
                }
                
                optionValue = Grid.GetOptionValueSelect();
                optionText = Grid.GetOptionTextSelect();
            }else{
                $('#options-campos-base-dados').attr('checked', '');
                HabilitaOptionsCadastro();
            }

            $('#options-tabelas-banco').find('option').remove();
            $('#options-campos-tabela-value').find('option').remove();
            $('#options-campos-tabela-text').find('option').remove();
            
            var schemas = Projeto.DadosProjeto.tabelas;
            var cont = 0;
            for (var s in schemas){
                var tabelas = schemas[s];

                for (var t in tabelas){
                    var campos = tabelas[t];

                    var schemaTabela = (($.trim(s) == '') ? t : s + '.' + t);
                    var optionTabela = $("<option></option>").attr("value",schemaTabela).text(schemaTabela);

                    if (schema == s && tabela == t) {
                        optionTabela.attr('selected','selected');

                        for (var c in campos){

                            var optionCampoText = $("<option></option>").attr("value", c).text(c);
                            var optionCampoValue = $("<option></option>").attr("value", c).text(c);
                            
                            if (optionValue == c){
                                optionCampoValue.attr('selected','selected');
                            }
                            
                            if (optionText == c){
                                optionCampoText.attr('selected','selected');
                            }

                            $('#options-campos-tabela-value').append(optionCampoValue);
                            $('#options-campos-tabela-text').append(optionCampoText);
                            cont++;
                        }
                    }
                                        
                    $('#options-tabelas-banco').append(optionTabela);

                }
            }
            
        },
        buttons: {
            'Cancelar': function() {
                $(this).dialog('close');
            },
            'Ok': function() {
                
                if ($('#options-campos-base-dados').is(':checked')){
                    
                    var campoValue = $('#options-campos-tabela-value').val();
                    var campoText = $('#options-campos-tabela-text').val();
                    var schemaTabela = $('#options-tabelas-banco').val();
                    
                    if ($.trim(schemaTabela) == '' || $.trim(campoValue) == '' || $.trim(campoText) == ''){
                        Dialog.OpenMsgNotificacao(MsgErro.PreencherOptionsTabela, TituloDialog.SelecioneCamposCorretamente);
                        return false;    
                    }
                    
                    Grid.SetTabelaOptionSelect(schemaTabela);
                    Grid.SetOptionValueSelect(campoValue);
                    Grid.SetOptionTextSelect(campoText);
                                
                    var data = [];
                    Grid.SetDataCampoSelect(data);
                    
                    Gerador.BuscarOptionsCampoSelect(schemaTabela, campoValue, campoText);
                    
                }else{
                    
                    var data = [];
                    var i = 0;
                    $.each($('#listagem-options-select tr.options'), function(){
                        data[i++] = {
                            'value': $.trim($(this).find('td.value-option-select').text()),
                            'text' : $.trim($(this).find('td.text-option-select').text())
                        };
                    });
                
                    if (i == 0){
                    
                        Dialog.OpenMsgNotificacao(MsgErro.CadastrarOptionsTabela, TituloDialog.CadastrarOptionsTabela);
                        return false;
                    }
                    Grid.SetDataCampoSelect(data);
                    
                    Grid.SetTabelaOptionSelect('');
                    Grid.SetOptionValueSelect('');
                    Grid.SetOptionTextSelect('');
                }
                
                
                Grid.ExibePropriedades();
                $(this).dialog('close');
            }
        }
    });
    
    function AddOptionListagem(text, value){
        var html = '';
        html += '<tr class="options">';
        html += '<td class="width120 Center text-option-select">' + text + '</td>';
        html += '<td class="width120 Center value-option-select">' + value + '</td>';
        html += '<td class="width50 Center">';
        html += '<input type="button" class="botao-editar" />';
        html += '<input type="button" class="botao-excluir" />';
        html += '</td>';
        html += '</tr>';
        
        $('#listagem-options-select').append(html);
    }
    
    function HabilitaOptionsBaseDados(){
        $('#options-campos-tabela-value').attr('disabled','');
        $('#options-campos-tabela-text').attr('disabled','');
        $('#options-tabelas-banco').attr('disabled','');
            
        $('#text-option-select').attr('disabled','disabled');
        $('#value-option-select').attr('disabled','disabled');
        $('#salvar-option-select').attr('disabled','disabled');
    }
    
    function HabilitaOptionsCadastro(){
        $('#options-campos-tabela-value').attr('disabled','disabled');
        $('#options-campos-tabela-text').attr('disabled','disabled');
        $('#options-tabelas-banco').attr('disabled','disabled');
            
        $('#text-option-select').attr('disabled','');
        $('#value-option-select').attr('disabled','');
        $('#salvar-option-select').attr('disabled','');
    }
    
    $('#options-tabelas-banco').change(function(){

        var valor = $(this).val();

        var schemas = Projeto.DadosProjeto.tabelas;
        for (var s in schemas){
            var tabelas = schemas[s];

            for (var t in tabelas){
                var campos = tabelas[t];

                var schemaTabela = (($.trim(s) == '') ? t : s + '.' + t);
                if (valor == schemaTabela){
                    $('#options-campos-tabela-value').find('option').remove();
                    $('#options-campos-tabela-text').find('option').remove();

                    for (var c in campos){
                        
                        var text = $("<option></option>").attr("value", c).text(c);
                        var value = $("<option></option>").attr("value", c).text(c);
                        $('#options-campos-tabela-value').append(value);
                        $('#options-campos-tabela-text').append(text);
                    }
                }
            }
        }
    });
    
    $('#options-campos-base-dados').change(function(){
        if ($(this).is(':checked')){
            HabilitaOptionsBaseDados();
        }else{
            HabilitaOptionsCadastro();
        }
    });
    
    $('#salvar-option-select').click(function(){
        
        var text = $('#text-option-select').val();
        var value = $('#value-option-select').val();

        AddOptionListagem(text, value);
        
        $('#text-option-select').val('');
        $('#value-option-select').val('');
        
    });
    
    $('#listagem-options-select tr .botao-editar').live('click', function(){
        
        $('#text-option-select').val($.trim($(this).parent().siblings('td.text-option-select').text()));
        $('#value-option-select').val($.trim($(this).parent().siblings('td.value-option-select').text()));
        $(this).parents('tr.options:first').remove();
    });
    
    $('#listagem-options-select tr .botao-excluir').live('click', function(){
        $(this).parents('tr.options:first').remove();
    });
    /* -------------------- Fim Options campo Select ----------------------- */
 
 
    /* ------------------------- Usuário Sistema ---------------------------
     *
     * Dialog para confirmação de exclusão de um determinado registro.
     **/
    
    var DialogUsuario = {
        usuario : $('#usuario'),
        senha : $('#senha')
    }
    
    $("#dialog-usuario-sistema").dialog({
        autoOpen: false,
        closeOnEscape: false,
        resizable: false,
        modal: true,
        width: 500,
        buttons: {
            'Cancelar': function() {
                $(this).dialog('close');
            },
            'Salvar': function() {
                
                if ($.trim(DialogUsuario.usuario.val()) == '' || $.trim(DialogUsuario.senha.val()) == ''){
                    Dialog.OpenMsgNotificacao(MsgErro.PreencherUsuarioSenha, TituloDialog.EditarUsuarioSenha);
                }else{
                    
                    Dialog.OpenAguardando(Msg.Aguardando, 100);
                    
                    $.ajax({
                        type: 'POST',
                        data: {
                            usuario : DialogUsuario.usuario.val(),
                            senha : DialogUsuario.senha.val()
                        },
                        url: BASE_URL + "/autenticacao/editar-usuario/",
                        dataType: 'json',
                        cache: false,
                        success: function(json){
                            Dialog.CloseAguardando();

                            if (json.retorno == 'sucesso'){
                                $("#dialog-usuario-sistema").dialog('close');
                            } else if (json.retorno == 'erro'){
                                Dialog.OpenMsgNotificacao(MsgErro.EditarUsuarioSenha, TituloDialog.EditarUsuarioSenha);
                            }
                        
                        },
                        error : function(){
                            Dialog.CloseAguardando();
                            Dialog.OpenMsgNotificacao(MsgErro.EditarUsuarioSenha, TituloDialog.EditarUsuarioSenha);
                        }
                    });
                }
                
            }
        }
    });
/* ------------------------- Fim Usuário Sistema --------------------------- */
 

});


var Dialog ={

    OpenMsgNotificacao : function(msg, titulo, largura, altura){

        $("#dialog-notificacao p span.msg").html(msg);

        $("#dialog-notificacao").dialog({
            width: $.trim(largura) == '' ? 500 : largura,
            height: $.trim(altura) == '' ? 'auto' : altura,
            title: $.trim(titulo) == '' ? '' : titulo
        });
        
        $("#dialog-notificacao").dialog('open');
    },
    
    OpenAguardando : function(titulo, largura, altura){
        
        $('#dialog-aguardando').dialog({
            title: $.trim(titulo) == '' ? 'Aguardando...' : titulo,
            width: $.trim(largura) == '' ? 100 : largura,
            height: $.trim(altura) == '' ? 100 : altura
        });
        
        $('#dialog-aguardando').dialog('open');
    },
    
    CloseAguardando : function(){
        $('#dialog-aguardando').dialog('close');
    }

}
