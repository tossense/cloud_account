<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="en">
    <head>
    <meta charset="UTF-8">
    <title>Sample</title>
    <!-- Include the latest version of jQuery library -->
    <script type="text/javascript" src="http://cdn.staticfile.org/jquery/2.1.1-rc2/jquery.min.js"></script>
    <script type="text/javascript">
        $(function() {
            $.getJSON("api/api.php?method=allUsers&jsoncallback=?",
            function(dataGet) {
                console.log(dataGet);
                for(oneuser in dataGet) {
                    var user = dataGet[oneuser];
                    $("#output").append(user.name + "<br />");
                }
            });
        });
        </script>
    </head>
    <body>
    <div id="output"></div>
   </body>
</html>
