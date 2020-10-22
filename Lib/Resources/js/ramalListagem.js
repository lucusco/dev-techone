//Atribui id do ramal no botão excluir do mobal
    $('.btn-outline-danger').click(function(){
        var id=$(this).data('id');
        $('#btnModalExcluir').attr('href','exclui-ramal?method=remover&id='+id);
    });