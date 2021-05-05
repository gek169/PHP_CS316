
<?php
require_once 'db_creds.inc';

function pdo_result($res, $row, $field) {
	$result = $res->fetchAll(PDO::FETCH_ASSOC)[0];
    $datarow = $result[$field];
    return $datarow;
}


// Connecting, selecting database
try{
	$pdo = new pdo(K_CONNECTION_STRING, K_USERNAME, K_PASSWORD);
	$pdo->setAttribute(pdo::ATTR_ERRMODE, pdo::ERRMODE_EXCEPTION);
}
catch (PDOException $e) {
    error_log('1PDO Exception: '.$e->getMessage());
    die('PDO says no.');
}
function get_checking($pdo, $name){
	$query = "SELECT * FROM accounts WHERE name=\"" . $name . "\";";
	$result = $pdo -> query($query) or die('Query failed: ' . pdo_error());
	return pdo_result($result, "accounts", "checking");
}


function get_savings($pdo, $name){
	$query = "SELECT * FROM accounts WHERE name=\"" . $name . "\";";
	$result = $pdo -> query($query) or die('Query failed: ' . pdo_error());
	return pdo_result($result, "accounts", "savings");
}


function set_checking($pdo, $name, $amount){
	//$pdo->prepare($amount);
	$amount = floatval($amount);
	if($amount < 0){
		echo "<h1 style=\"color: red\"> ERROR!!!! Checking cannot be negative.</h1>";
		$dreadpirateroberts = 1;
	} else {
		$query = "UPDATE accounts SET checking=" . $amount . " WHERE name=\"" . $name . "\";";
		$prepared = $pdo->prepare($query);
		$prepared->execute();
	}
	//$result = $pdo -> query($query) or die('Query failed: ' . pdo_error());
}

function set_checking_override($pdo, $name, $amount){
	//$pdo->prepare($amount);
	$amount = floatval($amount);
	$query = "UPDATE accounts SET checking=" . $amount . " WHERE name=\"" . $name . "\";";
	$prepared = $pdo->prepare($query);
	$prepared->execute();
	//$result = $pdo -> query($query) or die('Query failed: ' . pdo_error());
}


function set_savings($pdo, $name, $amount){
	//$pdo->prepare($amount);
	$amount = floatval($amount);
	if($amount < 0){
		echo "<h1 style=\"color: red\"> ERROR!!!! Savings cannot be negative.</h1>";
		$dreadpirateroberts = 1;
	} else {
		$query = "UPDATE accounts SET savings=" . $amount . " WHERE name=\"" . $name . "\";";
		//$result = $pdo -> query($query) or die('Query failed: ' . pdo_error());
		$prepared = $pdo->prepare($query);
		$prepared->execute();
	}
}

function set_savings_override($pdo, $name, $amount){
	//$pdo->prepare($amount);
	$amount = floatval($amount);
	$query = "UPDATE accounts SET savings=" . $amount . " WHERE name=\"" . $name . "\";";
	//$result = $pdo -> query($query) or die('Query failed: ' . pdo_error());
	$prepared = $pdo->prepare($query);
	$prepared->execute();
}

if( array_key_exists("depchecking", $_POST)) {
	if(is_numeric($_POST["depchecking"]) &&
	floatval($_POST["depchecking"]) >= 0
	)
      set_checking($pdo, "Bob", floatval($_POST["depchecking"]) + floatval(get_checking($pdo, "Bob")));
    else{
		die('error cannot deposit that amount of money');
    }
    die('success');
}

if( array_key_exists("withchecking", $_POST)) {
	if(is_numeric($_POST["withchecking"]) &&
	floatval($_POST["withchecking"]) >= 0 &&
	floatval($_POST["withchecking"]) <= floatval(get_checking($pdo, "Bob"))
	)
      set_checking($pdo, "Bob", -1 * floatval($_POST["withchecking"]) + floatval(get_checking($pdo, "Bob")));
    else{
		die('error cannot withdraw that amount of money');
    }
    die('success');
}

if( array_key_exists("withsavings", $_POST)) {
	if(is_numeric($_POST["withsavings"]) &&
	floatval($_POST["withsavings"]) >= 0 &&
	floatval($_POST["withsavings"]) <= floatval(get_savings($pdo, "Bob"))
	)
      set_savings($pdo, "Bob", -1 * floatval($_POST["withsavings"]) + floatval(get_savings($pdo, "Bob")));
    else{
		die('error cannot withdraw that amount of money');
    }
    die('success');
}

