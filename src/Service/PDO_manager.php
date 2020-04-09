<?php


namespace App\Service;


class PDO_manager implements DBInterface
{
    private $pdo;

    public function __construct(){
        $sdn = "mysql:host=localhost;dbname=steden_steven";
        $user = "root";
        $passwd = "ArtHur17";

        $this->pdo = new \PDO( $sdn, $user, $passwd);
    }

    public function GetData($sql){
        $stm = $this->pdo->prepare($sql);
        $stm->execute();

        $rows = $stm->fetchAll(\PDO::FETCH_ASSOC);
        return $rows;
    }

    public function ExecuteSQL($sql){
        $stm = $this->pdo->prepare($sql);

        if ( $stm->execute() ) return true;
        else return false;
    }
}

