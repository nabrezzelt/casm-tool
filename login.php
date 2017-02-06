<?php
     include_once("includes/Autoloader.Class.php");     
     Autoloader::Init(Dev::DEBUG);
     $db = new MysqlDatabase(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);


     $user = User::Login(mysql_real_escape_string($_POST['username']), mysql_real_escape_string($_POST['password']), $db);
     
     if ($user != null) 
     {
        $_SESSION['user'] = serialize($user); 
        Helper::redirectTo("main.php");
     }
     else
     {
         Helper::redirectTo("index.php?error=Login%20failed%21&color=warning");
     }
  ?>