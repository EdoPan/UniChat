<?php
    function autoload($className)
    {
        $entityClass = __DIR__ . "/" . "Entity" . "/" . $className . ".php";
        $entityUtilityClass = __DIR__ . "/" . "Entity" . "/" . "Utility" . "/" . $className . ".php";
        $foundationClass = __DIR__ . "/" . "Foundation" . "/" . $className . ".php";
        $foundationUtilityClass = __DIR__ . "/" . "Foundation" . "/" . "Utility" . "/" . $className . ".php";
        $controlClass = __DIR__ . "/" . "Control" . "/" . $className . ".php";
        $viewClass = __DIR__ . "/" . "View" . "/" . $className . ".php";
        if(file_exists($entityClass)){
            include $entityClass;
        } else if (file_exists($entityUtilityClass)) {
            include $entityUtilityClass;
        } else if (file_exists($foundationClass)) {
            include $foundationClass;
        } else if (file_exists($foundationUtilityClass)) {
            include $foundationUtilityClass;
        } else if (file_exists($controlClass)){
            include $controlClass;
        } else if (file_exists($viewClass)) {
            include $viewClass;
        } else {
            return false;
        }
        return true;
    }

    spl_autoload_register("autoload");