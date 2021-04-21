
<?php
require_once 'db_creds.inc';

//Fun variable
$dreadpirateroberts = 0;


function mysqli_result($res, $row, $field=0) {
    $res->data_seek($row);
    $datarow = $res->fetch_array();
    return $datarow[$field];
}


// Connecting, selecting database
$mysqli = new mysqli('mysql.cs.uky.edu', K_USERNAME, K_PASSWORD,K_USERNAME);
if ($mysqli -> connect_errno) {
	echo("Error description: " . $mysqli -> error);
	exit();
}


function get_checking($mysqli, $name){
	$query = "SELECT * FROM accounts WHERE name=\"" . $name . "\";";
	$result = $mysqli -> query($query) or die('Query failed: ' . mysqli_error());
	return mysqli_result($result, "accounts", 1);
}


function get_savings($mysqli, $name){
	$query = "SELECT * FROM accounts WHERE name=\"" . $name . "\";";
	$result = $mysqli -> query($query) or die('Query failed: ' . mysqli_error());
	return mysqli_result($result, "accounts", 2);
}


function set_checking($mysqli, $name, $amount){
	//$mysqli->prepare($amount);
	$amount = floatval($amount);
	if($amount < 0){
		echo "<h1 style=\"color: red\"> ERROR!!!! Checking cannot be negative.</h1>";
		$dreadpirateroberts = 1;
	} else {
		$query = "UPDATE accounts SET checking=" . $amount . " WHERE name=\"" . $name . "\";";
		$prepared = $mysqli->prepare($query);
		$prepared->execute();
	}
	//$result = $mysqli -> query($query) or die('Query failed: ' . mysqli_error());
}

function set_checking_override($mysqli, $name, $amount){
	//$mysqli->prepare($amount);
	$amount = floatval($amount);
	$query = "UPDATE accounts SET checking=" . $amount . " WHERE name=\"" . $name . "\";";
	$prepared = $mysqli->prepare($query);
	$prepared->execute();
	//$result = $mysqli -> query($query) or die('Query failed: ' . mysqli_error());
}

function set_savings($mysqli, $name, $amount){
	//$mysqli->prepare($amount);
	$amount = floatval($amount);
	if($amount < 0){
		echo "<h1 style=\"color: red\"> ERROR!!!! Savings cannot be negative.</h1>";
		$dreadpirateroberts = 1;
	} else {
		$query = "UPDATE accounts SET savings=" . $amount . " WHERE name=\"" . $name . "\";";
		//$result = $mysqli -> query($query) or die('Query failed: ' . mysqli_error());
		$prepared = $mysqli->prepare($query);
		$prepared->execute();
	}
}

function set_savings_override($mysqli, $name, $amount){
	//$mysqli->prepare($amount);
	$amount = floatval($amount);
	$query = "UPDATE accounts SET savings=" . $amount . " WHERE name=\"" . $name . "\";";
	//$result = $mysqli -> query($query) or die('Query failed: ' . mysqli_error());
	$prepared = $mysqli->prepare($query);
	$prepared->execute();
}

if( $_GET["depchecking"]) {
	if(is_numeric($_GET["depchecking"]) &&
	floatval($_GET["depchecking"]) >= 0
	)
      set_checking($mysqli, "Bob", floatval($_GET["depchecking"]) + floatval(get_checking($mysqli, "Bob")));
    else{
    echo "<h1 style=\"color: ff00ff\">Nice try, dingus. Can't hack this bank. you have to enter a NUMERIC VALUE.</h1>";
	 $dreadpirateroberts = 1;
    }
}

if( $_GET["depsavings"]) {
	if(is_numeric($_GET["depsavings"]) &&
			floatval($_GET["depsavings"]) >= 0)
      set_savings($mysqli, "Bob", 
      floatval($_GET["depsavings"]) + floatval(get_savings($mysqli, "Bob"))
      );
   else{
   echo "<h1 style=\"color: ff00ff\">Nice try, dingus. Can't hack this bank. you have to enter a NUMERIC VALUE.</h1>";
	 $dreadpirateroberts = 1;
   }
}

