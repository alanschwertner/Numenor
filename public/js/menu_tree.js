$(function(){

    });

var MenuTree = {
    
    MontaMenu : function(estrutura, diretorio){
        
        var html = '';
        
        for (var i in estrutura) {
            
            if (estrutura[i].type == 'folder') {
                
                diretorio += i + '/';
                
                html += '<li class="closed">';
                html += '    <span class="folder">' + i + '</span>';
                
                var pasta = '';
                
                for (var j in estrutura[i]) {
                    
                    if (j != 'type'){
                        var obj = new Object();
                        obj[j] = estrutura[i][j];
                        
                        pasta += MenuTree.MontaMenu(obj, diretorio);
                    }
                    
                }

                if ($.trim(pasta) != ''){
                    html += '<ul>' + pasta + '</ul>';
                }
                
                html += '</li>';
                
            } else if (estrutura[i].type == 'file') {
                html += '<li><span class="file arquivo" arq="' + diretorio + i + '">' + i + '</span></li>';
            }
            
        }
        
        return html;
        
    }
    
}


