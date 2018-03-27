<!DOCTYPE html>
<html>
<head>
<meta Content-Type: text/html; charset=Windows-1252 name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<style>
select, input[type=text] {
  margin: 12px;
  border: 1px solid #111;
  background: transparent;
  width: 160px;
  padding: 5px 5px 5px 5px;
  font-size: 16px;
  border: 1px solid #ccc;
  height: 34px;
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
  background: 96% / 15% no-repeat #eee;
}

p1 {
	font-size: 24px;
	margin-left: 38%;
}

input[type=text]
{
	background: GhostWhite;
}

/* CAUTION: IE hackery ahead */
select::-ms-expand { 
    display: none; /* remove default arrow on ie10 and ie11 */
}

/* target Internet Explorer 9 to undo the custom arrow */
@media screen and (min-width:0\0) {
    select {
        background:none\9;
        padding: 5px\9;
    } 
}
body {
    font-family: Arial;
}

* {
	margin-top: 5px;
    box-sizing: border-box;
}

.config {
	 margin-left: 10px;
}

form.example button {
	width: 40%;
	margin-left: 26%;
	margin-right: 27%;
    padding: 10px;
    background: #2196F3;
    color: white;
    font-size: 19px;
    border: 1px solid grey;
    border-left: none;
    cursor: pointer;
}

form.example button:hover {
    background: #0b7dda;
}

form.example::after {
	float: left;
    content: "";
    clear: both;
    display: table;
}

</style>
</head>
<body>
<?php
//echo 'Current PHP version: ' . phpversion() . "<br>";
//phpinfo(INFO_MODULES);

include 'config.php';
$servername = $config['DB_HOST'];
$username = $config['DB_USERNAME'];
$password = $config['DB_PASSWORD'];
$dbname = $config['DB_DATABASE'];

$findme1 = 'j';
$findme2 = 'f'; //werden im zweiten PHP Block benutzt (unten)
$findme3 = 'o';
$findme4 = 't';

try
{
		$conn = new PDO( "sqlsrv:server=$servername ; Database=$dbname", "$username", "$password");
		$conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
}
	catch(Exception $e)
{
	die( print_r( $e->getMessage() ) );
}

try
{
	$sql = "SELECT DISTINCT SUBSTRING(Anschluss, 8, 3) AS Anschluss FROM devices";
	$sql2 = "SELECT DISTINCT SUBSTRING(Anschluss, 5, 2) AS Anschluss FROM devices";
	$sql3 = "SELECT DISTINCT SUBSTRING(Anschluss, 12, 3) AS Anschluss FROM devices";
	$getresult = $conn->prepare($sql);
	$getresult->execute();
	$result = $getresult->fetchAll();
	
	$getresult = $conn->prepare($sql2);
	$getresult->execute();
	$result2 = $getresult->fetchAll();
	
	$getresult = $conn->prepare($sql3);
	$getresult->execute();
	$result3 = $getresult->fetchAll();
}
	catch(Exception $e)  
{
	die( print_r( $e->getMessage() ) );   
}
?>
<form class="example" method="POST" style="margin:auto;max-width:1200px">

<select name="device">
  <option value="h">Host</option>
  <option value="s">Switch</option>
  <option value="r">Router</option>
  <option value="c">Kamera</option>
  <option value="a">Accesspoint</option>
  <option value="j">Patchfeld (junction)</option>
  <option value="f">Patchfeld (fiber)</option>
  <option value="o">Netzwerkdose</option>
  <option value="t">Patchfeld (telephone)</option>
  <option value="p">Drucker</option>
  <option value="m">Server</option>
  <option value="b">Beamer</option>
</select>

<select name="building">
  <option value="1">Hauptgebäude</option>
  <option value="2">Mensa</option>
  <option value="3">Sporthalle</option>
  <option value="4">Lamspringe</option>
</select>

