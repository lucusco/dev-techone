<?php

namespace Techone\Lib\Conf;

use Techone\Lib\Model\Ramal;

class Asterisk
{
    const SIP_FILE = '/etc/asterisk/sip.conf';
    const EXTENSIONS_FILE = '/etc/asterisk/extensioins.conf';
    const DIALPLAN_RELOAD = "asterisk -rx 'sip reload'";

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
            $sipConf = BASE_DIR . 'Files/sip.conf';
            clearstatcache();

            if (file_exists($sipConf))
                unlink($sipConf);

            if (!$file = fopen($sipConf, 'w'))
                return false;

            $infoGeral = ";Arquivo gerado automaticamente em " . date('d-m-Y H:i:s') . " - Não edite diretamente\n$generalOptions";
            if (fwrite($file, $infoGeral) === FALSE)
                return false;

            foreach ($ramais['ramais'] as $ramal) {
                if (fwrite($file, self::montaExten($ramal)) === FALSE)
                    return false;
            }
            fclose($file);

            self::syncSipConf($sipConf);
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

    /**
     * Move o arquivo sip.conf para o local correto e atualiza o dialplan
     *
     * @param string $arqOrigem
     */
    public static function syncSipConf($arqOrigem)
    {
        clearstatcache();
        if (file_exists($arqOrigem)) {

            if (file_exists(self::SIP_FILE))
                rename(self::SIP_FILE, self::SIP_FILE . '.bak');

            copy($arqOrigem, self::SIP_FILE);
            exec(self::DIALPLAN_RELOAD);
        }
    }

    public static function montaContexto($faixaRamais)
    {
        
    }
}
