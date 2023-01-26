<?php

namespace Links\Database;

use Links\App\View;
use Links\Exceptions\ExceptionController;

class Connect
{
    private static $pdo = null;

    private static string $host;
    private static string $dbname;
    private static string $user;
    private static string $password;

    public function __construct() 
    {
        $data = $this->getConfig();

        self::$host = $data->host;
        self::$dbname = $data->dbname;
        self::$user = $data->user;
        self::$password = $data->password;

        try {
            self::$pdo = new \PDO('mysql:host=' . self::$host . ';dbname=' . self::$dbname , self::$user, self::$password, [\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"]);
        } catch (\Exception $e) {
            ExceptionController::catchException($e);
        }
    }

    /**
     * @return object
     */

    public function getConfig() :object
    {
        return $data = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/config/config.json'));
    }

    /**
     * @return object|null
     */

    public static function getConnect() :object|null
    {
        if (self::$pdo !== null) {
            return self::$pdo;
        }
    }
}