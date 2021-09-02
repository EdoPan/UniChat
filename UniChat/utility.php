<?php
    function autoload($className)
    {
        $entityClass = __DIR__ . "/" . "Entity" . "/" . $className . ".php";
        $fondationClass = __DIR__ . "/" . "Fondation" . "/" . $className . ".php";
        $controlClass = __DIR__ . "/" . "Control" . "/" . $className . ".php";
        $viewUtilityClass = __DIR__ . "/" . "View" . "/" . "Utility" . "/" . $className . ".php";
        if(file_exists($entityClass)){
            include $entityClass;
        } else if (file_exists($fondationClass)) {
            include $fondationClass;
        } else if (file_exists($controlClass)){
            include $controlClass;
        } else if (file_exists($viewUtilityClass)) {
            include $viewUtilityClass;
        } else {
            return false;
        }
        return true;
    }

    spl_autoload_register("autoload");