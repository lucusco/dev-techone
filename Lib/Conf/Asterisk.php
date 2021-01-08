<?php

namespace Techone\Lib\Conf;

use Techone\Lib\Model\Ramal;

class Asterisk
{
    private static $generalOptions = "
[general]
bindport=5060
context=dummy
disallow=all
allow=ulaw
allow=alaw
alwaysauthreject=yes
allowguest=no\n";

    /**
     * Escreve arquivo de configuração sip.conf
     * @return true|false
     */
    public static function escreveConfRamais(): bool
    {
        $ramais = Ramal::todosRamais();
       
        if (!empty($ramais)) {
            $filename = BASE_DIR . 'Files/sip.conf';
            clearstatcache();

            if (file_exists($filename))
                unlink($filename);

            if (!$file = fopen($filename, 'w'))
                return false;

            $infoGeral = ";Arquivo gerado automaticamente em " . date('d-m-Y H:i:s') . "\n" . self::$generalOptions;
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
nat=yes
dtmfmode=rfc2833
insecure=invite,port
canreinvite=yes
context={$ramal->context}\n";
        
        return $linha;
    }
}
