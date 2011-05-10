$(function(){

    $('#tab-formularios span.ui-icon-close').live('click', function() {
        var index = $("li", Projeto.Tab).index($(this).parent());
        Formulario.DesmarcaSelecionado();
        Grid.ExibeMenuPropriedadesFormulario();
        Projeto.Tab.tabs("remove", index);
    });
    
});

var Projeto = {

    Tab : '',
    TabCount : 1,
    DadosProjeto : {},

    NovoProjeto : function(Dados){
        outerLayout.open('west');
        outerLayout.show('east');

        Projeto.DadosProjeto = {
            nome_projeto : Dados.nome_projeto.val(),
            conexao_banco : Dados.conexao_banco.is(':checked') ? true : false,
            conexao : {
                banco : Dados.banco.val(),
                host : Dados.host.val(),
                usuario : Dados.usuario.val(),
                senha : Dados.senha.val(),
                dbnome : Dados.dbnome.val()
            },
            resultado_conexao : Dados.resultado_conexao.val() == 'true' ? true : false,
            tabelas : {},
            formulario : [{
                id: Projeto.TabCount,
                nome: Dados.nome_formulario.val(),
                controlleer: Dados.nome_controller.val(),
                action: Dados.nome_action.val()
            }]
        };

        $('.ui-layout-center .header').css('display', 'block');

        var html = '';

        html += '<ul><li>';
        html += '<a href="#formulario-' + Projeto.TabCount + '">' + Dados.nome_formulario.val() + '</a>';
        html += '<span class="ui-icon ui-icon-close">Fechar Formulário</span>';
        html += '</li></ul>';
        html += '<div id="formulario-' + Projeto.TabCount + '">';
        html += '    <div class="area-formulario">';
        html += '        <ul id="itens-formulario-' + Projeto.TabCount + '" class="itens-formulario scrolling-content">';
        html += '            <input type="hidden" class="controller" value="' + Dados.nome_controller.val() + '" />';
        html += '            <input type="hidden" class="action" value="' + Dados.nome_action.val() + '" />';
        html += '        </ul>';
        html += '    </div>';
        html += '</div>';

        $('#tab-formularios').append(html);

        //$('#tab-formularios').tabs();

        Projeto.Tab = $('#tab-formularios').tabs({
            tabTemplate: '<li><a href="#{href}">#{label}</a><span class="ui-icon ui-icon-close">Fechar Formulário</span></li>',

            add: function( event, ui ) {

                var conteudo = '';
                conteudo += '<div class="area-formulario">';
                conteudo += '<ul id="itens-formulario-' + Projeto.TabCount + '" class="itens-formulario scrolling-content">';
                conteudo += '<input type="hidden" class="controller" value="" />';
                conteudo += '<input type="hidden" class="action" value="" />';
                conteudo += '</ul>';
                conteudo += '</div>';
                $( ui.panel ).append(conteudo);
            },
            show : function(){
                // cria a barra de rolagem caso necessario
                initPaneScrollbar('center', outerLayout.panes.center);
                Formulario.DesmarcaSelecionado();
                Grid.ExibeMenuPropriedadesFormulario();
            }
        });

        Projeto.SetSortable();
        Grid.ExibeMenuPropriedadesFormulario();
        Projeto.TabCount++;
        
        Projeto.AtivarMenus();
    },

    NovoFormulario : function(DadosForm){

        Projeto.Tab.tabs('add', '#formulario-' + Projeto.TabCount, DadosForm.nome_formulario.val());
        Projeto.SetSortable();
        
        $('#itens-formulario-' + Projeto.TabCount + ' .controller').val(DadosForm.nome_controller.val());
        $('#itens-formulario-' + Projeto.TabCount + ' .action').val(DadosForm.nome_action.val());
        Projeto.TabCount++;
    },

    SetSortable : function(){

        $('#itens-formulario-' + Projeto.TabCount).sortable({
            //containment: '#limite-formulario',
            placeholder: 'ui-state-highlight',

            stop: function(event, ui) {
                Formulario.MarcaSelecionado(ui.item);
                Grid.ExibePropriedades();
            },

            start: function(event, ui) {
                Formulario.MarcaSelecionado(ui.item);
                Grid.ExibePropriedades();
                $('.ui-state-highlight').height(ui.item.height());
            }

        });

    },
    
    AtivarMenus: function()
    {
        // 
        $("[ProjetoDependencia]").removeAttr('disabled');
    },
    
    DesativarMenus: function()
    {
        $("[ProjetoDependencia]").attr('disabled', 'true');
    }
    

}
