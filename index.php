<?php

@include "./Database.php";

$dataBase = new Database( "localhost" , "dataFtest" , "root" , "");

//$dataBase->createTable("user" , array("id" => "INT(11) PRIMARY KEY"));

$dataBase->renameTable("user" , "userss");


