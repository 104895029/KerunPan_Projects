<?php
  require_once '../login.php';
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
	
	//if this user is student, user code is 2, the start user credit is 2
	if(strcmp("$u_Type","student") == 0)
	{
		$userCredit = 2;
	}
	//if this user is teacher, user code is 1, the start user credit is 3
	if(strcmp("$u_Type","teacher") == 0)
	{
		$userCredit = 3;
	}
	
    $query_Second    = "INSERT INTO User_Profiles VALUES" .
      "('$u_Id','$u_Name', '$u_Email','$u_Password', '$user_code', '$userCredit')";
	  
	$result   = $conn->query($query_Second);
	   
  	if (!$result) 
	{
		echo "INSERT failed: $query_Second<br>" .
      $conn->error . "<br><br>";
	}
	else
	{
		echo "Create Account successfully";
		header("Location: http://pan16.myweb.cs.uwindsor.ca/60334/project/html/real_html/User_Login_Real.php");
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
_END;

    function get_post($conn, $var)
  {
    return $conn->real_escape_string($_POST[$var]);
  }
?>

<!doctype html>
<html lang="en">
  <head>
    <script src="../../js/popper.min.js"></script>
  
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="canonical" href="https://getbootstrap.com/docs/4.5/examples/sign-in/">

	
	
    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
	
    <script src="../../js/jquery-3.5.1.min.js"></script>
	<script src="../../js/bootstrap.min.js"></script>

    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>
    <!-- Custom styles for this template -->
    <link href="../../css/Create_Account_Real.css" rel="stylesheet">
  </head>
  
  <body class="text-center">
    <form class="form-signin" action="Create_Account_Real.php" method="post">
  <img class="mb-4" src="../../image/book.png" alt="" width="100" height="100">
  <label for="inputID" class="sr-only">User id</label>
  <input type="text" id="inputID" name="u_Id" class="form-control" placeholder="User id" required autofocus>
  
  <label for="userName" class="sr-only">User name</label>
  <input type="text" id="inputName" name="u_Name" class="form-control" placeholder="User name" required autofocus>
  
  <label for="userEmail" class="sr-only">User Email</label>
  <input type="text" id="inputEmail" name="u_Email" class="form-control" placeholder="User email" required autofocus>
  
  <label for="inputPassword" class="sr-only">Password</label>
  <input type="password" id="inputPassword" name="u_Password" class="form-control" placeholder="Password" required>
  
	
	<label for="inputPassword" class="sr-only">User Type:</label>
 
	       <select name="u_Type">
		   <option value="teacher">teacher</option>
		   <option value="student">student</option>
		   </select>

  <button class="btn btn-lg btn-primary btn-block" type="submit">Create Account</button>
</form>


<?php
  $usercode->close();
  $result->close();
  $conn->close();
?>
</body>
</html>
