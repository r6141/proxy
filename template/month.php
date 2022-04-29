<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');


function drawcal($dstart, $dend) {
	$db = new SQLite3('index.db');
	$ress = $db->query("SELECT * FROM dates WHERE date BETWEEN $dstart AND $dend;");

	$i = 0;

	while ($roow = $ress->fetchArray()) {

		$daate = $roow['date'];

		$timestampp = strtotime($daate);
		$dayy = date('D', $timestampp);

		$sdatee = explode("-", $daate);

		$i += 1;

		if ( $dayy == "Sun" ) {
			break;
		}
	}

	$i = 7-$i;

	echo "<table id=\"table\" class=\"table\">\n";

	echo "<tr>\n";
	
	echo "<th><button disabled>M</button></th>\n";
	echo "<th><button disabled>T</button></th>\n";
	echo "<th><button disabled>W</button></th>\n";
	echo "<th><button disabled>T</button></th>\n";
	echo "<th><button disabled>F</button></th>\n";
	echo "<th><button disabled>S</button></th>\n";
	echo "<th><button disabled>S</button></th>\n";

	echo "</tr>\n";
	echo "<tr>\n";

	for ($x = 1; $x <= $i; $x++) {
		echo "<td> </td>\n";
	}
	
	$res = $db->query("SELECT * FROM dates WHERE date BETWEEN $dstart AND $dend;");
	
	while ($row = $res->fetchArray()) {
		$date = $row['date'];
		$val = $row['value'];

		$timestamp = strtotime($date);
		$day = date('D', $timestamp);

		$sdate = explode("-", $date);
		$dayfull = date("(D) M jS, Y", $timestamp);

		$dstart = trim($dstart,'"');
		$dend = trim($dend,'"');

		if ($val == 1) {
			echo "<td><button class=\"present\" data-dstart={$dstart} data-dend={$dend} onclick=\"showmodal('{$dayfull}', '{$date}', 'present', '$dstart', '$dend')\"> {$sdate[2]} </button> </td>\n";
		}
		if ($val == 0) {
		echo "<td><button class=\"absent\" data-dstart={$dstart} data-dend={$dend} onclick=\"showmodal('{$dayfull}', '{$date}', 'absent', '$dstart', '$dend')\"> {$sdate[2]} </button> </td>\n";
		}
		if ($val == -1) {
			echo "<td><button class=\"possible\" data-dstart={$dstart} data-dend={$dend} onclick=\"showmodal('{$dayfull}', '{$date}', '', '$dstart', '$dend')\"> {$sdate[2]} </button> </td>\n";
		}
		if ($val == -2) {
			echo "<td><button disabled\"> {$sdate[2]} </button> </td>\n";
		}
		
		if ( $day == "Sun" ) {
			echo "</tr>\n";
			echo "<tr>\n";
		}
	}
	echo "</table>\n";
	$db->close();
	unset($db);
}

function drawstats($dstart, $dend) {
	$db = new SQLite3('index.db');
	$res = $db->query("SELECT * FROM dates WHERE date BETWEEN $dstart AND $dend;");
	
	$present = 0;
	$absent = 0;
	$possible = 0;
	while ($row = $res->fetchArray()) {
		$date = $row['date'];
		$val = $row['value'];

		if ($val == 1) {
			$present += 1;
		}
		if ($val == 0) {
			$absent += 1;
		}
		if ($val == -1) {
			$possible += 1;
		}
	}

	//echo "Days Absent: {$absent}<br>\n";
	//echo "Days Present: {$present}<br>\n";
	//echo "Days Possible: {$possible}<br>\n";

	$workingdays = $absent + $present + $possible;
	$lworkingdays = round(0.75 * $workingdays);
	$possibleleave = $workingdays - $lworkingdays;
	$possibleleave = $possibleleave - $absent;

	if ($possibleleave > 0) {
		echo "You can take {$possibleleave} days off<br>\n";
	}
	
	else {
		echo "no leaves left !<br>\n";
	}

	$db->close();
	unset($db);
}

if( isset($_POST['ajax']) ){

	if (isset($_POST['val'])) {
	$val =  $_POST['val'];
	$date =  $_POST['datee'];

	$db = new SQLite3('index.db');
	$db->exec("UPDATE dates SET value = $val WHERE date = \"$date\";");
	$db->close();
	unset($db);
	}
	
	$dstart = $_POST['dstart'];
	$dstart = '"'.strval($dstart).'"';
	$dend = $_POST['dend'];
	$dend = '"'.strval($dend).'"';

	drawcal($dstart, $dend);
	echo "<hr>";
	drawstats($dstart, $dend);

 exit;
}
//drawcal('"2022-03-01"', '"2022-03-19"');
//drawstats('"2022-03-01"', '"2022-03-19"');

