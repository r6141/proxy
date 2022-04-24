<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

$base_url = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != "off") ? "https" : "http");
$base_url .= "://".$_SERVER['HTTP_HOST'];
$base_url .= str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);

?>

<!-- HTML HEADER -->
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>new registration</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="robots" content="noindex">
		<link rel="stylesheet" href="https://envs.net/css/css_style.css" />
		<link rel="stylesheet" href="https://envs.net/css/fork-awesome.min.css" />
	</head>
	<!-- dark/light-mode -->
	<body id="body" class="dark-mode">
	<!-- hi from <?=$user?> -->
 	
	<form action="" method="post" id="form">
            <input type="name" name="name"<br>
            <input name="formSubmit" type="submit" value="Create">
        </form>
	
	</body>
</html>

<?php
if(isset($_POST['formSubmit']))
{
	$name = $_POST['name'];
	$path = "./users/{$name}";
	mkdir("$path");
	chmod($path, 0747);
	$db = new sqlite3("{$path}/index.db");
	
        function getDatesFromRange($start, $end, $format = 'Y-m-d') {$array = array();$interval = new DateInterval('P1D');$realEnd = new DateTime($end);$realEnd->add($interval);$period = new DatePeriod(new DateTime($start), $interval, $realEnd);foreach($period as $date) { $array[] = $date->format($format); } return $array; }
	$year = date("Y");
	$nyear = date("Y", strtotime('+1 year'));
	$Date = getDatesFromRange("$year-03-01", "$nyear-03-01");
        
	$db->exec("CREATE TABLE dates(date TEXT, value INT)");

        foreach ($Date as $key=>$item){
                $db->exec("INSERT INTO dates(date, value) VALUES('{$item}', -1)");
		//echo "{$item}<br>";
	}
	
	echo "succesfully registered {$name} !<br>";
	echo '<script language="javascript"> document.getElementById("form").style.display = "none"; </script>';
	header("Location: $base_url");
        //echo $base_url;
}
?>
