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
    <html lang="zh-CN">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Cloud Account</title>
        <!-- Include the latest version of jQuery library -->
        <script type="text/javascript" src="http://libs.baidu.com/jquery/2.0.3/jquery.min.js"></script>
        <script type="text/javascript">
            function getUrlParameter(sParam)
            {
                var sPageURL = window.location.search.substring(1);
                var sURLVariables = sPageURL.split('&');
                for (var i = 0; i < sURLVariables.length; i++) 
                {
                    var sParameterName = sURLVariables[i].split('=');
                    if (sParameterName[0] == sParam) 
                    {
                        return sParameterName[1];
                    }
                }
                return "";
            }
            var resBalance = {};
            $(function() {
                var group = getUrlParameter("group");
                if(group == "")
                    return ;
                document.title = "Cloud Account - " + group;
                var url = "api/get.php?method=userBalance&group="+group+"&jsoncallback=?";
                $.getJSON(url,
                    function(dataGet) {
                        resBalance = dataGet["result"];
                        drawTable(resBalance);
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
            <table id="checkSumTable"><tr><td>checksum:</td><td id="checkSumCell">0</td></tr></table>
            <form id="formInput" action="test.php", method="POST">
                <table id="formInputTable"></table>
                <input type="button" id="postEvent" value="Submit">
                <input type="reset" id="clearTable" value="Reset">
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
                    var row = $("<tr />");
                    row.append($("<td>" + arr[u][0] + "</td>"));
                    row.append($("<td>" + arr[u][1] + "</td>"));
                    $("#balanceTable").append(row);
                }
            }
            $("#balanceTable").click(function (e) {
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
            });

            function addOneRecord(username) {
                if(addOneRecord.names.indexOf(username) != -1)
                    return;
                addOneRecord.names.push(username);
                var formInput = $("#formInput");
                var formInputTable = $("#formInputTable");
                var tr = $("<tr />");
                tr.append($("<td>" + username + "</td>"));
                var td = $("<td />");
                tr.append(td);
                td.append($("<input />", {
                    type: 'text',
                    id: 'moneyCell',
                    name: username,
                    placeholder: 'Input Money'
                    }));
                tr.append(td);
                formInputTable.append(tr);
            };
            addOneRecord.names = [];

            $("#postEvent").click(function(e)
            {
                e.preventDefault(); //STOP default action
                var formArr = $("#formInput").serializeArray();
                var postArr = {};
                var group = getUrlParameter("group");
                if(group == "")
                    return;
                postArr["action"] = "addEvent";
                postArr["group"] = group;
                postArr["records"] = {};
                for(var i in formArr){
                    var item = formArr[i];
                    var username = item["name"];
                    var money = item["value"];
                    if(username.length==0 || money.length==0 || isNaN(money))
                    {
                        alert("Uncomplete Content.");
                        return;
                    }
                    postArr["records"][username] = money;
                }
                var postData = JSON.stringify(postArr);
                console.log(postData);
                $.ajax(
                {
                    url : "api/event.php",
                    type: "POST",
                    data : postData,
                    success:function(maindta){
                        addOneRecord.names = [];
                        $("#formInputTable").empty();
                        location.reload();
                    },
                    error: function(jqXHR, textStatus, errorThrown){
                        alert("post data fail.")
                    }
                });
            });
            
            $("#clearTable").click(function(e)
            {
                e.preventDefault();
                addOneRecord.names = [];
                $("#formInputTable").empty();
            });

            $("#moneyCell").on(function(){
                alert("change");
            });
        </script>
    </body>
    </html>
<?php
}
?>