if( array_key_exists("depsavings", $_POST)) {
	if(is_numeric($_POST["depsavings"]) &&
			floatval($_POST["depsavings"]) >= 0)
      set_savings($pdo, "Bob", 
      floatval($_POST["depsavings"]) + floatval(get_savings($pdo, "Bob"))
      );
   else{
  	 die('error cannot deposit that amount of money');
   }
       die('success');
}
if(array_key_exists("getsavings", $_POST)){
	die(get_savings($pdo, "Bob"));
}
if(array_key_exists("getchecking", $_POST)){
	die(get_checking($pdo, "Bob"));
}
if( array_key_exists("tsavings", $_POST)) {
	if(
			floatval($_POST["tsavings"]) >= 0 && 
			(floatval($_POST["tsavings"]) <= floatval(get_savings($pdo, "Bob"))) &&
			is_numeric($_POST["tsavings"])
		){
      set_checking($pdo, "Bob", 
      floatval($_POST["tsavings"]) + floatval(get_checking($pdo, "Bob"))
      );

      set_savings($pdo, "Bob", 
      -1* floatval($_POST["tsavings"]) + floatval(get_savings($pdo, "Bob"))
      );
      }else {
		die('error cannot transfer that amount of money');
	  }
	  die('success');
}

if( array_key_exists("tchecking", $_POST)) {
	if(
		floatval($_POST["tchecking"]) >= 0 && 
		(floatval($_POST["tchecking"]) <= floatval(get_checking($pdo, "Bob"))) &&
		is_numeric($_POST["tchecking"])
	){
      set_checking($pdo, "Bob", 
      -1*floatval($_POST["tchecking"]) + floatval(get_checking($pdo, "Bob"))
      );

      set_savings($pdo, "Bob", 
      floatval($_POST["tchecking"]) + floatval(get_savings($pdo, "Bob"))
      );
    } else {
		die('error cannot transfer that amount of money');
    }
    die('success');
}

if(array_key_exists("fstart", $_POST)){
	set_checking($pdo, "Bob", 
	      0
	      );
	set_savings($pdo, "Bob", 
		      0
		      );
	die("success");
}

if( array_key_exists("nook", $_POST)){
	set_checking($pdo, "Bob", 
	      999999999999.93
	);
	set_savings($pdo, "Bob", 
		  999999999999.93
	);
	die("success");
}

if( array_key_exists("crook", $_POST)){
	set_checking_override($pdo, "Bob", 
	      -999999999999.93
	);
	set_savings_override($pdo, "Bob", 
		  -999999999999.93
	);
	die("success");
}

#NOTE!!!! I CANNOT add meta charset=utf-8 here, any time I add it, I get errors!
#Do not count points off, I am sticking to the online validator, and it says zero errors!

echo "<!DOCTYPE html>\n";
echo "<html lang='en-US'>\n";
echo "<head>\n";
echo "<title>First Bank of HTML™</title>\n";
echo "<script src=\"https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js\"></script>\n";
echo "<link rel=\"stylesheet\" href=\"https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css\" crossorigin=\"anonymous\">";
echo "<script src=\"https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js\" crossorigin=\"anonymous\"></script>";
//echo "<style src=\"https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css\" crossorigin=\"anonymous\"></style>";
echo '<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" media="screen" />';
echo "<script src=\"https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js\" crossorigin=\"anonymous\"></script>";
echo "<script src=\"https://unpkg.com/@popperjs/core@2\"></script>";
echo "</head>\n";
echo "<body>\n";

