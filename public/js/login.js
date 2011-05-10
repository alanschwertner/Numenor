$(function(){

    $('#usuario').focus();

    $("#dialog-notificacao").dialog({
        autoOpen: false,
        resizable: false,
        modal: true,
        width: 400,
        buttons: {
            'OK': function() {
                $(this).dialog('close');
            }
        }
    });

    $('#form_login').submit(function(){

        if($.trim($('#usuario').val()) == '' || $.trim($('#senha').val()) == ''){

            $('.msg').html('Preencha corretamente os campos Usuário e Senha');
            $("#dialog-notificacao").dialog('open');
            return false;
        }
        
       // _gaq.push(['_trackPageview', '/autenticacao/logar/']);
        
        $('#carregando').html('<img src="/images/carregando.gif" alt="Aguardando" />')

        $.ajax({
            type: 'POST',
            data: {
                usuario: $('#usuario').val(),
                senha: $('#senha').val()
            },
            url: "/autenticacao/logar/",
            dataType: 'json',
            cache: false,
            success: function(json){
                
                if (json.retorno == 'sucesso'){
                    window.location = '/index/';
                } else {
                    $(".login").effect('shake');
                }
                $('#carregando').html('')
            },
            error : function(){

                $('.msg').html('Ocorreu algum problema ao tentar efetuar o login, caso o erro persista favor entrar em contato com o administrador do sistema.');
                $("#dialog-notificacao").dialog('open');
                $('#carregando').html('')
            }
        });

        return false;

    });

});
