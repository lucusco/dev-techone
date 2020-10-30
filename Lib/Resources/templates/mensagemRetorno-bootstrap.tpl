    
    {if isset($mensagem)}
    <div class="alert alert-{$mensagem->tipo} alert-dismissible fade show m-3 w-75" role="alert">
        <button class="close" type="button" data-dismiss="alert"> &times; </button>
        {$mensagem->texto}
    </div>
    {/if}