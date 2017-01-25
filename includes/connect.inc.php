<?php 
    session_start();        

    $host = "localhost";
    $username = "root";
    $password = "ascent";
    $database = "casm-tool";    

    $connection = mysql_connect($host, $username, $password, $database);
    $connectedDatabase = mysql_select_db($database) or die ("<p>Datenbank nicht gefunden oder fehlerhaft</p>"); 
 ?>