
<?php
require_once 'db_creds.inc';

function mysqli_result($res, $row, $field=0) {
    $res->data_seek($row);
    $datarow = $res->fetch_array();
    return $datarow[$field];
}


// Connecting, selecting database
$mysqli = new mysqli('mysql.cs.uky.edu', K_USERNAME, K_PASSWORD,"dwe245");
if ($mysqli -> connect_errno) {
	echo("Error description: " . $mysqli -> error);
	exit();
}
//echo 'Connected successfully';

//mysqli_select_db($link,'dwe245') or die('Could not select database');
// Performing SQL query
$query = "SELECT * FROM accounts WHERE name=\"Bob\";";
//echo "_" . $query . "_";
$result = $mysqli -> query($query) or die('Query failed: ' . mysqli_error());

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

// Printing results in HTML
echo "<br>Name is " . "Bob" . "<br>";
echo "<br>Checking is " . get_checking($mysqli, "Bob") . "<br>";
echo "<br>Savings is " . get_savings($mysqli, "Bob") . "<br>";

// Free resultset
mysqli_free_result($result);

// Closing connection
mysqli_close($link);
?>
