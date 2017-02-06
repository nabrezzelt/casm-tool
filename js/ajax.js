//AJAX
        $('.description-loader-group').on('click', function() {
            var loaded = $(this).data("loaded");
            var id = $(this).data("id");

            if(loaded != "true")
            {
                $.get( "handler/ajax.handler.php?act=getGroup&id=" + id, function(data) {
                    switch(data.AJAXCode)
                    {
                        case "-1":
                            //No Permission
                            console.log("No Permission");
                        break;
                        case "0":
                            //OK
                           console.log("OK");
                                //.append( "Name: " + data.name ) // John
                                //.append( "Time: " + data.time ); //  2pm
                    
                        break;
                        case "1":
                            //Failed
                            console.log("Failed");
                        break;
                    }
                });                    
            }
        });



//AJAX
        $('.description-loader-group').on('click', function() {
            var loaded = $(this).data("loaded");
            var id = $(this).data("id");
            
            if(loaded == "true")
            {
                print("t" + id);
            }

            if(loaded == "false") {
                print("f" + id);
            }
        }); 


         $(document).ready(function(){
            $('[data-toggle="popover"]').popover({
                 html : true,
                 content : function()
                    {
                        $.get("ajax.php?act=getGroup&id=" + id, function(data) {
                            var re;
                            if(data.description == null)
                            {
                                re += "No description found.";
                            }
                            return re + "<hr />" + data.statusName;
                        }, "json" );
                    } 
            });
        });


         //AJAX
        $('.description-loader-group').on('click', function(e) {
            //e.preventDefault();

            var element = $(this);
            var loaded = $(this).data("loaded"); //bool
            var id = $(this).data("id"); //Int

            

            if(loaded == false) {
                console.log("start loading");
                $.get("ajax.php?act=getGroup&id=" + id, function(data) {  
                    element.popover({
                        html : true,
                        content : data.statusName                       
                    });                                           
                }, "json" );
            }
        });   