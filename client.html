<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Sample</title>
    <!-- Include the latest version of jQuery library -->
    <script type="text/javascript" src="http://libs.baidu.com/jquery/2.0.3/jquery.min.js"></script>
    <script type="text/javascript">
        $(function() {
            $.getJSON("api/get.php?method=userBalance&group=dataminers&jsoncallback=?",
            function(dataGet) {
                //console.log(JSON.stringify(dataGet));
                drawTable(dataGet["result"]);
            });
        });
    </script>
    <link rel="stylesheet" href="style.css">
    </head>
    <body>
    <div id="output">
        <table id="balanceTable" align="center">
        <tr>
            <th>User Name</th>
            <th>Balance</th>
        </tr>
        </table>
    </div>
    <script type="text/javascript">
        var drawTable = function (nameBalance) {
            var arr = [];
            for (var name in nameBalance){
                arr.push([name, Number(nameBalance[name])]);
            }
            arr.sort(function(a,b){
                return a[1] - b[1];
            })
            for (var u in arr) {
                //console.log(name, nameBalance[name]);
                var row = $("<tr />")
                $("#balanceTable").append(row); //this will append tr element to table... keep its reference for a while since we will add cels into it
                row.append($("<td>" + arr[u][0] + "</td>"));
                row.append($("<td>" + arr[u][1] + "</td>"));
            }
        }
    </script>
   </body>
</html>
