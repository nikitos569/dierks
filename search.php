<?php include 'password_protect.php';?>
<!DOCTYPE html>
<html>
<head>
<meta Content-Type: text/html; charset=Windows-1252 name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<style>
select{
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

.select{
    display: inline-block;
    text-align: center;
    overflow: hidden;
    font-size: 20px;
} 

form.example button {
    width: 40%;
    margin-left: 26%;
    margin-right: 27%;
    padding: 10px;
    background: #2196F3;
    color: white;
    font-size: 17px;
    border: 1px solid grey;
    border-left: none;
    cursor: pointer;
}

form.example button:hover {
    background: #0b7dda;
}

form.example::after {
    content: "";
    clear: both;
    display: table;
}
</style>
</head>
<body>

<form class="example" method="POST" style="margin:auto;max-width:600px">

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

<?php
//echo 'Current PHP version: ' . phpversion() . "<br>";
//phpinfo(INFO_MODULES);

$config     = include 'config.php';
$servername = $config['DB_HOST'];
$username   = $config['DB_USERNAME'];
$password   = $config['DB_PASSWORD'];
$dbname     = $config['DB_DATABASE'];

$findme1 = 'j';
$findme2 = 'f'; //werden im zweiten PHP Block benutzt (unten)
$findme3 = 'o';
$findme4 = 't';

try {
    $conn = new PDO("sqlsrv:server=$servername ; Database=$dbname", "$username", "$password");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (Exception $e) {
    die(print_r($e->getMessage()));
}

try {
    $sql       = "SELECT DISTINCT SUBSTRING(Anschluss, 8, 3) AS Anschluss FROM devices";
    $sql2      = "SELECT DISTINCT SUBSTRING(Anschluss, 5, 2) AS Anschluss FROM devices";
    $sql3      = "SELECT DISTINCT SUBSTRING(Anschluss, 12, 3) AS Anschluss FROM devices";
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
catch (Exception $e) {
    die(print_r($e->getMessage()));
}
?>

<select name="room">
<?php
foreach ($result2 as $row) {
    $ding = $row['Anschluss'];
    if (is_numeric($ding)) {
        echo '<option value="' . $ding . '">' . $ding . '</option>';
    }
}
?>

</select>
<select name="port">
<?php
foreach ($result as $row) {
    $ding = $row['Anschluss'];
    if (is_numeric($ding)) {
        echo '<option value="' . $ding . '">' . $ding . '</option>';
    }
}
?>
</select>


</select>
<select name="anschlusss">
<?php
foreach ($result3 as $row) {
    $ding = $row['Anschluss'];
    if (is_numeric($ding) && ($ding > 0)) {
        echo '<option value="' . $ding . '">' . $ding . '</option>';
    }
}
?>
</select>

<div class="button">
  <button type="submit">Search</button>
</div>
</form>
<br>
<div class="w3-container" style="display: inline-block; text-align: right; width: 100%">
    <a href="add.php" class="w3-button w3-indigo">Add a record</a>
</div>
<div class="w3-container" style="display: inline-block; text-align: right; width: 100%">
<a href="setup.php" class="w3-button w3-indigo">Edit configuration</a>
</div>

<div class="w3-container" style="display: inline-block; text-align: right; width: 100%">
<a href="delete.php" class="w3-button w3-indigo">Delete record</a>
</div>

<?php
$ignorefirst = 0;
echo "<a href=\"./search.php?logout=1\" style=\"margin-left: 10px\" >Logout</a>";
echo "<div class=\"config\">Current configuration:</div>";
echo "<div class=\"config\">Servername = $servername</div>";
echo "<div class=\"config\">username = $username</div>";
echo "<div class=\"config\">database name = $dbname</div>";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $select = $_POST['device'] . $_POST['building'] . "-" . $_POST['ebene'] . $_POST['room'] . "-" . $_POST['port'] . "-" . $_POST['anschlusss'];
    echo "<div style=\"margin-left:45px;\text-align:center;\" class=\"select\">$select</div>";
    do {
        try {
            $conn = new PDO("sqlsrv:server=$servername ; Database=$dbname", "$username", "$password");
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch (Exception $e) {
            die(print_r($e->getMessage()));
        }
        
        try {
            $sql = "SELECT Ziel FROM devices WHERE Anschluss LIKE ?";
            
            $getresult = $conn->prepare($sql);
            $getresult->execute(array(
                $select
            ));
            $result = $getresult->fetchColumn(0);
        }
        catch (Exception $e) {
            die(print_r($e->getMessage()));
        }
		if ($ignorefirst == 1)
		{
			echo "<div style=\"text-align:center;\" class=\"select\">&rarr; $select </div>";
		}
		else
		{
			$ignorefirst = 1;
		}
        
        $select = $result;
        $pos1   = strpos($select, 'j', 0);
        $pos2   = strpos($select, 'f', 0);
        $pos3   = strpos($select, 'o', 0);
        $pos4   = strpos($select, 't', 0);
        if (($pos1 !== FALSE) || ($pos2 !== FALSE) || ($pos3 !== FALSE) || ($pos4 !== FALSE)) {
            $pos5 = strpos($select, '1', 11);
            if ($pos5 === 11) {
				echo "<div style=\"text-align:center;\" class=\"select\">&rarr; $select </div>";
				$select = substr_replace($select, 0, '11', 1);
            } else {
				echo "<div style=\"text-align:center;\" class=\"select\">&rarr; $select </div>";
				$select = substr_replace($select, 1, '11', 1);
            }
            
        }
    } while ($select !== FALSE);
}
?>

</body>
</html>