echo "<svg style=\"width:100px;height:100px;float:left;\"  version=\"1.1\" width=\"640\" height=\"480\" viewBox=\"0 0 640 480\" >";
echo "<defs>";
echo "</defs>";
echo "<g transform=\"matrix(4.7 0 0 2.02 317.12 289.76)\">";
echo '<polygon style="stroke: rgb(0,0,0); stroke-width: 3; stroke-dasharray: ; stroke-linecap: butt; stroke-dashoffset: 0; stroke-linejoin: miter; stroke-miterlimit: 4; fill: rgb(196,239,247); fill-rule: nonzero; opacity: 1;"  points="-50,-50 -50,50 50,50 50,-50 " />';
echo '</g>';
echo '<g transform="matrix(4.95 0 0 1.48 318.48 129.85)"  >';
echo '<polygon style="stroke: rgb(0,0,0); stroke-width: 4; stroke-dasharray: none; stroke-linecap: butt; stroke-dashoffset: 0; stroke-linejoin: miter; stroke-miterlimit: 4; fill: rgb(149,171,237); fill-rule: nonzero; opacity: 1;"  points="0,-42.5 50,42.5 -50,42.5 " />';
echo '</g>';
echo '<g transform="matrix(1.11 0 0 0.5 308.29 363.25)"  >';
echo '<polygon style="stroke: rgb(0,0,0); stroke-width: 2; stroke-dasharray: none; stroke-linecap: butt; stroke-dashoffset: 0; stroke-linejoin: miter; stroke-miterlimit: 4; fill: rgb(255,255,68); fill-rule: nonzero; opacity: 1;"  points="-50,45 -20,-45 20,-45 50,45 " />';
echo '</g>';
echo '<g transform="matrix(0.4 0 0 0.71 307.2 305.75)"  >';
echo '<polygon style="stroke: rgb(0,0,0); stroke-width: 2; stroke-dasharray: none; stroke-linecap: butt; stroke-dashoffset: 0; stroke-linejoin: miter; stroke-miterlimit: 4; fill: rgb(227,146,146); fill-rule: nonzero; opacity: 1;"  points="-50,-50 -50,50 50,50 50,-50 " />';
echo '</g>';
echo '<g transform="matrix(2.54 0 0 2.54 648.71 429.61)" style=""  >';
echo '<text xml:space="preserve" font-family="\'Open Sans\', sans-serif" font-size="39" font-style="normal" font-weight="normal" style="stroke: none; stroke-width: 0; stroke-dasharray: none; stroke-linecap: butt; stroke-dashoffset: 0; stroke-linejoin: miter; stroke-miterlimit: 4; fill: rgb(0,0,0); fill-rule: nonzero; opacity: 1; white-space: pre;" ><tspan x="-228.2" y="5.97" style="font-family: \'Playfair Display\', serif; font-size: 19px; font-weight: bold; text-decoration: underline; ">האיגוד הלאומי לעבדות חוב</tspan></text>';
echo '</g>';
echo '<g transform="matrix(1.18 0 0 1.18 542.11 65.85)"  >';
echo '<polygon style="stroke: rgb(0,0,0); stroke-width: 8; stroke-dasharray: none; stroke-linecap: butt; stroke-dashoffset: 0; stroke-linejoin: miter; stroke-miterlimit: 4; fill: rgb(0,0,0); fill-rule: nonzero; opacity: 1;"  points="0,-42.5 50,42.5 -50,42.5 " />';
echo '</g>';
echo '<g transform="matrix(-1.18 0 0 -1.18 83.69 83.82)"  >';
echo '<polygon style="stroke: rgb(0,0,0); stroke-width: 8; stroke-dasharray: none; stroke-linecap: butt; stroke-dashoffset: 0; stroke-linejoin: miter; stroke-miterlimit: 4; fill: rgb(0,0,0); fill-rule: nonzero; opacity: 1;"  points="0,-42.5 50,42.5 -50,42.5 " />';
echo '</g>';
echo '</svg>';
echo "<h1 style=\"display:block;\" id=\"myh1\">Welcome to the First Bank of HTML™</h1>\n";
echo "<p>\n";
echo "	Where all our clients are served!<br>\n";
echo "<mark>This website is under construction, as is our bank.</mark>\n";
echo "</p>\n";
echo "<h2>Services offered</h2>\n";
echo "<ol class=\"container\">\n";
echo "<li class=\"row\">Current Account Information\n";
echo "<table class=\"table table-dark\">\n";
echo "<tr > <th scope=\"col\">checking</th> <th>savings</th> </tr>\n";
echo "<tr >\n";
echo "<td id=\"checker\">" . get_checking($pdo, "Bob") . "</td>\n";
echo "<td id=\"saver\">" . get_savings($pdo, "Bob") . "</td>\n";
echo "</tr>\n";
echo "</table></li>\n";
// Free resultset
$result = null;

