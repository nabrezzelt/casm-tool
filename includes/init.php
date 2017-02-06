<?php
    session_start();

    define('APPNAME', "CASM-CAT");
    define('APP_VERSION', 0.2);

    define("DB_HOST", "localhost");
    define("DB_USER", "root");
    define("DB_PASSWORD", "ascent");
    define("DB_DATABASE", "casm-tool");     

    $connection = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
    $connectedDatabase = mysql_select_db(DB_DATABASE) or die ("<p>Datenbank nicht gefunden oder fehlerhaft</p>");      
?>