?>

<!-- HTML HEADER -->
<!DOCTYPE html>
<html lang="en">
        <?php include 'header.php'?>
        <!-- dark/light-mode -->
	<body id="body" class="dark-mode">

<!-- The Modal -->
<div id="myModal" class="modal">
<!-- Modal content -->
<div class="modal-content">
<div class="attendance">
      
<form class="attendance-form">
   
<p id="modal-p"></p>
	
<div class="toggle">
<input type="radio" name="status" value="present" id="present" />
<label for="present">Present</label>
<input type="radio" name="status" value="absent" id="absent" />
<label for="absent">Absent</label>
<input type="radio" name="status" value="none" id="none" />
<label for="none">Uncheck</label> 
</div>

<label> <span class="close" id="close">OK</span> </label>
<p id="dateajax"></p>
      
</form>
</div>
</div>
</div>

<script>
$(document).ready(function() {
	$(document).on("click", "#close", function() {
		var stats = $('input[name="status"]:checked').val();
		var datee = $("#dateajax").text();
		var dstart = $("#dateajax").attr("dstart");
		var dend = $("#dateajax").attr("dend");
        if (stats == 'present') { $.ajax({ type: 'post', data: {ajax: 1,val: 1,datee: datee,dstart: dstart,dend: dend}, success: function(response){ $('#main').html( response); } }); }
	else if (stats == 'absent') { $.ajax({ type: 'post', data: {ajax: 1,val: 0,datee: datee,dstart: dstart,dend: dend}, success: function(response){ $('#main').html( response); } }); }
	else if (stats == 'none') { $.ajax({ type: 'post', data: {ajax: 1,val: -1,datee: datee,dstart: dstart,dend: dend}, success: function(response){ $('#main').html( response); } }); }
	else { $.ajax({ type: 'post', data: {ajax: 1, datee: datee,dstart: dstart,dend: dend}, success: function(response){ $('#main').html( response); } }); }

    });
});
</script>

</div>
</div>

<script>

var modal = document.getElementById("myModal");
function showmodal(sdate, hdate, astatus, dstart, dend) { 
	document.getElementById("modal-p").innerHTML = sdate; 
	document.getElementById("dateajax").innerHTML = hdate;
        
	document.getElementById("dateajax").setAttribute("dstart", dstart);
	document.getElementById("dateajax").setAttribute("dend", dend);
	modal.style.display = "block";
}
var span = document.getElementsByClassName("close")[0];
span.onclick = function() { modal.style.display = "none"; }
//window.onclick = function(event) { if (event.target == modal) { modal.style.display = "none"; } }

</script>

<div id="whole">
<form action=""> 
  <select name="customers">
<?php 
$db = new SQLite3('index.db'); 
$res = $db->query("SELECT * FROM dates WHERE date LIKE '%01';"); 
while ($row = $res->fetchArray()) { 
	$firstdate = $row['date']; 
	$lastdate = date('Y-m-t', strtotime($firstdate)); 
	$month = date("F", strtotime($firstdate));
	$year = date("Y", strtotime($firstdate));
	
	$cdate = date("Y-m-d H:i:s");
	$dstart = date('Y-m-01', strtotime($cdate));

	if ($firstdate == $dstart) {
		echo "<option value=\"{$firstdate}to{$lastdate}\" selected>{$month} {$year}</option>";
	}
	else {
		echo "<option value=\"{$firstdate}to{$lastdate}\">{$month} {$year}</option>"; 
	}
}

?>
  </select>
</form><br>

<script>
$('select').on('change', function() {
	var dstring = this.value;
	var darray = dstring.split("to");
	var dstart = darray[0];
	var dend = darray[1];

        $.ajax({ type: 'post', data: {ajax: 1,dstart: dstart,dend: dend}, success: function(response){ $('#main').html( response); } });	
});
</script>
<div id="main" >

<?php 
$cdate = date("Y-m-d H:i:s");
$dstart = date('Y-m-01', strtotime($cdate));
$dstart = '"'.strval($dstart).'"';
$dend = date('Y-m-t', strtotime($cdate));
$dend = '"'.strval($dend).'"';
drawcal($dstart, $dend);
echo "<hr>";
drawstats($dstart, $dend);
?>

</div>
</div>
<?php include 'footer.php'; ?>
	</body>
</html>

