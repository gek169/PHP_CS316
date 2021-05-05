
<?php
require_once 'db_creds.inc';

/* If started from the command line, wrap parameters */
/*
if (!isset($_SERVER["HTTP_HOST"])) {
        parse_str($argv[1], $_GET);
        parse_str($argv[1], $_POST);
        parse_str($argv[1], $_REQUEST);
}*/
$pdo = new pdo(K_CONNECTION_STRING, K_USERNAME, K_PASSWORD);
$pdo->setAttribute(pdo::ATTR_ERRMODE, pdo::ERRMODE_EXCEPTION);

echo "
<!DOCTYPE html>
<html lang='en-US'>
<head>
<title>First Bank of HTML™</title>
<style>
em{
color: red;font-weight: normal
}
</style>
</head>
<body>



<h1>Welcome to the First Bank of HTML™</h1>
<p>
Where all our clients are served!<br>
<mark>This website is under construction, as is our bank.</mark>
</p>
<h2>Services offered</h2>




</body>
</html>
"
?>
