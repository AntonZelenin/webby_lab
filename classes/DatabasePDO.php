<?php

class DatabasePDO {
    private $connection = null;

    public function __construct() {
        $conf = parse_ini_file(ROOT.'/conf/db_params.ini', true);

        $host = $conf['host'];
        $database = $conf['database'];
        $user = $conf['user'];
        $password = $conf['password'];
        $charset = $conf['charset'];

        $dsn = "mysql:host = $host; dbname = $database; charset = $charset";
        $opt = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        $this->connection = new PDO($dsn, $user, $password, $opt);
    }

    function getConnection() {
        return $this->connection;
    }

    function __destruct() {
        $this->connection = null;
    }

}
