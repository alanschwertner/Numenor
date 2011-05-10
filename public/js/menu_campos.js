$(function(){
    
    
    // menu ******************************

    $("#menu-lateral").accordion({
        header: "h3",
        autoHeight: false,
        collapsible: true
    }).sortable({
        axis: "y",
        handle: "h3"
    });


    // menu itens //////
    $('.itens-form li').addClass('ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only');

    $(".itens-form li").disableSelection();
    $(".itens-form li").hover(function(){
        $(this).addClass("ui-state-hover");
    },function(){
        $(this).removeClass("ui-state-hover");
    });

    // menu itens //////

    // cria uma nova linha no formulario para atribuições de titulos
    $('#itens-form-titulo').click(function(){
        Formulario.NovoTitulo();
    });// fim titulo

    // cria uma nova linha no formulario para atribuições de campos text
    $('#itens-form-campo-text').click(function(){
        Formulario.NovoInputText();
    }); // fim input-text

    // cria uma nova linha no formulario para atribuições de campos selct
    $('#itens-form-campo-select').click(function(){
        Formulario.NovoSelect();
    }); // fim select

    // cria uma nova linha no formulario para atribuições de campos text
    $('#itens-form-campo-textarea').click(function(){
        Formulario.NovoTextarea();
    }); // fim input-text

    // cria uma nova linha no formulario para atribuições de campos input submit
    $('#itens-form-campo-input-submit').click(function(){
        Formulario.NovoInputSubmit();
    }); // fim input-submit

    // cria uma nova linha no formulario para atribuições de campos input password
    $('#itens-form-campo-input-password').click(function(){
        Formulario.NovoInputPassword();
    }); // fim input-button

});


