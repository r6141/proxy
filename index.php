<?php 

error_reporting(E_ALL);
ini_set('display_errors', '1');

$base_url = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != "off") ? "https" : "http");
$base_url .= "://".$_SERVER['HTTP_HOST'];
$base_url .= str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);

// all users
$path = './users';
$files = array_diff(scandir($path), array('.', '..'));

foreach ($files as $key=>$item){
    echo "<a href='{$base_url}users/{$item}'>{$item}</a> <br>";
}

echo "<a href='{$base_url}new.php'>new user</a>";

//$db->exec("CREATE TABLE cars(id INTEGER PRIMARY KEY, name TEXT, price INT)");
//$db->exec("INSERT INTO cars(name, price) VALUES('Audi', 52642)");
//db->exec("INSERT INTO cars(name, price) VALUES('Mercedes', 57127)");
//db->exec("INSERT INTO cars(name, price) VALUES('Skoda', 9000)");

?>

<!-- HTML HEADER -->
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>bunk calc</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="robots" content="noindex">
		<link rel="stylesheet" href="https://envs.net/css/css_style.css" />
		<link rel="stylesheet" href="https://envs.net/css/fork-awesome.min.css" />
	</head>
	<!-- dark/light-mode -->
	<body id="body" class="dark-mode">
	</body>
</html>
