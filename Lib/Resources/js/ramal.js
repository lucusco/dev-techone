
    //Obter nome do arquivo ao selecioná-lo para importar
    $(".custom-file-input").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });


    //Atribui id do ramal no botão excluir do mobal
    $('.btn-outline-danger').click(function(){
        var id=$(this).data('id');
        $('#btnModalExcluir').attr('href','exclui-ramal?method=remover&id='+id);
    });
