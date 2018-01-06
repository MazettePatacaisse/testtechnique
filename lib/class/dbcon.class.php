<?php


/**
 * Class dbcon
 */
// On se connecte à la base de données
class dbcon {

    /**
     * dbconn constructor.
     */
    public function connect()
    {
        global $mysql_host,$mysql_user,$mysql_pass,$mysql_db,$mysql_port,$mysql_prefix, $mysql_chartset;
        include_once (BASEPATH."lib/db.php");
        spl_autoload_register('load');
        return new MysqliDb ([
            "host" => $mysql_host,
            "username" => $mysql_user,
            "password" => $mysql_pass,
            "db" => $mysql_db,
            "port" => $mysql_port,
            "prefix" => $mysql_prefix,
            "chartset" => $mysql_chartset]);

    }

}
