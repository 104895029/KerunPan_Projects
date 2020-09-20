<?php
  require_once 'login.php';
  $conn = new mysqli($hn, $un, $pw, $db);
  if ($conn->connect_error) die($conn->connect_error);

  if (isset($_POST['u_Id'])   &&
      isset($_POST['u_Name'])    &&
	  isset($_POST['u_Email'])    &&
	  isset($_POST['u_Password']) &&
      isset($_POST['u_Type']))
  {
    $u_Id   = get_post($conn, 'u_Id');
	$u_Name    = get_post($conn, 'u_Name');
    $u_Type    = get_post($conn, 'u_Type');
    $u_Email = get_post($conn, 'u_Email');
    $u_Password     = get_post($conn, 'u_Password');
    $query_First    = "SELECT userCode from Users_Codes WHERE userType='$u_Type'";
	$usercode = $conn->query($query_First);
	$row = $usercode->fetch_array(MYSQLI_NUM);
	$user_code = $row[0];
	$y = 1;
	
    $query_Second    = "INSERT INTO User_Profiles VALUES" .
      "('$u_Id','$u_Name', '$u_Email','$u_Password', '$user_code', '$y')";
	  
	$result   = $conn->query($query_Second);
	   
  	if (!$result) 
	{
		echo "INSERT failed: $query_Second<br>" .
      $conn->error . "<br><br>";
	}
	else
	{
		echo "Create Account successfully";
		header("Location: http://pan16.myweb.cs.uwindsor.ca/60334/project/html/User_Login.php");
		exit();
	}
  }

  echo <<<_END
          <script>
		if(window.history.replaceState)
		{
			window.history.replaceState(null, null, window.location.href);		
		}
  </script>
  
  <form action="Add_User_Profiles.php" method="post"><pre>
      User ID: <input type="text" name="u_Id">
    User name: <input type="text" name="u_Name">
   User Email: <input type="text" name="u_Email">
User Password: <input type="text" name="u_Password">              
    User Type: <select name="u_Type">
		   <option>student</option>
		   <option>teacher</option>
		   </select>
		     <input type="submit" value="Create Account">
  </pre></form>
_END;

  $usercode->close();
  $result->close();
  $conn->close();
  
    function get_post($conn, $var)
  {
    return $conn->real_escape_string($_POST[$var]);
  }
?>