if( $_GET["tsavings"]) {
	if(
			floatval($_GET["tsavings"]) > 0 && 
			(floatval($_GET["tsavings"]) <= floatval(get_savings($mysqli, "Bob"))) &&
			is_numeric($_GET["tsavings"])
		){
      set_checking($mysqli, "Bob", 
      floatval($_GET["tsavings"]) + floatval(get_checking($mysqli, "Bob"))
      );

      set_savings($mysqli, "Bob", 
      -1* floatval($_GET["tsavings"]) + floatval(get_savings($mysqli, "Bob"))
      );
      }else {
		echo "<h1 style=\"color: ff00ff\">ERROR!!!!! DUMB KIDS STOP TRYING TO BREAK MY BANK</h1>";
		 $dreadpirateroberts = 1;
    }
}

if( $_GET["tchecking"]) {
	if(
		floatval($_GET["tchecking"]) > 0 && 
		(floatval($_GET["tchecking"]) <= floatval(get_checking($mysqli, "Bob"))) &&
		is_numeric($_GET["tchecking"])
	){
      set_checking($mysqli, "Bob", 
      -1*floatval($_GET["tchecking"]) + floatval(get_checking($mysqli, "Bob"))
      );

      set_savings($mysqli, "Bob", 
      floatval($_GET["tchecking"]) + floatval(get_savings($mysqli, "Bob"))
      );
    } else {
echo "<h1 style=\"color: ff00ff\"> ERROR!!!!! DUMB KIDS STOP TRYING TO BREAK MY BANK</h1>";
	$dreadpirateroberts = 1;
    }
}

if( $_GET["fstart"]){
	set_checking($mysqli, "Bob", 
	      0
	      );
	set_savings($mysqli, "Bob", 
		      0
		      );
}

if( $_GET["gates"]){
	set_checking($mysqli, "Bob", 
	      999999999999.93
	);
	set_savings($mysqli, "Bob", 
		  999999999999.93
	);
}

if( $_GET["nook"]){
	set_checking_override($mysqli, "Bob", 
	      -999999999999.93
	);
	set_savings_override($mysqli, "Bob", 
		  -999999999999.93
	);
}

if($dreadpirateroberts){
	//Message from Linus
	header('Location: '.'https://youtu.be/_36yNWw_07g?t=10');
	exit();
}

//What a horrible way of preventing PHP form resubmission!
if($_GET["idiot"]
   ){
	unset($_GET);
	unset($_POST);
	echo "\nRedirecting...";
	header('Location: '.'project.php');
	exit();
}

echo "<!DOCTYPE html>";
echo "<html lang='en-US'>";
echo "<head>";
echo "<title>First Bank of HTML™</title>";
echo "<style>";
echo 	"th { padding: 3px; border: 0px; border-collapse: collapse; }";
echo 	"td { padding: 3px; border: 0px; border-collapse: collapse; }";
echo 	"table { padding: 10px; border: 2px solid blue; border-collapse: collapse; }";
echo 	"li {margin-left: 15px}";
echo 	"em { color: red;font-weight: normal }";
echo "</style>";
echo "</head>";
echo "<body>";
echo "<h1>Welcome to the First Bank of HTML™</h1>";
echo "<p>";
echo "	Where all our clients are served!<br>";
echo "<mark>This website is under construction, as is our bank.</mark>";
echo "</p>";
echo "<h2>Services offered</h2>";
echo "<form action = " . 'project.php' . " method = \"GET\">";
echo "<ol>";
echo "<li>Current Account Information";
echo "<table>";
echo "<tr> <th>checking</th> <th>savings</th> </tr>";
echo "<tr>";
echo "<td>" . get_checking($mysqli, "Bob") . "</td>";
echo "<td>" . get_savings($mysqli, "Bob") . "</td>";
echo "</tr>";
echo "</table></li>";
// Free resultset
mysqli_free_result($result);

// Closing connection
mysqli_close($link);
?>

   <li>Deposit into checking <input type = "text" name = "depchecking" /> <input type = "submit" /></li>
   <li>Deposit into savings <input type = "text" name = "depsavings" /> <input type = "submit" /></li>
   <li>Transfer from checking into savings <input type = "text" name = "tchecking" /> <input type = "submit" /></li>
   <li>Transfer from savings into checking <input type = "text" name = "tsavings" /> <input type = "submit" /></li>

</ol>
<br><br><br>
<input type = "submit" name = "fstart" value = "start fresh"> <br>
<input type = "submit" name = "gates" value = "become exorbitantly wealthy"> <br>
<input type = "submit" name = "nook" value = "mess with the IRS"> <br>
<input style = "display: none" type = "hidden" name = "idiot" value = "1">
</form>
</body>
</html>