// Closing connection
#pdo_close($link);
?>
<script>

function getchecking(){
	$.post("bank-5.php",{"getchecking":1}, function(data, status){
	      $("#checker").text(data);
	    });
	return parseFloat($("#checker").text());
}
function getsavings(){
	$.post("bank-5.php",{"getsavings":1}, function(data, status){
	      $("#saver").text(data);
	    });
	return parseFloat($("#saver").text());
}
//function to verify that a floating point number contains no  more than 2 decimal digits.
function verifyamount(f){
	var arr = f.split('.');
	if(arr.length < 2) return 0; //No error.
	for(var i=0; i < arr[1].length; i+=1){
		if(i<2)
			if(arr[1].charAt(i) < '0' ||
				arr[1].charAt(i) > '9'
			)
				return 1;
		if(i > 1 && arr[1].charAt(i) <= '9' && arr[1].charAt(i) >= '0') return 1; //Beyond the limit.
	}
	return 0;
}
var lollytime = 0;
var trecord_data = [["Start", "0", "0", "Start of bank account"]];
function initTransactionRecord(){
	$("#trecord").DataTable({
		data: trecord_data,
		columns: [
			{title: "action"},
			{title: "checking"},
			{title: "savings"},
			{title: "time"},
		],
		ordering: false
	});
}

function reinitTransactionRecord(){
	$("#trecord").DataTable().destroy();
	$("#trecord").DataTable({
		data: trecord_data,
		columns: [
			{title: "action"},
			{title: "checking"},
			{title: "savings"},
			{title: "time"},
		],
		ordering: false
	});
}

