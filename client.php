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
            $.getJSON("api/api.php?method=userBalance&jsoncallback=?",
            function(dataGet) {
                // for(var oneuser in dataGet["users"]) {
                //     console.log(JSON.stringify(oneuser));
                //     var user = dataGet["users"][oneuser];
                //     if(user.name)
                //         $("#output").append(user.name + ":"+ user.balance+"<br />");
                // }
                drawTable(dataGet["users"]);
            });
        });
    </script>
    <link rel="stylesheet" href="style.css">
    </head>
    <body>
    <div id="output">
        <table id="balanceTable">
        <tr>
            <th>User Name</th>
            <th>Balance</th>
        </tr>
        </table>
    </div>
    <script type="text/javascript">
        var drawTable = function (data) {
            for (var i = 0; i < data.length; i++) {
                drawRow(data[i]);
            }
        }

        var drawRow = function drawRow(rowData) {
            var row = $("<tr />")
            $("#balanceTable").append(row); //this will append tr element to table... keep its reference for a while since we will add cels into it
            row.append($("<td>" + rowData.name + "</td>"));
            row.append($("<td>" + rowData.balance + "</td>"));
        }
    </script>
   </body>
</html>
