<?php

declare(strict_types = 1);
require_once __DIR__ . "\..\utility.php";

class FMessaggio
{
    public static function delete(int $messID): bool
    {
        $pdo = new PDO ("mysql:host=localhost;dbname=testing", "root", "pippo");
        $sql = ("DELETE FROM messaggi WHERE messID = " . $messID);
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute();
        return $result;
    }
}