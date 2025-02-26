<?php

@include "./Database.php";

$dataBase = new Database( "localhost" , "dataFtest" , "root" , "");

$dataBase->deleteDatabase();

