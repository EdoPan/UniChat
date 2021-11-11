<?php
declare(strict_types = 1);
require_once __DIR__ . "\..\utility.php";

class CGestioneClient
{
    public function javascriptDisabilitati(): void
    {
        $vErroreCliente = new VErroreClient();
        $vErroreCliente->setErrore(VErroreClient::MESSAGGIO_JAVASCRIPT_DISABILITATO);
        $vErroreCliente->showErroreClient();
    }

    public function cookieDisabilitati(): void
    {
        $vErroreCliente = new VErroreClient();
        $vErroreCliente->setErrore(VErroreClient::MESSAGGIO_COOKIES_DISABILITATI);
        $vErroreCliente->showErroreClient();
    }

}
