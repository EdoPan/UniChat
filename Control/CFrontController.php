<?php
declare(strict_types = 1);
require_once __DIR__ . "\..\utility.php";

/**
 * Classe responsabile di richiamare le classi di controllo ed i metodi in esse presenti responsabili di eseguire le
 * operazioni necessarie a soddisfare le richieste di un utente.
 * Tali richieste vengono ricevute sotto forma di URL autodescrittive.
 */
class CFrontController
{

    /**
     * Permette di richiamare la classe e il metodo del controllore richiesto elaborando la URL che il server riceve.
     * Se non si hanno classi e metodi nella url allora viene richiamata la classe di controllo e rispettivo metodo,
     * responsabili della visualizzazione della home page.
     * Se vengono forniti classi o metodi inesistenti si riceve il codice di stato HTTP 400.
     */
    public function run(): void
    {
        $url = $_SERVER['REQUEST_URI'];
        $urlComponents = explode('/', $url);
        array_shift($urlComponents);
        array_shift($urlComponents);

        if ($urlComponents[0] != "") {

            /*
             * Recupero del nome della classe di controllo da richiamare.
             */
            $controller = $urlComponents[0];
            if (count($urlComponents) > 1) {

                /*
                 * Recupero del nome del metodo da richiamare.
                 */
                $method = $urlComponents[1];

                /*
                 * Recupero dei parametri dalla URL autodescrittiva.
                 */
                if (count($urlComponents) > 2) {
                    $params = array();
                    array_shift($urlComponents);
                    array_shift($urlComponents);
                    foreach ($urlComponents as $component) {
                        $params[] = $component;
                    }
                }
            }

            $controller = "CGestione" . ucfirst($controller);
            if (class_exists($controller)) {
                $controller = new $controller();
                if (isset($method)) {
                    if (method_exists($controller, $method)) {
                        if (isset($params)) {

                            if (count($params) == 1) {
                                /*
                                * Gestione caso con un solo parametro.
                                */
                                if ((int)$params[0] == 0) {
                                    /*
                                    * Verifica che il primo parametro sia una stringa o meno.
                                    */
                                    $controller->$method($params[0]);
                                } else {
                                    $controller->$method((int)$params[0]);
                                }
                            } elseif (count($params) == 2) {
                                /*
                                 * Gestione caso con due parametri.
                                 */
                                if ((int)$params[1] == 0) {
                                    /*
                                    * Verifica che il secondo parametro sia una stringa o meno.
                                    */
                                    $controller->$method((int)$params[0], $params[1]);
                                } else {
                                    $controller->$method((int)$params[0], (int)$params[1]);
                                }
                            } else if (count($params) == 3) {
                                /*
                                 * Gestione caso con tre parametri.
                                 */
                                if ((int)$params[2] == 0) {
                                    /*
                                    * Verifica che il terzo parametro sia una stringa o meno.
                                    */
                                    $controller->$method((int)$params[0], (int)$params[1], $params[2]);
                                } else {
                                    $controller->$method((int)$params[0], (int)$params[1], (int)$params[2]);
                                }
                            }
                        } else {
                            $controller->$method();
                        }
                    } else {
                        /*
                        * Se nella URL viene fornito un nome di un metodo inesistente allora si visualizza una
                        * pagina di errore con il codice di stato HTTP 400.
                        */
                        $vError = new VError();
                        $vError->setValoriErrore(VError::CODE_400, VError::TYPE_400);
                    }
                } else {
                    /*
                     * Se nella URL viene fornito il nome di una classe di controllo ma non quello del metodo da eseguire
                     * allora si visualizza una pagina di errore con il codice di stato HTTP 400.
                     */
                    $vError = new VError();
                    $vError->setValoriErrore(VError::CODE_400, VError::TYPE_400);
                }
            } else {
                /*
                 * Se nella URL viene fornito un nome di una classe di controllo inesistente allora si visualizza una
                 * pagina di errore con il codice di stato HTTP 400.
                 */
                $vError = new VError();
                $vError->setValoriErrore(VError::CODE_400, VError::TYPE_400);
            }
        } else {
            /*
             * Se nella URL non si hanno nome di classe e nome del metodo allora si viene rimandati alla home page.
             * Questo richiede una determinata classe di controllo e l'esecuzione di un apposito metodo.
             */
            $cGestioneHome = new CGestioneHome();
            $cGestioneHome->visualizzaHome();
        }
    }

}