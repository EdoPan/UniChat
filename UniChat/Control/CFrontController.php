<?php
declare(strict_types = 1);
require_once __DIR__ . "\..\utility.php";
class CFrontController
{

    /**
     * Permette di richiamare la classe e il metodo del controllore richiesto elaborando la url che il server riceve.
     * Se non si hanno classi e metodi nella url allora si viene rimandati alla home.
     * Se vengono forniti classi o metodi inesistenti si riceve il codice di errore HTTP 400.
     */
    public function run()
    {
        $url = $_SERVER['REQUEST_URI'];
        $urlComponents = explode('/', $url);
        array_shift($urlComponents);
        array_shift($urlComponents);

        if ($urlComponents[0] != "") {
            $controller = $urlComponents[0];
            if (count($urlComponents) > 1) {
                $method = $urlComponents[1];

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
                            if (count($params) == 1) $controller->$method($params[0]);
                            elseif (count($params) == 2) $controller->$method($params[0], $params[1]);
                        } else {
                            $controller->$method();
                        }
                    } else {
                        header('HTTP/1.1 400 Bad Request');
                    }
                } else {
                    header('HTTP/1.1 400 Bad Request');
                }
            } else {
                header('HTTP/1.1 400 Bad Request');
            }
        } else {
            $cGestioneHome = new CGestioneHome();
            $cGestioneHome->visualizzaHome();
        }
    }

}