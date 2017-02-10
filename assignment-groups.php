<?php        
    require_once("includes/Autoloader.Class.php");
    require_once("handler/assignment-groups.handler.php");
    Autoloader::Init(Dev::DEBUG);    

    if (!isset($_SESSION['user'])) 
    {        
        //User is not LoggedIn
        Helper::redirectTo("index.php"); 
        exit;        
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo APPNAME; ?> > AssignmentGroups</title>

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
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container-fluid">
            <!-- Titel und Schalter werden fuer eine bessere mobile Ansicht zusammengefasst -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Navigation ein-/ausblenden</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#"> <?php echo APPNAME; ?></a>
            </div>

            <!-- Alle Navigationslinks, Formulare und anderer Inhalt werden hier zusammengefasst und koennen dann ein- und ausgeblendet werden -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Bearbeiter-Gruppen <span class="caret"></span></a>
                        <ul class="dropdown-menu">                                
                            <li><a href="assignment-groups.php"> Bearbeiter-Gruppen anzeigen</a></li>
                            <li><a href="assignment-groups.php#create-group"> Bearbeiter-Gruppen hinzufügen</a></li>                                
                            <li role="separator" class="divider"></li>
                            <li><a href="assignment-groups.php#create-sub-group"></span> Bearbeiter-Untergruppen hinzufügen</a></li>
                        </ul>
                    </li>
                    <li><a href="menu-points.php">Menü-Struktur</a></li>
                    <li><a href="templates.php">Templates</a></li> 
                </ul>

                <ul class="nav navbar-nav navbar-right">                                
                    <!--<form class="navbar-form navbar-left" role="search">
                        <div class="form-group">
                            <input class="form-control" placeholder="Search" type="text">
                        </div>
                        <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></span></button>
                    </form> -->
                    <p class="navbar-text logged_in_as" style="padding-left: 10px;">Logged in as <a href="account-panel.php"><?php echo unserialize($_SESSION['user'])->getUsername() ?></a></p>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Menu <span class="caret"></span></a>
                        <ul class="dropdown-menu">                                
                            <li><a href="admin-panel.php"><span class="glyphicon glyphicon-cog"></span> Adminpanel</a></li>
                            <li><a href="account-panel.php"><span class="glyphicon glyphicon-user"></span> Accountpanel</a></li>                                
                            <li role="separator" class="divider"></li>
                            <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>

    <div class='container main-frame'>
        <?php echo handler(); ?>
    </div>

    <!-- jQuery (wird für Bootstrap JavaScript-Plugins benötigt) -->
    <script src="js/jquery.min.js"></script>
    <!-- Binde alle kompilierten Plugins zusammen ein (wie hier unten) oder such dir einzelne Dateien nach Bedarf aus -->
    <script src="js/bootstrap.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            if(location.hash != null && location.hash != ""){
                $('.collapse').removeClass('in');
                $(location.hash + '.collapse').collapse('show');
            }
        });

        $(document).ready(function(){
            $('[data-toggle="popover"]').popover({
                 html : true
            });
        });      

        $('.show-group-details').on('click', function() {           
            var id = $(this).data("id");
            var re;
            var desciptionFound = false;

            console.log("clicked");

            $.get("ajax.php?act=getGroup&id=" + id, function(data) {
                switch (data.AJAXCode) 
                {
                    case -1:
                        console.log("No Permission");
                        break;
                    case 0:
                        if(data.description == null || data.description == "")
                        {
                            re = "No description found.";
                        }
                        else
                        {
                            re = data.description;
                            desciptionFound = true;
                        }

                        $(".modal-body").html("<form action='#" + id + "' method='POST'>");
                            $(".modal-body").append("<div class='form-group'>");
                                $(".modal-body").append("<label for='description'>Description:</label>");
                                $(".modal-body").append("<textarea name='description' class='form-control' rows='3'>" + re + "</textarea>");
                            $(".modal-body").append("</div>");
                            $(".modal-body").append("<button style='margin-top: 10px; align: right;' type='submit' class='btn btn-primary'>Save Changes</button>");
                        $(".modal-body").append("</form>");

                        $('#descriptionModal').modal();
                        break;             
                                       
                }                              
            }, "json" );             
        });     
        
</script>
  </body>
</html>