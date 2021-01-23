<?php

namespace Techone\Lib\Conf;

use Techone\Lib\Model\Ramal;

class Asterisk
{

    /**
     * Escreve arquivo de configuração sip.conf
     * @return true|false
     */
    public static function escreveConfRamais(): bool
    {
        $ramais = Ramal::todosRamais();

        $generalOptions = "
[general]
bindport=5060
context=dummy
disallow=all
allow=ulaw
allow=alaw
alwaysauthreject=yes
allowguest=no\n";
       
        if (!empty($ramais)) {
            $filename = BASE_DIR . 'Files/sip.conf';
            clearstatcache();

            if (file_exists($filename))
                unlink($filename);

            if (!$file = fopen($filename, 'w'))
                return false;

            $infoGeral = ";Arquivo gerado automaticamente em " . date('d-m-Y H:i:s') . "\n$generalOptions";
            if (fwrite($file, $infoGeral) === FALSE)
                return false;

            foreach ($ramais['ramais'] as $ramal) {
                if (fwrite($file, self::montaExten($ramal)) === FALSE)
                    return false;
            }
            fclose($file);

            return true;
        }
    }

    /**
     * Monta a linha a ser escrita no arquivo
     *
     * @param object $ramal
     * @return string
     */
    private static function montaExten($ramal): string
    {
        $linha = "
[{$ramal->exten}]
callerid=\"{$ramal->username}\" <{$ramal->exten}>
type=friend
secret={$ramal->secret}
host=dynamic
qualify=yes
directmedia=no
nat=force_rport,comedia 
dtmfmode=rfc2833
insecure=invite,port
canreinvite=yes
context={$ramal->context}\n";
        
        return $linha;
    }
}
