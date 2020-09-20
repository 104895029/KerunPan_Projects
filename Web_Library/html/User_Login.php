<?php

  session_start();
  
  require_once 'login.php';
  $conn = new mysqli($hn, $un, $pw, $db);
  if ($conn->connect_error) die($conn->connect_error);

  if (isset($_POST['u_Id'])   &&
	  isset($_POST['u_Password']))
  {
    $u_Id   = get_post($conn, 'u_Id');
    $u_Password     = get_post($conn, 'u_Password');
	
	$query_Confirm_Account  = "SELECT * FROM `User_Profiles` WHERE userId = '$u_Id'";
		
	$result1   = $conn->query($query_Confirm_Account);
	$row1 = $result1->fetch_array(MYSQLI_NUM);
	
	if (empty($row1[0])) 
	{
		echo "Account does not exist! Please create an account!";
	}
	else
	{
		$query_Login    = "SELECT * FROM `User_Profiles` WHERE userId = '$u_Id' AND userPassword = '$u_Password'";
		$result2   = $conn->query($query_Login);
		$row2 = $result2->fetch_array(MYSQLI_NUM);
		
		if (empty($row2[0])) 
		{
			echo "Worry Password, Cannot Login";
		}
		else
		{
			echo "Login in successful";
			
			$_SESSION['current_userId'] = $u_Id;
			
			header("Location: http://pan16.myweb.cs.uwindsor.ca/60334/project/html/Main_Page.php");
		    exit();
		}
	}
  }

  echo <<<_END
  <script>
		if(window.history.replaceState)
		{
			window.history.replaceState(null, null, window.location.href);		
		}
  </script>
  <form action="User_Login.php" method="post"><pre>
      User ID: <input type="text" name="u_Id">
User Password: <input type="text" name="u_Password">              
		     <input type="submit" value="Login">
  </pre></form>
  <form action="http://pan16.myweb.cs.uwindsor.ca/60334/project/html/Add_User_Profiles.php">
    <input type="submit" value="Create Account" />
</form>
_END;
   $result1->close();
  $result2->close();
  $conn->close();
  
      function get_post($conn, $var)
  {
    return $conn->real_escape_string($_POST[$var]);
  }
?>