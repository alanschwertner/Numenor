
$(function(){

    $(".myMenu").buildMenu({
        additionalData:"pippo=1",
        menuWidth:200,
        openOnRight:false,
        menuSelector: ".menuContainer",
        iconPath:"/images/icon/menu_cabecalho/",
        hasImages:true,
        fadeInTime:100,
        fadeOutTime:300,
        adjustLeft:1,
        minZindex:"auto",
        adjustTop:10,
        opacity:.95,
        shadow:true,
        openOnClick:true,
        closeOnMouseOut:true,
        closeAfter:50
    });

    

    /**
     * Retirar esta linha assim como estiver pronto
     */
        
//    var dados = {
//        nome_projeto : $('<input type="text" />').val('Projeto'),
//        nome_formulario : $('<input type="text" />').val('Form'),
//        nome_controller : $('<input type="text" />').val('controller'),
//        nome_action : $('<input type="text" />').val('action'),
//        banco : $('<input type="text" />').val('pdo_mysql'),
//        host : $('<input type="text" />').val('localhost'),
//        usuario : $('<input type="text" />').val('root'),
//        senha : $('<input type="text" />').val(''),
//        dbnome : $('<input type="text" />').val('base_modelo'),
//        conexao_banco : $('<input type="chekbox" />').val('true'),
//        resultado_conexao : $('<input type="text" />').val('true')
//    }
//    Projeto.NovoProjeto(dados);
//    $("#dialog-buscar-dados-tabelas").dialog('open');

});

var MenuCabecalho = {
    
    NovoProjeto : function(){
        $("#dialog-novo-projeto").dialog('open');
    },
    
    NovoFormulario : function(){
        $("#dialog-novo-formulario").dialog('open');
    },
    
    Logoff : function(){
        window.location = '/autenticacao/logout/';
    },
    
    UsuarioSenha : function(){
        $("#dialog-usuario-sistema").dialog('open');
    },
    
    TipoValidacao : function(){
        $("#dialog-tipo-validacao").dialog('open');
    }
    
}






