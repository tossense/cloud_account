<?php
session_start();
if(!isset($_SESSION["login"]) || $_SESSION["login"] != true)
{
    header("Location:login.php?location=" . urlencode($_SERVER['REQUEST_URI']));
}
else
{
?>
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
                        drawTable(dataGet["result"]);
                    });
            });
        </script>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div id="container">
        <div id="output">
            <table id="balanceTable" align="center">
                <tr>
                    <th>User Name</th>
                    <th>Balance</th>
                </tr>
                <tr>
                    <th></th><th></th>
                </tr>
            </table>
        </div>
        <div id="inputEvent">
        <form id="formInput" action="test.php", method="POST">
            <table id="formInputTable"></table><input type="button" id="postEvent" value="Submit">
        </form>
        </div>
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
            var btable = document.getElementById("balanceTable");
            btable.onclick = function (e) {
                e = e || window.event;
                var data = [];
                var target = e.srcElement || e.target;
                if(target.nodeName === "TH")
                    return;
                while (target && target.nodeName !== "TR") {
                    target = target.parentNode;
                }
                if (target) {
                    var username = target.getElementsByTagName("td")[0];
                    addOneRecord(username.innerHTML);
                }
            };

            var addOneRecord = function(username) {
                var formInput = $("#formInput");
                var formInputTable = $("#formInputTable");
                var tr = $("<tr />");
                tr.append($("<td>" + username + "</td>"));
                var td = $("<td />");
                tr.append(td);
                td.append($("<input/>", {
                    type: 'text',
                    name: username,
                    placeholder: 'Input Money'
                    }));
                tr.append(td);
                formInputTable.append(tr);
            };

            var postEvent = document.getElementById("postEvent");
            postEvent.onclick = function(e)
            {
                e.preventDefault(); //STOP default action
                var formArr = $("#formInput").serializeArray();
                var postArr = {};
                postArr["action"] = "addEvent";
                postArr["group"] = "dataminers";
                postArr["records"] = [];
                for(var i in formArr){
                    var item = formArr[i];
                    var username = item["name"];
                    var money = item["value"];
                    var aRec = {};
                    aRec[username] = money;
                    postArr["records"].push(aRec);
                }
                var postData = JSON.stringify(postArr);
                console.log(postData);
                $.ajax(
                {
                    url : "test.php",
                    type: "POST",
                    data : postData,
                    success:function(maindta){
                        alert(maindta);
                    },
                    error: function(jqXHR, textStatus, errorThrown){
                        alert("post data fail.")
                    }
                });
            }
        </script>
    </body>
    </html>
<?php
}
?>