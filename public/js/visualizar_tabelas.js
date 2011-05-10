$(function(){

    $(".dialog-tabelas-banco .tabela .nome .ui-icon").live('click', function() {
        $(this).toggleClass("ui-icon-minusthick").toggleClass("ui-icon-plusthick");
        $(this).parents(".tabela:first").find(".campos").toggle();
    });

});
