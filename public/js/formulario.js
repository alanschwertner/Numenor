$(function(){

    //    $('.input-formulario input:text').live('click', function(event){
    //        var li = $(this).parents("li:first");
    //        Formulario.MarcaSelecionado(li);
    //        event.stopPropagation();
    //        $(this).focus();
    //    });
    //    $('.input-formulario select').live('click', function(event){
    //        var li = $(this).parents("li:first");
    //        Formulario.MarcaSelecionado(li);
    //        $(this).focus();
    //        event.stopPropagation();
    //    });
    //
    //    $('.input-formulario textarea').live('click', function(event){
    //        var li = $(this).parents("li:first");
    //        Formulario.MarcaSelecionado(li);
    //        event.stopPropagation();
    //        $(this).focus();
    //    });
    
   
    });


Formulario = {

    MenuContexto : [{
        'Subir': {
            onclick : function(menuItem,menu) {
                Formulario.MoverParaCimaSelecionado();
                return true;
            },
            //disabled: true,
            icon:'/images/icon/subir.png'
        },
        'Descer':{
            onclick : function(menuItem,menu) {
                Formulario.MoverParaBaixoSelecionado();
                return true;
            },
            //disabled: true,
            icon:'/images/icon/descer.png'
        }
    },
    $.contextMenu.separator, {
        'Duplicar':{
            onclick : function(menuItem,menu) {
                Formulario.DuplicarSelecionado();
                return true;
            },
            icon:'/images/icon/duplicar.png'
        }
    },
    $.contextMenu.separator, {
        'Excluir':{
            onclick : function(menuItem,menu) {
                $('#dialog-excluir-linha-formulario').dialog('open');
                return true;
            },
            icon:'/images/icon/excluir.png'
        }
    }],

    AddObjeto : function(obj){
        obj.bind({
            click : function(){
                if ($(this).is('.ui-selected')){
                    Formulario.DesmarcaSelecionado();
                    Grid.ExibeMenuPropriedadesFormulario();
                }else{
                    Formulario.MarcaSelecionado($(this));
                }
            }
        });
        
        obj.contextMenu(Formulario.MenuContexto,{
            theme:'vista'
        });
        
        obj.rightClick(function(event) {
            //event.stopPropagation();
            Formulario.MarcaSelecionado($(this));
        });

        $('.itens-formulario:visible').append(obj);
        
        initPaneScrollbar('center', outerLayout.panes.center);

    },


    MarcaSelecionado : function(Elemento){
        Formulario.DesmarcaSelecionado();
        Elemento.addClass('ui-selected');
        Grid.ExibePropriedades();
    },

    DesmarcaSelecionado : function (){
        $('.itens-formulario li.ui-selected').each(function(){
            $(this).find('input, select, textarea').blur();
            $(this).removeClass('ui-selected');
        });
        Grid.ExibePropriedades();
    },

    MoverParaCimaSelecionado : function(){
        var mover = $('.itens-formulario:visible li.ui-selected');
        mover.insertBefore(mover.prev());
    },

    MoverParaBaixoSelecionado : function(){
        var mover = $('.itens-formulario:visible li.ui-selected');
        mover.insertAfter(mover.next());
    },

    DuplicarSelecionado : function(){
        var clone = $('.itens-formulario:visible li.ui-selected').clone(false);
        
        clone.removeClass('ui-selected');
        
        Formulario.AddObjeto(clone);
    },
    
    
    NovoTitulo : function(){
        var titulo = $('<li class="titulo">' +
            '<div class="conteudo-formulario">' +
            '<h2 class="titulo-formulario Center">' +
            'T&iacute;tulo do Formul&aacute;rio' +
            '</h2>' +
            '<h5 class="sub-titulo-formulario Center">' +
            'Sub T&iacute;tulo do Formul&aacute;rio' +
            '</h5>' +
            '</div>' +
            '</li>');
        
        Formulario.AddObjeto(titulo);
    },
    
    NovoInputText : function(){
        
        var inputText = $('<li class="campo-input-text">' +
            '<div class="conteudo-formulario">' +
            '<div style="width: 150px;" class="label-campo-formulario Left">' +
            'T&iacute;tulo Input Text:' +
            '</div>' +
            '<div class="input-formulario">' +
            '<input type="text" valida="texto" tabela_campo="" style="width: 200px;" class="ui-style" />' +
            '</div>' +
            '</div>' +
            '</li>');

        Formulario.AddObjeto(inputText);
    },

    NovoInputRadio : function(){

    },

    NovoInputCheckbox : function(){

    },

    NovoInputPassword : function(){

        var inputPassword = $('<li class="campo-input-password">' +
            '<div class="conteudo-formulario">' +
            '<div style="width: 150px;" class="label-campo-formulario Left">' +
            'T&iacute;tulo Input Password:' +
            '</div>' +
            '<div class="input-formulario">' +
            '<input type="password" tabela_campo="" criptografia="md5" style="width: 200px;" class="ui-style" />' +
            '</div>' +
            '</div>' +
            '</li>');
        
        Formulario.AddObjeto(inputPassword);

    },

    NovoInputHidden : function(){

    },

    NovoInputSubmit : function(){

        var inputButton = $('<li class="campo-input-submit">' +
            '<div class="conteudo-formulario">' +
            '<div class="input-formulario width100p Center">' +
            '<input type="submit" style="width: 100px;" value="Salvar" class="ui-button ui-style" />' +
            '</div>' +
            '</div>' +
            '</li>');
        
        Formulario.AddObjeto(inputButton);
    },

    NovoSelect : function(){

        var select = $('<li class="campo-select">' +
            '<div class="conteudo-formulario">' +
            '<div style="width: 150px;" class="label-campo-formulario Left">' +
            'T&iacute;tulo Select:' +
            '</div>' +
            '<div class="input-formulario">' +
            '<select style="width: 209px;" tabela_campo="" tabela_options="" campo_option_value="" campo_option_text="" class="ui-style">' +
            '</select>' +
            '</div>' +
            '</div>' +
            '</li>');

        Formulario.AddObjeto(select);
    },

    NovoTextarea : function(){

        var textarea = $('<li class="campo-textarea">' +
            '<div class="conteudo-formulario">' +
            '<div style="width: 150px;" class="label-campo-formulario Left">' +
            'T&iacute;tulo Textarea:' +
            '</div>' +
            '<div class="input-formulario">' +
            '<textarea style="width: 200px; height: 50px;" tabela_campo="" class="ui-style" ></textarea>' +
            '</div>' +
            '</div>' +
            '</li>');

        Formulario.AddObjeto(textarea);
    }

}

