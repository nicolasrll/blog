<?php

namespace Core;

use Pdo;

/**
 * Used to get the database connexion as singleton
 * @author  Nicolas Rellier <nicolasrellier@yahoo.fr>
 */
class PdoConnect
{
    private static $pdo = null;

    private function __construct() {}

    public static function getInstance(): PDO
    {
        if (is_null(self::$pdo)) {
            self::$pdo = new PDO(DSN . ';dbname=' . DBNAME, DBUSER, DBPASSWORD);
        }
        return self::$pdo;
    }
}
