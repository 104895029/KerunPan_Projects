<?php

  session_start();
  
  require_once '../login.php';
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
  echo <<<_END
<div class="fixed-top">
<div class="alert alert-warning" role="alert">
  Account does not exist! Please create an account!
</div>
</div>
_END;
	}
	else
	{
		$query_Login    = "SELECT * FROM `User_Profiles` WHERE userId = '$u_Id' AND userPassword = '$u_Password'";
		$result2   = $conn->query($query_Login);
		$row2 = $result2->fetch_array(MYSQLI_NUM);
		
		if (empty($row2[0])) 
		{
  echo <<<_END
<div class="fixed-top">
<div class="alert alert-danger" role="alert">
  Worry Password, Cannot Login!
</div>
</div>
_END;
		}
		else
		{
  echo <<<_END
<div class="fixed-top">
<div class="alert alert-success" role="alert">
  Login successfully!
</div>
</div>
_END;

			$_SESSION['current_userId'] = $u_Id;
			
			header("Location: http://pan16.myweb.cs.uwindsor.ca/60334/project/html/real_html/Home_Page_Real.php");
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
_END;
	  function get_post($conn, $var)
	  {
		return $conn->real_escape_string($_POST[$var]);
	  }
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
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
    <link href="../../css/User_Login_Real.css" rel="stylesheet">
  </head>
  
  <body class="text-center">
    <form class="form-signin" action="User_Login_Real.php" method="post">
  <img class="mb-4" src="../../image/book.png" alt="" width="100" height="100">
  <h1 class="h3 mb-3 font-weight-normal">My Library</h1>
  <label for="inputID" class="sr-only">User id</label>
  <input type="text" id="inputID" name="u_Id" class="form-control" placeholder="User id" required autofocus>
  <label for="inputPassword" class="sr-only">Password</label>
  <input type="password" id="inputPassword" name="u_Password" class="form-control" placeholder="Password" required>

  <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
  <a href="http://pan16.myweb.cs.uwindsor.ca/60334/project/html/real_html/Create_Account_Real.php" class="text-decoration-none">Create Account</a>
</form>
<?php
   $result1->close();
   $conn->close();
?>
</body>
</html>
