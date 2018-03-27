<!DOCTYPE HTML>  
<html>
<head>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<style>
* {
	margin-top: 10px;
	margin-left: 10px;
    box-sizing: border-box;
}
.error {color: #FF0000;}
</style>
</head>
<body>  

<?php

// define variables and set to empty values
$password = $servernameErr = $usernameErr = $passwordErr = $dbnameErr = "";

$config = include 'config.php';
$servername = $config['DB_HOST'];
$username = $config['DB_USERNAME'];
//$password = $config['DB_PASSWORD'];
$dbname = $config['DB_DATABASE'];
echo "<div class=\"config\">Current configuration:</div>";
echo "<div class=\"config\">Servername = $servername</div>";
echo "<div class=\"config\">username = $username</div>";
echo "<div class=\"config\">database name = $dbname</div>";

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
  if (empty($_POST["name"])) 
  {
    $nameErr = "Server name is required";
  }
  else 
  {
    $config['DB_HOST'] = test_input($_POST["name"]);
  }
  
  if (empty($_POST["email"]))
  {
    $emailErr = "Email is required";
  }
  else 
  {
    $config['DB_USERNAME'] = test_input($_POST["email"]);
  }
    
  if (empty($_POST["website"]))
  {
    $config['DB_PASSWORD'] = "";
  } 
  else 
  {
    $config['DB_PASSWORD'] = test_input($_POST["website"]);
  }

  if (empty($_POST["comment"])) 
  {
    $config['DB_DATABASE'] = "";
  }
  else 
  {
    $config['DB_DATABASE'] = test_input($_POST["comment"]);
  }
  //file_put_contents('config.php', '$config = ' . var_export($config));
  file_put_contents('config.php', '<?php return ' . var_export($config, true) . ';');
  echo "<p>Successfully changed configuration!</p>";
}

function test_input($data) 
{
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>

<h2>Change Database Access Configuration</h2>
<p><span class="error">* required field.</span></p>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">  
  Database Host: <input type="text" name="name" value="<?php echo $servername;?>">
  <span class="error">* <?php echo $servernameErr;?></span>
  <br><br>
  Username: <input type="text" name="email" value="<?php echo $username;?>">
  <span class="error">* <?php echo $usernameErr;?></span>
  <br><br>
  Password: <input type="text" name="website" value="<?php echo $password;?>">
  <span class="error"><?php echo $passwordErr;?></span>
  <br><br>
  Database: <input type="text" name="comment" value="<?php echo $dbname;?>">
  <span class="error">*<?php echo $dbnameErr;?></span>
  <br><br>
  <input type="submit" name="submit" value="Change configuration">  
</form>

</body>
</html>