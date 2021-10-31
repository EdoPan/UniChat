<?php
require_once __DIR__ . "\..\utility.php";
require_once "VSmarty.php";

/**
 * Classe view per la gestione delle pagine di errore.
 */
class VError
{

    /**
     * Codici di stato HTTP client side 4xx.
     */
    const CODE_400 = 400;
    const CODE_401 = 401;
    const CODE_402 = 402;
    const CODE_403 = 403;
    const CODE_404 = 404;
    const CODE_405 = 405;
    const CODE_406 = 406;
    const CODE_407 = 407;
    const CODE_408 = 408;
    const CODE_409 = 409;
    const CODE_410 = 410;
    const CODE_411 = 411;
    const CODE_412 = 412;
    const CODE_413 = 413;
    const CODE_414 = 414;
    const CODE_415 = 415;
    const CODE_416 = 416;
    const CODE_417 = 417;
    const CODE_418 = 418;
    const CODE_420 = 420;
    const CODE_422 = 422;
    const CODE_426 = 426;
    const CODE_429 = 429;
    const CODE_431 = 431;
    const CODE_449 = 449;
    const CODE_451 = 451;

    /**
     * Codici di stato HTTP server side 5xx
     */
    const CODE_500 = 500;
    const CODE_501 = 501;
    const CODE_502 = 502;
    const CODE_503 = 503;
    const CODE_504 = 504;
    const CODE_505 = 505;
    const CODE_509 = 509;

    /**
     * Tipologie di errore HTTP client side.
     */
    const TYPE_400 = "Bad Request";
    const TYPE_401 = "Unauthorized";
    const TYPE_402 = "Payment Required";
    const TYPE_403 = "Forbidden";
    const TYPE_404 = "Not Found";
    const TYPE_405 = "Method Not Allowed";
    const TYPE_406 = "Not Acceptable";
    const TYPE_407 = "Proxy Authentication Required";
    const TYPE_408 = "Request Timeout";
    const TYPE_409 = "Conflict";
    const TYPE_410 = "Gone";
    const TYPE_411 = "Length Required";
    const TYPE_412 = "Precondition Failed";
    const TYPE_413 = "Request Entity Too Large";
    const TYPE_414 = "Request-URI Too Long";
    const TYPE_415 = "Unsupported Media Type";
    const TYPE_416 = "Requested Range Not Satisfiable";
    const TYPE_417 = "Expectation Failed";
    const TYPE_418 = "I'm a teapot";
    const TYPE_420 = "Enhance your calm";
    const TYPE_422 = "Unprocessable Entity";
    const TYPE_426 = "Upgrade Required";
    const TYPE_429 = "Too many requests";
    const TYPE_431 = "Request Header Fields Too Large";
    const TYPE_449 = "Retry With";
    const TYPE_451 = "Unavailable For Legal Reasons";

    /**
     * Tipologie di errore HTTP server side.
     */
    const TYPE_500 = "Internal Server Error";
    const TYPE_501 = "Not Implemented";
    const TYPE_502 = "Bad Gateway";
    const TYPE_503 = "Service Unavailable";
    const TYPE_504 = "Gateway Timeout";
    const TYPE_505 = "HTTP Version Not Supported";
    const TYPE_509 = "Bandwidth Limit Exceeded";


    private Smarty $smarty;

    public function __construct() {
        $this->smarty = VSmarty::start();
    }

    /**
     * @param int $codiceErrore
     * @param string $tipoErrore
     * Metodo responsabile dell'assegnazione del codice e del tipo di errore.
     */
    public function setValoriErrore(int $codiceErrore, string $tipoErrore) {

        $this->smarty->assign('codiceErrore', $codiceErrore);
        $this->smarty->assign('tipoErrore', $tipoErrore);

    }

    /**
     * Metodo responsabile della visualizzazione di pagina-errore.tpl
     */
    public function showError() {

        $this->smarty->display('pagina-errore.tpl');
    }

}