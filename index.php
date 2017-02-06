<?php   
    require_once("includes/Autoloader.Class.php");
    Autoloader::Init(Dev::DEBUG);    

    if (isset($_SESSION['user'])) 
    {        
         //User is LoggedIn
         Helper::redirectTo("main.php");  
         exit;       
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo APPNAME; ?> > Login</title>

    <meta charset="ISO-8859-1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1"> 

    <!-- Bootstrap -->
    <link href="css/sandstone.bootstrap.min.css" rel="stylesheet">

    <!-- Unterstützung für Media Queries und HTML5-Elemente in IE8 über HTML5 shim und Respond.js -->
    <!-- ACHTUNG: Respond.js funktioniert nicht, wenn du die Seite über file:// aufrufst -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <link href="styles/style.css" type="text/css" rel="stylesheet" />
    <link href="favicon.ico" type="image/x-icon" rel="shortcut icon" />
    <link rel="stylesheet" href="css/font-awesome.min.css">      
</head>
<body>    
    <div class="container-fluid">
        <?php
            if (isset($_GET['error']) && isset($_GET['color'])) {
                echo "<div style=\"margin-top: 10px;\" class=\"alert alert-dismissible alert-" . $_GET['color'] . "\">
                        <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
                        " . $_GET['error'] . "<br/>
                      </div>";                    
            }
        ?>
        <div class="panel panel-default login-frame">
            <div class="panel-heading text-center"><h4></span> Login</h4></div>
            <div class="panel-body">
                 <form role="form" action="login.php" method="POST">
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="username" class="form-control" name="username" />                        
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" class="form-control" name="password" />
                    </div>
                    <div class='row'>
                        <div class='col-sm-6'>
                            <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-log-in"></span> Login</button>
                        </div>
                        <div class='col-sm-6 text-right'>
                            <a class='btn btn-default' href='register.php'>Register</a>
                        </div>
                    </div>                    
                    
                </form>
            
            </div>
        </div>
    </div>

    <!-- jQuery (wird für Bootstrap JavaScript-Plugins benötigt) -->
    <script src="js/jquery.min.js"></script>
    <!-- Binde alle kompilierten Plugins zusammen ein (wie hier unten) oder such dir einzelne Dateien nach Bedarf aus -->
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>