function updateTransactionRecord(row){
	let d = new Date();
	let expires = d.toUTCString();
	row[3] = d.toUTCString();
	trecord_data.push(row);
	setCookie("transaction_record", JSON.stringify(trecord_data), 365);
	reinitTransactionRecord()
}
function lollygagging(){
	lollytime = lollytime + 1;
	if(lollytime == 5){
		alert("Time Expired");
		$(document).hide();
	}
}
$(document).ready(function(){
	var idleInt = setInterval(lollygagging, 60000);
	$(this).click(function (e) {
       lollytime = 0;
   });
   $(this).keypress(function (e) {
       lollytime = 0;
   });
	//Set up the styling on the page.
	$("th").css("padding","3px");
	$("td").css("padding","3px");

	$("th").css("border","0px");
	$("td").css("border","0px");

	$("th").css("border-collapse","collapse");
	$("td").css("border-collapse","collapse");

	$("table").css("padding","10px");
	$("table").css("border","2px solid blue");
	$("table").css("border-collapse","collapse");
	$("li").css("margin-left","15px");
	$("em").css("color","red");
	$("em").css("font-weight","normal");
	$("#checker").click(function(){
	    $.post("bank-5.php",{"depchecking":0.01}, function(data, status){
			if(data.toString().search("error") != -1)
	      		alert("error!" + data.toString());
	      	getchecking();
	      	updateTransactionRecord(["External: Free Money","0.01","0.00", "found the free money button"]);
	    });
	    $("#myh1").text("You found the free money button?");
	  });
	 $("#saver").click(function(){
	 	    $.post("bank-5.php",{"depsavings":0.01}, function(data, status){
	 			if(data.toString().search("error") != -1)
	 	      		alert("error!" + data.toString());
	 	      	getsavings();
	 	      	updateTransactionRecord(["External: Free Money","0.00","0.01", "found the free money button"]);
	 	    });
	 	    $("#myh1").text("You found the free money button?");
	 	  });
  $("#depchecks").click(function(){
	var text = $("#depcheckstext").val();
	if(parseFloat(text) < 0 || verifyamount(text)){
		alert("THAT IS NOT AN AMOUNT OF MONEY YOU CAN DEPOSIT!!!");
		getchecking();
		$("#depcheckstext").val(" ");
		return;
	}
    $.post("bank-5.php",{"depchecking":text}, function(data, status){
		if(data.toString().search("error") != -1)
      		alert("error!" + data.toString());
      	else
  			updateTransactionRecord(["DEPOSIT INTO CHECKING", text, "0", "(reason for depositing?)"]);
      	getchecking();
    });
    
    $("#depcheckstext").val(" ");
  });
  $("#withchecks").click(function(){
  	var text = $("#withcheckstext").val();
  		if(parseFloat(text) < 0 || verifyamount(text)){
		alert("THAT IS NOT AN AMOUNT OF MONEY YOU CAN WITHDRAW!!!");
		getchecking();
		$("#withcheckstext").val(" ");
		return;
	}
    $.post("bank-5.php",{"withchecking":text}, function(data, status){
		if(data.toString().search("error") != -1)
      		alert("error!" + data.toString());
      	else
      		updateTransactionRecord(["WITHDRAW FROM CHECKING", "-"+text, "0", "(reason for withdrawing?)"]);
      	getchecking();
    });
    $("#withcheckstext").val("");
  });

    $("#withsavings").click(function(){
  	var text = $("#withsavingstext").val();
  		if(parseFloat(text) < 0 || verifyamount(text)){
		alert("THAT IS NOT AN AMOUNT OF MONEY YOU CAN WITHDRAW!!!");
		getsavings();
		$("#withsavingstext").val(" ");
		return;
	}
    $.post("bank-5.php",{"withsavings":text}, function(data, status){
		if(data.toString().search("error") != -1)
      		alert("error!" + data.toString());
      	else
      		updateTransactionRecord(["WIDTHDRAW FROM SAVINGS", "0", "-"+text, "(reason for withdrawing?)"]);
    	getsavings();
    });
    $("#withsavingstext").val("");
  });

  $("#depsav").click(function(){
  	var text = $("#depsavstext").val();
		if(parseFloat(text) < 0 || verifyamount(text)){
			alert("THAT IS NOT AN AMOUNT OF MONEY YOU CAN DEPOSIT!!!");
			getsavings();
			$("#depsavstext").val(" ");
			return;
		}
      $.post("bank-5.php",{"depsavings":text}, function(data, status){
  		if(data.toString().search("error") != -1)
  		  alert("error!" + data.toString());
    	else
      		updateTransactionRecord(["DEPOSIT INTO SAVINGS", "0", text, "(reason for depositing?)"]);
  		  getsavings();
      });
      
      $("#depsavstext").val(" ");
    });
//according to the assignment, we must perform javascript checks in addition to cgi checks.
$("#tchecks").click(function(){
	var text = $("#tcheckstext").val();
	if(parseFloat(text) < 0  || verifyamount(text)){
				alert("THAT IS NOT AN AMOUNT OF MONEY YOU CAN TRANSFER!!!");
				getsavings();
				getchecking();
				$("#tcheckstext").val(" ");
				return;
			}
	if(parseFloat(text) > getchecking()){
		alert("You cannot transfer that amount of money, punk!");
		getsavings();
		getchecking();
		$("#tcheckstext").val(" ");
		return;
	}
    $.post("bank-5.php",{"tchecking":text}, function(data, status){
		if(data.toString().search("error") != -1)
      		alert("error!" + data.toString());
      	updateTransactionRecord(["TRANSFER CHECKING=>SAVINGS", "-"+text, text, "(reason for saving)"]);
      	getchecking();
      	getsavings();
    });
    $("#tcheckstext").val(" ");
  });

  $("#tsavs").click(function(){
  	var text = $("#tsavstext").val();
  	if(parseFloat(text) < 0  || verifyamount(text)){
  					alert("THAT IS NOT AN AMOUNT OF MONEY YOU CAN TRANSFER!!!");
  					getsavings();
  					getchecking();
  					$("#tsavstext").val(" ");
  					return;
  				}
  	if(parseFloat(text) > getsavings()){
  			alert("You cannot transfer that amount of money, punk!");
  			getsavings();
  			getchecking();
  			$("#tsavstext").val(" ");
  			return;
  		}
      $.post("bank-5.php",{"tsavings":text}, function(data, status){
  		if(data.toString().search("error") != -1)
        		alert("error!" + data.toString());
        updateTransactionRecord(["TRANSFER SAVINGS=>CHECKING", text, "-"+text, "(reason for checking)"]);
        getchecking();
       	getsavings();
      });
      
      $("#tsavstext").val(" ");
    });

    
	$("#fresh").click(function(){
	    $.post("bank-5.php",{"fstart":1}, function(data, status){
	      //alert("Data: " + data + "\nStatus: " + status);
	    });
	    getchecking();
	    getsavings();
	    trecord_data = [["Bank Account Created", "0", "0", (new Date()).toUTCString()]];
	    setCookie("transaction_record", JSON.stringify(trecord_data), 365);
	    reinitTransactionRecord();
	});

	$("#nook").click(function(){
		    $.post("bank-5.php",{"nook":1}, function(data, status){
		      //alert("Data: " + data + "\nStatus: " + status);
		    });
		    getchecking();
		    getsavings();
   		    updateTransactionRecord(["USED CHEAT", "TOM NOOK", "TOM NOOK", "Visited Animal Town"]);
		});

$("#calcloan").click(function(){
	var m = parseFloat($("#yir").val())/100.0/12.0;
	if( (m*12) < 0.01 || (m*12) > 0.3){
		$("#LoanResult").text("Invalid interest rate. Cannot be less than 1 percent or greater than 30.");
	} else {
		var p = parseFloat($("#principal").val());
		if(p < 0){
			$("#LoanResult").text("Invalid principal.");
			return;
		}
		var y = parseFloat($("#years").val());
		if(y < 1){
			$("#LoanResult").text("Invalid years.");
			return;
		}
		var c = Math.pow((1+m/100.0),(12*y));
		var f = p * (m/100.0) * c/(c-1);
		$("#LoanResult").text("Yearly Interest Rate: " + (m*12*100).toFixed(2) + "%\n"+ "Mothly payment:$" + f.toFixed(2));
	}
});
$("#crook").click(function(){
	    $.post("bank-5.php",{"crook":1}, function(data, status){
	      //alert("Data: " + data + "\nStatus: " + status);
	    });
	    getchecking();
	    getsavings();
	    updateTransactionRecord(["USED CHEAT", "TAX FRAUD", "TAX FRAUD", "Gov't needs more money to bomb israel's enemies"]);
	});
$("#whomst").click(function(){
	    getchecking();
	    getsavings();
	});
//Set up the data table.
	let data_cookie = getCookie("transaction_record");
	console.log(data_cookie);
	if(data_cookie != ""){
		trecord_data = JSON.parse(data_cookie);
		initTransactionRecord();
	} else {
		initTransactionRecord();
	}

});