<select name="ebene">
  <option value="1">Ebene 1</option>
  <option value="2">Ebene 2</option>
  <option value="3">Ebene 3</option>
  <option value="4">Ebene 4</option>
</select>

<select name="device2">
  <option value="h">Host</option>
  <option value="s">Switch</option>
  <option value="r">Router</option>
  <option value="c">Kamera</option>
  <option value="a">Accesspoint</option>
  <option value="j">Patchfeld (junction)</option>
  <option value="f">Patchfeld (fiber)</option>
  <option value="o">Netzwerkdose</option>
  <option value="t">Patchfeld (telephone)</option>
  <option value="p">Drucker</option>
  <option value="m">Server</option>
  <option value="b">Beamer</option>
</select>

<select name="building2">
  <option value="1">Hauptgebäude</option>
  <option value="2">Mensa</option>
  <option value="3">Sporthalle</option>
  <option value="4">Lamspringe</option>
</select>

<select name="ebene2">
  <option value="1">Ebene 1</option>
  <option value="2">Ebene 2</option>
  <option value="3">Ebene 3</option>
  <option value="4">Ebene 4</option>
</select>

<select name="room">
<?php
foreach ($result2 as $row)
{
	$ding = $row['Anschluss'];
	if (is_numeric($ding))
	{
		echo '<option value="'.$ding.'">'.$ding.'</option>';
	}
}
?>
</select>

<select name="port">
<?php
foreach ($result as $row)
{
	$ding = $row['Anschluss'];
	if (is_numeric($ding))
	{
		echo '<option value="'.$ding.'">'.$ding.'</option>';
	}
}
?>
</select>

</select>
<select name="anschlusss">
<?php
foreach ($result3 as $row)
{
	$ding = $row['Anschluss'];
	if (is_numeric($ding) && ($ding > 0))
	{
		echo '<option value="'.$ding.'">'.$ding.'</option>';
	}
}
?>
</select>

<input type="text" name="room2" size="15" placeholder="Raum" pattern="[0-9]{2,}" required>
<input type="text" name="port2" size="15" placeholder="Port" pattern="[0-9]{3}" required>
<input type="text" name="anschlusss2" size="15" placeholder="Anschluss" pattern="[0-9]{3}" required>


<div class="button" style="display: inline-block; text-align: right; width: 100%">
  <button type="submit">Add a record</button>
</div>
</form>
<br>

<div class="w3-container" style="display: inline-block; text-align: right; width: 100%">
<a href="pdo demo.php" class="w3-button w3-indigo">Search for record</a>
</div>

<div class="w3-container" style="display: inline-block; text-align: right; width: 100%">
<a href="edit.php" class="w3-button w3-indigo">Edit configuration</a>
</div>

<?php
echo "<div class=\"config\">Current configuration:</div>";
echo "<div class=\"config\">Servername = $servername</div>";
echo "<div class=\"config\">username = $username</div>";
echo "<div class=\"config\">database name = $dbname</div>";
$select = $_POST['device'] . $_POST['building'] . "-" . $_POST['ebene'] . $_POST['room'] . "-" . $_POST['port'] . "-" . $_POST['anschlusss'];
$select2 = $_POST['device2'] . $_POST['building2'] . "-" . $_POST['ebene2'] . $_POST['room2'] . "-" . $_POST['port2'] . "-" . $_POST['anschlusss2'];
try  
{
$conn = new PDO( "sqlsrv:server=$servername ; Database=$dbname", "$username", "$password");
$conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
}
catch(Exception $e)
{
die( print_r( $e->getMessage() ) );
}

try
{
$sql = "INSERT INTO devices (Anschluss, Ziel)
        VALUES (?,?)";

$getresult = $conn->prepare($sql);
$getresult->execute([$select, $select2]);
echo "<p1>Successfully added new record!</p1>";
echo "<br>";
echo "<p1>$select &rarr; $select2</p1>";
}
catch(Exception $e)  
{
die( print_r( $e->getMessage() ) );   
}
?>
</body>
</html>