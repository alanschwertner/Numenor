$(function(){
    
    $('#acao-gerador-db').click(function() {
        _gaq.push(['_trackPageview', '/gerador/visualizar-tabelas/']);
        $("#dialog-tabelas-db").dialog('open');
    });
    
    $('#acao-gerador-gerar').click(function() {

        _gaq.push(['_trackPageview', '/gerador/gerar/']);
        $("#dialog-gerar-codigo").dialog('open');
        Gerador.GerarCodigoProjeto();

        ///$('#tabs-php').html($.toJSON(Gerador.GetDadosFormulario()));

    });

    
});





