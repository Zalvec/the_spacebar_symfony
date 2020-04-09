<?php


namespace App\Service;


interface DBInterface
{
    public function GetData( $sql );
    public function ExecuteSQL( $sql );
}