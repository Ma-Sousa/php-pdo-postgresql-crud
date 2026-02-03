<?php 

if (session_status() === PHP_SESSION_NONE){
    session_start();
}

require_once __DIR__ . "/helpers.php";
require_once __DIR__ . "/db.php";
require_once __DIR__ . "/customers.php";
require_once __DIR__ . "/flash.php";
require_once __DIR__ . "/csrf.php";