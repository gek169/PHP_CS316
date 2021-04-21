
<?php
require_once 'db_creds.inc';
// Connecting, selecting database
$link = mysqli_connect('mysql.cs.uky.edu', K_USERNAME, K_PASSWORD)
    or die('Could not connect: ' . mysqli_error());
echo 'Connected successfully';

mysqli_select_db($link,'dwe245') or die('Could not select database');
echo mysqli_query("use dwe245;");
// Performing SQL query
$query = "SELECT * FROM accounts;";
echo "_" . $query . "_";
$result = mysqli_query($query) or die('Query failed: ' . mysqli_error());

// Printing results in HTML
echo "<table>\n";
while ($line = mysqli_fetch_array($result, MYSQL_ASSOC)) {
    echo "\t<tr>\n";
    foreach ($line as $col_value) {
        echo "\t\t<td>$col_value</td>\n";
    }
    echo "\t</tr>\n";
}
echo "</table>\n";

// Free resultset
mysqli_free_result($result);

// Closing connection
mysqli_close($link);
?>