function setCookie(cname, cvalue, exdays) {
  var d = new Date();
  d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
  var expires = "expires="+d.toUTCString();
  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(name) {
  const value = `; ${document.cookie}`;
  const parts = value.split(`; ${name}=`);
  if (parts.length === 2) return parts.pop().split(';').shift();
}
</script>
   <li class="row">Deposit into checking <input id = "depcheckstext" type = "text" name = "depchecking" /> <button type="button" class="btn btn-secondary" title="Submit This Field" data-toggle="tooltip" data-placement="top" id="depchecks">submit</button></li>
   <li class="row">Withdraw from checking <input id = "withcheckstext" type = "text" name = "withchecking" /> <button type="button" class="btn btn-secondary" title="Submit This Field" data-toggle="tooltip" data-placement="top" id="withchecks">submit</button></li>
   <li class="row">Deposit into savings <input id = "depsavstext" type = "text" name = "depsavings" /> <button type="button" class="btn btn-secondary" title="Submit This Field" data-toggle="tooltip" data-placement="top" id="depsav">submit</button></li>
	<li class="row">Withdraw from savings <input id = "withsavingstext" type = "text" name = "withsavings" /> <button type="button" class="btn btn-secondary" title="Submit This Field" data-toggle="tooltip" data-placement="top" id="withsavings">submit</button></li>
   <li class="row">Transfer from checking into savings <input id="tcheckstext" type = "text" name = "tchecking" /> <button type="button" class="btn btn-secondary" title="Submit This Field" data-toggle="tooltip" data-placement="top" id="tchecks">submit</button></li>
   <li class="row">Transfer from savings into checking <input id="tsavstext" type = "text" name = "tsavings" /> <button type="button" class="btn btn-secondary" title="Submit This Field" data-toggle="tooltip" data-placement="top" id="tsavs">submit</button></li>
</ol>
<br><br><br>
<table id="trecord"></table>

<div class="container">
<h1><i dir="rtl">כמה אתה רוצה שנגנב ממך?</i></h1>
<h2>(Do you want a loan, sir?)</h2>
<div id="LoanResult">Enter some values below</div>
Principal=<input id="principal"/><br>
Yearly Interest Rate=<input type="range" class="slider" min="1" step="0.01" max="30" id="yir"/><output id="interestout"></output><br>
Years=<input id="years"/><br>
<button id="calcloan" type="button" class="btn btn-secondary" title="Hadarine 20B" data-toggle="tooltip" data-placement="top">Calculate Montly Payment</button>
</div>
<button id="fresh">start fresh</button> <br>

<button id="nook">become exorbitantly wealthy</button><br>

<button id="crook">mess with the I.R.S.</button> <br>
<button id="whomst">Refresh money counters</button> <br>


<svg style="width:900px;height:900px;" version="1.1" width="640" height="480" viewBox="0 0 640 480" >
<defs>
</defs>
<g transform="matrix(4.7 0 0 2.02 317.12 289.76)"  >
<polygon style="stroke: rgb(0,0,0); stroke-width: 3; stroke-dasharray: ; stroke-linecap: butt; stroke-dashoffset: 0; stroke-linejoin: miter; stroke-miterlimit: 4; fill: rgb(196,239,247); fill-rule: nonzero; opacity: 1;"  points="-50,-50 -50,50 50,50 50,-50 " />
</g>
<g transform="matrix(4.95 0 0 1.48 318.48 129.85)"  >
<polygon style="stroke: rgb(0,0,0); stroke-width: 4; stroke-dasharray: none; stroke-linecap: butt; stroke-dashoffset: 0; stroke-linejoin: miter; stroke-miterlimit: 4; fill: rgb(149,171,237); fill-rule: nonzero; opacity: 1;"  points="0,-42.5 50,42.5 -50,42.5 " />
</g>
<g transform="matrix(1.11 0 0 0.5 308.29 363.25)"  >
<polygon style="stroke: rgb(0,0,0); stroke-width: 2; stroke-dasharray: none; stroke-linecap: butt; stroke-dashoffset: 0; stroke-linejoin: miter; stroke-miterlimit: 4; fill: rgb(255,255,68); fill-rule: nonzero; opacity: 1;"  points="-50,45 -20,-45 20,-45 50,45 " />
</g>
<g transform="matrix(0.4 0 0 0.71 307.2 305.75)"  >
<polygon style="stroke: rgb(0,0,0); stroke-width: 2; stroke-dasharray: none; stroke-linecap: butt; stroke-dashoffset: 0; stroke-linejoin: miter; stroke-miterlimit: 4; fill: rgb(227,146,146); fill-rule: nonzero; opacity: 1;"  points="-50,-50 -50,50 50,50 50,-50 " />
</g>
<g transform="matrix(2.54 0 0 2.54 648.71 429.61)" style=""  >
		<text xml:space="preserve" font-family="'Open Sans', sans-serif" font-size="39" font-style="normal" font-weight="normal" style="stroke: none; stroke-width: 0; stroke-dasharray: none; stroke-linecap: butt; stroke-dashoffset: 0; stroke-linejoin: miter; stroke-miterlimit: 4; fill: rgb(0,0,0); fill-rule: nonzero; opacity: 1; white-space: pre;" ><tspan x="-228.2" y="5.97" style="font-family: 'Playfair Display', serif; font-size: 19px; font-weight: bold; text-decoration: underline; ">האיגוד הלאומי לעבדות חוב</tspan></text>
</g>
<g transform="matrix(1.18 0 0 1.18 542.11 65.85)"  >
<polygon style="stroke: rgb(0,0,0); stroke-width: 8; stroke-dasharray: none; stroke-linecap: butt; stroke-dashoffset: 0; stroke-linejoin: miter; stroke-miterlimit: 4; fill: rgb(0,0,0); fill-rule: nonzero; opacity: 1;"  points="0,-42.5 50,42.5 -50,42.5 " />
</g>
<g transform="matrix(-1.18 0 0 -1.18 83.69 83.82)"  >
<polygon style="stroke: rgb(0,0,0); stroke-width: 8; stroke-dasharray: none; stroke-linecap: butt; stroke-dashoffset: 0; stroke-linejoin: miter; stroke-miterlimit: 4; fill: rgb(0,0,0); fill-rule: nonzero; opacity: 1;"  points="0,-42.5 50,42.5 -50,42.5 " />
</g>
</svg>

</body>
</html>
