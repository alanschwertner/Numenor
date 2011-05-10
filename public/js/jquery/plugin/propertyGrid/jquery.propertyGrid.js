
/**
 * Plugin desenvolvido por Cristian Cardoso
 * email: ctncardoso@ctncardoso.com.br
 * 
 */
(function( $ ){
    
    var methods = {

        init : function( options ) {

            return this.each(function(){

                var $this = $(this);

                $this.find('table').remove();

                var tabela = $('<table cellpadding="0" cellspacing="1" class="grid-menu" ></table>');

                $.each(options.grupos, function(){
                    
                    tabela.PropertyGrid('addGrupo', this);
                   
                });

                $this.append(tabela);
            });
        },

        addGrupo : function(options){
            var paramGrupo = {
                classe : 'grid-menu',
                nome : 'Menu Propriedades',
                itens : []
            }

            var obj = $.extend(paramGrupo, options);

            return this.each(function(){

                var $this = $(this)

                var trGrupo = '';
                trGrupo += '<tr id="titulo-grid">';
                trGrupo += '<td class="grupo" colspan="3">' + obj.nome + '</td>';
                trGrupo += '</tr>';

                $this.append(trGrupo);

                $.each(options.itens, function(){

                    $this.PropertyGrid('addItem', this);

                });

            });
        },

        addItem : function(options) {

            var itensGrid = {
                tipo : 'input'
            }

            var item = $.extend(itensGrid, options);

            return this.each(function(){
                var $this = $(this);

                switch(item.tipo){
                    case 'input':
                        $this.PropertyGrid('addText', item);
                        break;
                    case 'spinner':
                        $this.PropertyGrid('addSpinner', item);
                        break;
                    case 'select':
                        $this.PropertyGrid('addSelect', item);
                        break;
                    case 'button':
                        $this.PropertyGrid('addInputButton', item);
                        break;
                }

            });
        },
        
        addText : function(options){
            var itensGrid = {
                titulo : 'Campo Input Text',
                id : '',
                name : '',
                value : '',
                getValue : '',
                setValue : function(){},
                info : 'Não Informado'
            }

            var item = $.extend(itensGrid, options);

            item.id = (item.id == '') ? 'randon' : item.id;
            item.name = (item.name == '') ? item.id : item.name;
            item.value = (item.value == '') ? item.getValue : item.value;

            return this.each(function(){
                var $this = $(this);

                var tr = $('<tr id="grupo-' + item.id + '"></tr>');

                var tdLabel = '';
                tdLabel += '<td class="separador"></td>';
                tdLabel += '<td class="label">';
                tdLabel += '<label for="campo-' + item.id + '">' + item.titulo + '</label>';
                tdLabel += '</td>';

                tdLabel = $(tdLabel);

                var tdCampo = $('<td class="campo"></td>');
                var spanCampo = $('<span class="valor" id="span-' + item.id + '">' + item.value + '</span>').show();
                var inputCampo = $('<input type="text" name="campo-' + item.id + '" id="campo-' + item.id + '" value="' + item.value + '" />').hide();

                var tdInf = $('<td class="info">' + Img.Info + '</td>');
                
                tdInf.find('img').attr('title', item.info);
                tdInf.find('img').tipTip();
                
                
                inputCampo.bind({
                    blur : function(){
                        $(this).hide();
                        spanCampo.html($(this).val());
                        spanCampo.show();
                        item.setValue($(this).val());
                    }
                });

                tr.bind({
                    click : function(){
                        spanCampo.hide();
                        inputCampo.show();
                        inputCampo.focus();
                    }
                });

                tdCampo.append(spanCampo);
                tdCampo.append(inputCampo);

                tr.append(tdLabel);
                tr.append(tdCampo);
                tr.append(tdInf);

                $this.append(tr);
            });
        },

        addSpinner : function(options){
            var itensGrid = {
                titulo : 'Campo Spinner',
                id : '',
                name : '',
                min : 0,
                max : 1000,
                getValue : '',
                setValue : function(){},
                info : 'Não Informado'
            }

            var item = $.extend(itensGrid, options);

            item.id = (item.id == '') ? 'randon' : item.id;
            item.name = (item.name == '') ? item.id : item.name;
            item.value =  item.getValue;
            item.value = (item.value == '') ? item.min : item.value;


            return this.each(function(){
                var $this = $(this);

                var tr = $('<tr id="grupo-' + item.id + '"></tr>');

                var tdLabel = '';
                tdLabel += '<td class="separador"></td>';
                tdLabel += '<td class="label">';
                tdLabel += '<label for="campo-' + item.id + '">' + item.titulo + '</label>';
                tdLabel += '</td>';

                tdLabel = $(tdLabel);

                var tdCampo = $('<td class="campo"></td>');
                var spanCampo = $('<span class="valor" id="span-' + item.id + '">' + item.value + '</span>').hide();
                var spanHideCampo = $('<span class="spinner"></span>').show();
                var inputCampo = $('<input type="text" name="campo-' + item.id + '" id="campo-' + item.id + '" value="' + item.value + '" class="spinner-numerico" />');

                var tdInf = $('<td class="info">' + Img.Info + '</td>');
                tdInf.find('img').attr('title', item.info);
                tdInf.find('img').tipTip();

                inputCampo.bind({
                    blur : function(){
                        spanHideCampo.hide();
                        spanCampo.html($(this).val());
                        spanCampo.show();
                        item.setValue($(this).val());
                    },
                    change : function(){
                        item.setValue($(this).val());
                    }
                });

                tr.bind({
                    click : function(){
                        spanCampo.hide();
                        spanHideCampo.show();
                        inputCampo.spinner({
                            min: item.min,
                            max: item.max
                        }).focus();
                    }
                });

                tdCampo.append(spanCampo);
                spanHideCampo.append(inputCampo);
                tdCampo.append(spanHideCampo);

                tr.append(tdLabel);
                tr.append(tdCampo);
                tr.append(tdInf);


                $this.append(tr);
               
            });
        },

        addInputButton : function(options){
            var itensGrid = {
                titulo : '',
                id : '',
                name : '',
                dialog : '',
                value : '',
                getValue : '',
                setValue : function(){},
                info : 'Não Informado'
            }

            var item = $.extend(itensGrid, options);

            item.id = (item.id == '') ? 'randon' : item.id;
            item.name = (item.name == '') ? item.id : item.name;
            item.value = (item.value == '') ? item.getValue : item.value;

            
            
            return this.each(function(){
                var $this = $(this);

                var tr = $('<tr id="grupo-' + item.id + '"></tr>');

                var tdLabel = '';
                tdLabel += '<td class="separador"></td>';
                tdLabel += '<td class="label">';
                tdLabel += '<label for="campo-' + item.id + '">' + item.titulo + '</label>';
                tdLabel += '</td>';

                tdLabel = $(tdLabel);

                var tdCampo = $('<td class="campo"></td>');
                var spanCampo = $('<span class="valor-button" id="span-' + item.id + '">' + item.value + '</span>').show();

                var inputButton = $('<input type="button" class="button-grid ' + item.dialog + '" name="button-' + item.id + '" id="button-' + item.id + '" value="" />').hide();

                var tdInf = $('<td class="info">' + Img.Info + '</td>');
                tdInf.find('img').attr('title', item.info);
                tdInf.find('img').tipTip();

                inputButton.bind({
                    blur : function(){
                       inputButton.hide();
                    },
                    click : function(){
                        $(this).blur();
                        $(item.dialog).dialog('open');
                    }
                });

                tr.bind({
                    click : function(){
                        //spanCampo.hide();
                        inputButton.show();
                    inputButton.focus();
                    }
                });

                tdCampo.append(spanCampo);
                tdCampo.append(inputButton);

                tr.append(tdLabel);
                tr.append(tdCampo);
                tr.append(tdInf);

                $this.append(tr);
            });
        },

        addSelect : function(options){
            var itensGrid = {
                titulo : 'Campo Select',
                id : '',
                name : '',
                getValue : '',
                setValue : function(){},
                info : 'Não Informado'
            }

            var item = $.extend(itensGrid, options);

            item.id = (item.id == '') ? 'randon' : item.id;
            item.name = (item.name == '') ? item.id : item.name;
            item.value =  item.getValue;

            return this.each(function(){
                var $this = $(this);

                var tr = $('<tr id="grupo-' + item.id + '"></tr>');

                var tdLabel = '';
                tdLabel += '<td class="separador"></td>';
                tdLabel += '<td class="label">';
                tdLabel += '<label for="campo-' + item.id + '">' + item.titulo + '</label>';
                tdLabel += '</td>';

                tdLabel = $(tdLabel);

                var tdCampo = $('<td class="campo"></td>');
                var spanCampo = $('<span class="valor" id="span-' + item.id + '"></span>').show();

                var selectCampo = '';
                selectCampo += '<select name="campo-' + item.id + '" id="campo-' + item.id + '" >';
                $.each(item.data, function(){
                    if (this.selecionado == true || this.value == item.value) {
                        selectCampo += '<option value="' + this.value + '" selected="selected" >' + this.text + '</option>';
                    } else {
                        selectCampo += '<option value="' + this.value + '">' + this.text + '</option>';
                    }
                });
                selectCampo += '</select>';
                selectCampo = $(selectCampo).hide();
                spanCampo.html(selectCampo.find(':selected').text());

                var tdInf = $('<td class="info">' + Img.Info + '</td>');
                tdInf.find('img').attr('title', item.info);
                tdInf.find('img').tipTip();

                selectCampo.bind({
                    blur : function(){
                        $(this).hide();
                        spanCampo.html($(this).find(':selected').text());
                        spanCampo.show();
                        item.setValue($(this).val());
                    },
                    change : function(){
                        item.setValue($(this).val());
                    }

                });

                tr.bind({
                    click : function(){
                        spanCampo.hide();
                        selectCampo.show();
                        selectCampo.focus();
                    }
                });

                tdCampo.append(spanCampo);
                tdCampo.append(selectCampo);

                tr.append(tdLabel);
                tr.append(tdCampo);
                tr.append(tdInf);

                $this.append(tr);

            });
        }

    };

    $.fn.PropertyGrid = function(method) {

        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || ! method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' +  method + ' does not exist on jQuery.PropertyGrid');
        }

    };

})( jQuery );