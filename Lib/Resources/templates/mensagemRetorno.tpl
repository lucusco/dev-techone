        {if isset($tipo) && isset($mensagem)}
            <script>
                function exibeMsg(tipo, msg) {
                    Swal.fire({
                        position: 'center',
                        icon: tipo,
                        title: '',
                        text: msg,
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    } );
                }
                exibeMsg('{$tipo}', '{$mensagem}')
            </script>
        {/if}