$(function(){
   
    });

var Grid = {

    Tab             : '#tab-formularios ul li.ui-tabs-selected',
    Form            : '#tab-formularios div:visible div ul.itens-formulario',
    Pai             : 'li.ui-selected div ',
    PaiLabel        : 'li.ui-selected div div.label-campo-formulario',
    PaiInput        : 'li.ui-selected div div.input-formulario',
    PropTitulo      : '#propri-titulo-formulario-',
    Requerido       : '<span class="campo-requerido">*</span>',

    ExibePropriedades : function(){

        if ($('.itens-formulario:visible li').is('.ui-selected')){

            var Classes = $('.itens-formulario:visible li.ui-selected').attr('class').split(' ');

            $.each(Classes, function(key, value){

                switch(value){
                    case 'campo-input-text':
                        Grid.ExibeMenuPropriedadesInputText();
                        break;
                    case 'campo-input-password':
                        Grid.ExibeMenuPropriedadesInputPassword()
                        break;
                    case 'campo-input-submit':
                        Grid.ExibeMenuPropriedadesInputSubmit();
                        break;
                    case 'campo-select':
                        Grid.ExibeMenuPropriedadesSelect();
                        break;
                    case 'campo-textarea':
                        Grid.ExibeMenuPropriedadesTextarea();
                        break;
                    case 'titulo':
                        Grid.ExibeMenuPropriedadesTituloFormulario();
                        break;
                }
            });
        } else {
            Grid.ExibeMenuPropriedadesFormulario();
        }

    },
   

    ExibeMenuPropriedadesFormulario : function(){
        $('#propriedades-form').PropertyGrid({
            grupos : [{
                nome : 'Propriedades do Formuário',
                itens: [{
                    id : 'titulo',
                    titulo : 'Nome Formulário:',
                    info : 'Com esta informação que será criado o nome do arquivo.',
                    getValue : Grid.GetNomeFormulario(),
                    setValue : Grid.SetNomeFormulario
                },{
                    id : 'controller',
                    titulo : 'Nome Controller:',
                    info : 'Nome do Controller que deverá de ser criado, caso não seja informado será assumido Index como padão. Caso informe o nome não é necessário informar o "Controller" de IndexController.',
                    getValue : Grid.FormularioNomeController(),
                    setValue : Grid.FormularioNomeController
                },{
                    id : 'action',
                    titulo : 'Nome Action:',
                    getValue : Grid.FormularioNomeAction(),
                    setValue : Grid.FormularioNomeAction
                }]
            }]
        });

    },

    ExibeMenuPropriedadesTituloFormulario : function(){
        $('#propriedades-form').PropertyGrid({
            grupos : [{
                nome : 'Propriedades Titulo do Formuário',
                itens: [{
                    id : 'titulo',
                    titulo : 'Titulo:',
                    info : 'Esta informação estará presente somente na view e não no formulário Zend_Form',
                    getValue : Grid.GetValorTitulo(),
                    setValue : Grid.SetValorTitulo
                },{
                    id : 'alinhamento-titulo',
                    titulo : 'Alinhamento:',
                    tipo : 'select',
                    getValue : Grid.GetValorAlinhamentoTitulo(),
                    setValue : Grid.SetValorAlinhamentoTitulo,
                    data : Grid.DataAlinhamento
                },{
                    id : 'subtitulo',
                    titulo : 'Sub Titulo:',
                    info : 'Esta informação estará presente somente na view e não no formulário Zend_Form',
                    getValue : Grid.GetValorSubTitulo(),
                    setValue : Grid.SetValorSubTitulo
                },{
                    id : 'alinhamento-sub-titulo',
                    titulo : 'Alinhamento:',
                    tipo : 'select',
                    getValue : Grid.GetValorAlinhamentoSubTitulo(),
                    setValue : Grid.SetValorAlinhamentoSubTitulo,
                    data : Grid.DataAlinhamento
                }]
            }]
        });

    },

    ExibeMenuPropriedadesInputText : function(obj){

        $('#propriedades-form').PropertyGrid({
            grupos : [{
                nome : Propriedade.PropriLabel,
                itens: [{
                    id : 'titulo',
                    titulo : 'Titulo:',
                    getValue : Grid.GetValorTituloCampo(),
                    setValue : Grid.SetValorTituloCampo
                },{
                    id : 'alinhamento-titulo',
                    titulo : 'Alinhamento:',
                    tipo : 'select',
                    getValue : Grid.GetValorAlinhamentoTituloCampo(),
                    setValue : Grid.SetValorAlinhamentoTituloCampo,
                    data : Grid.DataAlinhamento
                },{
                    id : 'largura-titulo',
                    titulo : 'Largura:',
                    tipo : 'spinner',
                    getValue : Grid.GetValorLarguraTituloCampo(),
                    setValue : Grid.SetValorLarguraTituloCampo
                }]
            },{
                nome : 'Propriedades do Campo',
                itens: [{
                    id : 'requerido',
                    titulo : 'Requerido:',
                    tipo : 'select',
                    getValue : Grid.GetValorRequeridoCampo(),
                    setValue : Grid.SetValorRequeridoCampo,
                    data : Grid.DataSimNao
                },{
                    id : 'tipo-validacao',
                    titulo : 'Tipo de Validação:',
                    tipo : 'select',
                    getValue : Grid.GetTipoValidacaoCampo(),
                    setValue : Grid.SetTipoValidacaoCampo,
                    data : Grid.TipoValidacao()
                },{
                    id : 'largura-campo',
                    titulo : 'Largura:',
                    info : 'Largura do campo em pixels.',
                    tipo : 'spinner',
                    getValue : Grid.GetValorLarguraCampo(),
                    setValue : Grid.SetValorLarguraCampo
                },{
                    id : 'valor-padrao',
                    titulo : 'Valor Padrão:',
                    info : 'Valor padrão serve para caso deseje iniciar o compo com algum valor.',
                    getValue : Grid.ValorCampo(),
                    setValue : Grid.ValorCampo
                },{
                    id : 'tabela-campo',
                    titulo : 'Campo da tabela:',
                    info : 'Este campo representa, em qual tabela e em qual campo deseja guardar as informações.',
                    tipo : 'button',
                    dialog : '#dialog-tabelas-campos',
                    getValue : Grid.TabelaCampo()
                }]
            }]
        });

    },

    ExibeMenuPropriedadesInputSubmit : function(){
        $('#propriedades-form').PropertyGrid({
            grupos : [{
                nome : 'Propriedades do Button Submit',
                itens: [{
                    id : 'titulo',
                    titulo : 'Titulo:',
                    getValue : Grid.ValorCampo(),
                    setValue : Grid.ValorCampo
                },{
                    id : 'alinhamento-botao',
                    titulo : 'Alinhamento:',
                    tipo : 'select',
                    getValue : Grid.GetValorAlinhamentoButton(),
                    setValue : Grid.SetValorAlinhamentoButton,
                    data : Grid.DataAlinhamento
                },{
                    id : 'largura-campo',
                    titulo : 'Largura:',
                    info : 'Largura do botão em pixels.',
                    tipo : 'spinner',
                    getValue : Grid.GetValorLarguraCampo(),
                    setValue : Grid.SetValorLarguraCampo
                }]
            }]
        });
    },

    ExibeMenuPropriedadesSelect : function(){
        $('#propriedades-form').PropertyGrid({
            grupos : [{
                nome : Propriedade.PropriLabel,
                itens: [{
                    id : 'titulo',
                    titulo : 'Titulo:',
                    getValue : Grid.GetValorTituloCampo(),
                    setValue : Grid.SetValorTituloCampo
                },{
                    id : 'alinhamento-titulo',
                    titulo : 'Alinhamento:',
                    tipo : 'select',
                    getValue : Grid.GetValorAlinhamentoTituloCampo(),
                    setValue : Grid.SetValorAlinhamentoTituloCampo,
                    data : Grid.DataAlinhamento
                },{
                    id : 'largura-titulo',
                    titulo : 'Largura:',
                    tipo : 'spinner',
                    getValue : Grid.GetValorLarguraTituloCampo(),
                    setValue : Grid.SetValorLarguraTituloCampo
                }]
            },{
                nome : 'Propriedades do Campo',
                itens: [{
                    id : 'requerido',
                    titulo : 'Requerido:',
                    tipo : 'select',
                    getValue : Grid.GetValorRequeridoCampo(),
                    setValue : Grid.SetValorRequeridoCampo,
                    data : Grid.DataSimNao
                },{
                    id : 'largura-campo',
                    titulo : 'Largura:',
                    info : 'Largura do campo em pixels.',
                    tipo : 'spinner',
                    getValue : Grid.GetValorLarguraCampo(),
                    setValue : Grid.SetValorLarguraCampo
                },{
                    id : 'valor-padrao',
                    titulo : 'Valor Padrão:',
                    tipo : 'select',
                    getValue : Grid.ValorCampo(),
                    setValue : Grid.ValorCampo,
                    data : Grid.GetDataCampoSelect()
                },{
                    id : 'tabela-campo',
                    titulo : 'Campo da tabela:',
                    tipo : 'button',
                    dialog : '#dialog-tabelas-campos',
                    getValue : Grid.TabelaCampo()
                },{
                    id : 'option-campo',
                    titulo : 'Options do campo:',
                    tipo : 'button',
                    dialog : '#dialog-options-select'
                }]
            }]
        });

    },

    ExibeMenuPropriedadesTextarea : function(){
        $('#propriedades-form').PropertyGrid({
            grupos : [{
                nome : Propriedade.PropriLabel,
                itens: [{
                    id : 'titulo',
                    titulo : 'Titulo:',
                    getValue : Grid.GetValorTituloCampo(),
                    setValue : Grid.SetValorTituloCampo
                },{
                    id : 'alinhamento-titulo',
                    titulo : 'Alinhamento:',
                    tipo : 'select',
                    getValue : Grid.GetValorAlinhamentoTituloCampo(),
                    setValue : Grid.SetValorAlinhamentoTituloCampo,
                    data : Grid.DataAlinhamento
                },{
                    id : 'largura-titulo',
                    titulo : 'Largura:',
                    tipo : 'spinner',
                    getValue : Grid.GetValorLarguraTituloCampo(),
                    setValue : Grid.SetValorLarguraTituloCampo
                }]
            },{
                nome : 'Propriedades do Campo',
                itens: [{
                    id : 'requerido',
                    titulo : 'Requerido:',
                    tipo : 'select',
                    getValue : Grid.GetValorRequeridoCampo(),
                    setValue : Grid.SetValorRequeridoCampo,
                    data : Grid.DataSimNao
                },{
                    id : 'largura-campo',
                    titulo : 'Largura:',
                    info : 'Largura do campo em pixels.',
                    tipo : 'spinner',
                    getValue : Grid.GetValorLarguraCampo(),
                    setValue : Grid.SetValorLarguraCampo
                },{
                    id : 'altura-campo',
                    titulo : 'Altura:',
                    info : 'Altura do campo em pixels.',
                    tipo : 'spinner',
                    getValue : Grid.GetValorAlturaCampo(),
                    setValue : Grid.SetValorAlturaCampo
                },{
                    id : 'valor-padrao',
                    titulo : 'Valor Padrão:',
                    getValue : Grid.ValorCampo(),
                    setValue : Grid.ValorCampo
                },{
                    id : 'tabela-campo',
                    titulo : 'Campo da tabela:',
                    tipo : 'button',
                    dialog : '#dialog-tabelas-campos',
                    getValue : Grid.TabelaCampo()
                }]
            }]
        });
    },

    ExibeMenuPropriedadesInputPassword : function(){
        $('#propriedades-form').PropertyGrid({
            grupos : [{
                nome : Propriedade.PropriLabel,
                itens: [{
                    id : 'titulo',
                    titulo : 'Titulo:',
                    getValue : Grid.GetValorTituloCampo(),
                    setValue : Grid.SetValorTituloCampo
                },{
                    id : 'alinhamento-titulo',
                    titulo : 'Alinhamento:',
                    tipo : 'select',
                    getValue : Grid.GetValorAlinhamentoTituloCampo(),
                    setValue : Grid.SetValorAlinhamentoTituloCampo,
                    data : Grid.DataAlinhamento
                },{
                    id : 'largura-titulo',
                    titulo : 'Largura:',
                    tipo : 'spinner',
                    getValue : Grid.GetValorLarguraTituloCampo(),
                    setValue : Grid.SetValorLarguraTituloCampo
                }]
            },{
                nome : 'Propriedades do Campo',
                itens: [{
                    id : 'requerido',
                    titulo : 'Requerido:',
                    tipo : 'select',
                    getValue : Grid.GetValorRequeridoCampo(),
                    setValue : Grid.SetValorRequeridoCampo,
                    data : Grid.DataSimNao
                },{
                    id : 'tipo-criptografia',
                    titulo : 'Tipo de Criptografia:',
                    tipo : 'select',
                    getValue : Grid.GetTipoCriptografiaCampo(),
                    setValue : Grid.SetTipoCriptografiaCampo,
                    data : Grid.DataTipoCriptografia
                },{
                    id : 'largura-campo',
                    titulo : 'Largura:',
                    tipo : 'spinner',
                    getValue : Grid.GetValorLarguraCampo(),
                    setValue : Grid.SetValorLarguraCampo
                }]
            }]
        });
    },

    /* --------------- Inicio Set e Get --------------- */
    
    
    /* ------- alinhamento --------- */
    GetValorAlinhamento : function(obj){
        if (obj.is('.Left')){
            return 'Left';
        } else if (obj.is('.Right')){
            return 'Right';
        }else{
            return 'Center';
        }
    },

    SetValorAlinhamento : function(obj, classe){
        obj.removeClass('Left Center Right');
        obj.addClass(classe);
    },

    GetValorAlinhamentoTituloCampo : function(){
        return Grid.GetValorAlinhamento($(Grid.PaiLabel));
    },
    
    SetValorAlinhamentoTituloCampo : function(Classe){
        Grid.SetValorAlinhamento($(Grid.PaiLabel), Classe)
    },
    
    GetValorAlinhamentoTitulo : function(){
        return Grid.GetValorAlinhamento($(Grid.Pai + 'h2.titulo-formulario'));
    },

    SetValorAlinhamentoTitulo : function(classe){
        Grid.SetValorAlinhamento($(Grid.Pai + 'h2.titulo-formulario'), classe)
    },

    GetValorAlinhamentoSubTitulo : function(){
        return Grid.GetValorAlinhamento($(Grid.Pai + 'h5.sub-titulo-formulario'));
    },

    SetValorAlinhamentoSubTitulo : function(classe){
        Grid.SetValorAlinhamento($(Grid.Pai + 'h5.sub-titulo-formulario'), classe)
    },
    
    GetValorAlinhamentoButton : function(){
        return Grid.GetValorAlinhamento($(Grid.Pai));
    },

    SetValorAlinhamentoButton : function(classe){
        Grid.SetValorAlinhamento($(Grid.Pai), classe)
    },

    /* ------- alinhamento --------- */
    
    
    /* ------- largura --------- */
    GetValorLarguraTituloCampo : function(){
        return $(Grid.PaiLabel).width();
    },

    SetValorLarguraTituloCampo : function(valor){
        $(Grid.PaiLabel).width(parseInt(valor));
    },
    
    GetValorLarguraCampo : function(){

        if($(Grid.PaiInput + ' input:submit').size()){
            return parseInt($(Grid.PaiInput + ' input:submit').innerWidth() + 2);
        } else if($(Grid.PaiInput + ' input').size()){
            return parseInt($(Grid.PaiInput + ' input').width());
        } else if($(Grid.PaiInput + ' select').size()){
            return parseInt($(Grid.PaiInput + ' select').width());
        } else if($(Grid.PaiInput + ' textarea').size()){
            return parseInt($(Grid.PaiInput + ' textarea').width());
        }

    },

    SetValorLarguraCampo : function(valor){
        if($(Grid.PaiInput + ' input').size()){
            $(Grid.PaiInput + ' input').width(parseInt(valor));
        } else if($(Grid.PaiInput + ' select').size()){
            $(Grid.PaiInput + ' select').width(parseInt(valor) + 9);
        } else if($(Grid.PaiInput + ' textarea').size()){
            $(Grid.PaiInput + ' textarea').width(parseInt(valor));
        }
    },
    /* ------- largura --------- */
    
    
    /* ------- Altura --------- */
    GetValorAlturaCampo : function(){
        return parseInt($(Grid.PaiInput + ' textarea').height());
    },
    
    SetValorAlturaCampo : function(valor){
        $(Grid.PaiInput + ' textarea').height(parseInt(valor));
    },

    /* ------- Altura --------- */


    /* ------- Select --------- */
    GetTabelaOptionSelect : function(){
        return $(Grid.PaiInput + ' select').attr('tabela_options');
    },
    
    SetTabelaOptionSelect : function(nome){
        return $(Grid.PaiInput + ' select').attr('tabela_options',nome);
    },
    
    GetOptionValueSelect : function(){
        return $(Grid.PaiInput + ' select').attr('campo_option_value');
    },
    
    SetOptionValueSelect : function(nome){
        return $(Grid.PaiInput + ' select').attr('campo_option_value',nome);
    },
    
    GetOptionTextSelect : function(){
        return $(Grid.PaiInput + ' select').attr('campo_option_text');
    },
    
    SetOptionTextSelect : function(nome){
        return $(Grid.PaiInput + ' select').attr('campo_option_text',nome);
    },
    
    GetDataCampoSelect : function(){
        var data = [];
        var i = 0;
        $.each($(Grid.PaiInput + ' select option'), function(){
            data[i] = {
                'value': $(this).val(),
                'text' : $(this).text()
            };
            i++;
        });

        return data;
    },
    
    SetDataCampoSelect : function(dados){
        $(Grid.PaiInput + ' select option').remove();
        
        for (var i in dados){
            var option = $("<option></option>").attr("value",dados[i].value).text(dados[i].text);
            $(Grid.PaiInput + ' select').append(option);
        }
    },
    /* ------- Select --------- */


    /* ------- Titulo Formulario --------- */
    GetValorTitulo : function(){
        return $(Grid.Pai + 'h2.titulo-formulario').html();
    },
    
    SetValorTitulo : function(valor){
        $(Grid.Pai + 'h2.titulo-formulario').html(valor);
    },
   
    GetValorSubTitulo : function(){
        return $(Grid.Pai + 'h5.sub-titulo-formulario').html();
    },

    SetValorSubTitulo : function(valor){
        $(Grid.Pai + 'h5.sub-titulo-formulario').html(valor);
    },
   
    /* ------- Titulo Formulario --------- */
  
    /* ------- Formulario --------- */
    GetNomeFormulario : function(){
        return $(Grid.Tab + ' a').text();
    },
    SetNomeFormulario : function(nome){
        $(Grid.Tab + ' a').text(nome);
    },
    
    GetLarguraFormulario : function(){
        return parseInt($(Grid.Form).width());
    },

    SetLarguraFormulario : function(valor){
        $(Grid.Form).width(parseInt(valor));
    },
    /* ------- Formulario --------- */

    
    GetValorTituloCampo : function(){
        if($(Grid.PaiLabel + ' span').is('.campo-requerido')){
            
            var titulo = $(Grid.PaiLabel).html();
            return titulo.replace(Grid.Requerido, '');

        } else {
            return $(Grid.PaiLabel).html();
        }
    },

    SetValorTituloCampo : function(valor){
        if($(Grid.PaiLabel + ' span').is('.campo-requerido')){
            $(Grid.PaiLabel).html(valor + Grid.Requerido);
        }else {
            $(Grid.PaiLabel).html(valor);
        }

    },
    
    GetValorRequeridoCampo : function(){
        if($(Grid.PaiLabel + ' span').is('.campo-requerido')){
            return 'sim';
        } else {
            return 'nao';
        }
    },
    
    SetValorRequeridoCampo : function(Requerido){
        if (Requerido == 'sim'){
            if(!$(Grid.PaiLabel + ' span').is('.campo-requerido')){
                $(Grid.PaiLabel).append(Grid.Requerido);
            }
        }else{
            if($(Grid.PaiLabel + ' span').is('span.campo-requerido')){
                $(Grid.PaiLabel + ' span.campo-requerido').remove();
            }
        }
    },

    GetTipoValidacaoCampo : function(){
        return $(Grid.PaiInput + ' input').attr('valida');
    },

    SetTipoValidacaoCampo : function(valor){
        $(Grid.PaiInput + ' input').attr('valida', valor);
    },

    GetTipoCriptografiaCampo : function(){
        return $(Grid.PaiInput + ' input').attr('criptografia');
    },

    SetTipoCriptografiaCampo : function(valor){
        $(Grid.PaiInput + ' input').attr('criptografia', valor);
    },

    ValorCampo : function(valor){
        if (arguments.length) {
            $(Grid.PaiInput).find('input, select, textarea').val(valor);
        } else {
            return $(Grid.PaiInput).find('input, select, textarea').val();
        }
    },
    
    TabelaCampo : function(valor){
        if (arguments.length) {
            $(Grid.PaiInput).find('input, select, textarea').attr('tabela_campo', valor);
        } else {
            return $(Grid.PaiInput).find('input, select, textarea').attr('tabela_campo');
        }
    },
      
    /* --------------- Fim Set e Get --------------- */

    /* ----------------- Dados Formulario ------------ */
    FormularioNomeController : function(nome){
        if (arguments.length) {
            $(Grid.Form + ' .controller').val(nome);
        } else {
            return $(Grid.Form + ' .controller').val();
        }
    },

    FormularioNomeAction : function(nome){
        if (arguments.length) {
            $(Grid.Form + ' .action').val(nome);
        } else {
            return $(Grid.Form + ' .action').val();
        }
    },
    
    /* --------------- Fim Dados Formulario ---------- */


    TipoValidacao : function(){
        var data = []
        
        data[0] = {
            'value': '',
            'text' : 'Selecione'
        };
        var i = 1;
        for (var j in TipoValidacao.dados){
            data[i] = {
                'value': TipoValidacao.dados[j].nome_logico,
                'text' : TipoValidacao.dados[j].nome
            };
            i++;
        }
        
        return data;
    },


    /* --------------- Inicio Data Selects --------------- */
    DataAlinhamento : [{
        value : 'Center',
        text : 'Centro'
    },{
        value : 'Right',
        text : 'Direita'
    },{
        value : 'Left',
        text : 'Esquerda'
    }],

    DataSimNao : [{
        value : 'sim',
        text : 'Sim'
    },{
        value : 'nao',
        text : 'Não'
    }],

    DataTipoCriptografia : [{
        value : 'md5',
        text : 'MD5'
    },{
        value : 'sha1',
        text : 'SHA1'
    }]
    

/* --------------- Fim Data Selects --------------- */

};
