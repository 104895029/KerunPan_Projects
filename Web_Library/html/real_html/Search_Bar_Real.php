<script>
if(window.history.replaceState)
{
	window.history.replaceState(null, null, window.location.href);		
}
</script>
<?php

session_start(); 
  $current_userId = $_SESSION['current_userId'];
  
  require_once '../login.php';
  $conn = new mysqli($hn, $un, $pw, $db);
  if ($conn->connect_error) die($conn->connect_error);

  if (isset($_POST['bookName']))
  {
	$bookName   = get_post($conn, 'bookName');		
	$query2  = " SELECT * FROM `Book` WHERE bookName = '$bookName'; ";
	
	$result2 = $conn->query($query2);
	$row2 = $result2->fetch_array(MYSQLI_NUM);
	if (empty($row2[0])) 
	{
		  echo <<<_END
<div class="fixed-top">
<div class="alert alert-warning" role="alert">
Do not find this book name, please enter the correct title!
</div>
</div>
_END;
	}
else
{
	  echo <<<_END
<div class="fixed-top">
<div class="alert alert-success" role="alert">
Correct book name！
</div>
</div>
_END;
}	


  }
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
<link href="../../css/bootstrap.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">


    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
      }
      
      .navbar.navbar-1 .navbar-toggler-icon {
        background-image: url('https://mdbootstrap.com/img/svg/hamburger6.svg?color=000');
        }

      form {
        display: contents;
      }
      
      
      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>
    <!-- Custom styles for this template -->
	<link href="../../css/Book_Slot.css" rel="stylesheet">
    <link href="../../css/Search_Bar_Real.css" rel="stylesheet">
    <link href="../../css/Home_Page_Real.css" rel="stylesheet">
    <link href="../../css/Side_Bar.css" rel="stylesheet">
	
  </head>
  <body>
    <nav class="navbar navbar-1 site-header sticky-top py-1">
  <div class="py-2 container d-flex flex-column flex-md-row justify-content-between">
      <a class="navbar-brand py-2 d-md-inline-block" href="#"><img class="mb-4" src="../../image/book.png" alt="" width="65" height="65"></a>
    <button class="navbar-toggler d-lg-none d-md-none" type="button" data-toggle="collapse" data-target="#ham"
    aria-controls="ham" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
    <a class="py-2 d-none d-md-inline-block" href="Home_Page_Real.php">Home</a>
    <a class="py-2 d-none d-md-inline-block" href="Return_Book_Real.php">Return Book</a>
    <a class="py-2 d-none d-md-inline-block" href="Fine_Real.php">Pay Fine <span class="sr-only">(current)</span></a>
    <a class="py-2 d-none d-md-inline-block" href="Report_Real.php">Report</a>
	<a class="py-2 d-none d-md-inline-block" href="Search_Bar_Real.php">Search</a>
	
    <div class="justify-content-right">
    <a class="py-2 d-none d-md-inline-block" href="My_User_Profile_Real.php">My User Profile</a>
    </div>
          <!-- Collapsible content -->
          
          <div style="text-align:center;" class="collapse navbar-collapse" id="ham">
            <ul class="navbar-nav mr-auto">
              <li class="nav-item">
                <a class="nav-link" href="Home_Page_Real.php">Home </span></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="Return_Book_Real.php">Return Book</a>
              </li>
              <li class="nav-item active">
                <a class="nav-link" href="Fine_Real.php">Pay Fine<span class="sr-only">(current)</span></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="Report_Real.php">Report</a>
              </li>
				<li class="nav-item">
                <a class="nav-link" href="Search_Bar_Real.php">Search</a>
              </li>
				<li class="nav-item">
                <a class="nav-link" href="My_User_Profile_Real.php">My User Profile</a>
              </li>
            </ul>
          </div>
          
  </div>
</nav>

    <!-- Page Content -->
	<div class="row d-flex justify-content-center">
	<div class="card" style="width: 18rem;">
<?php

 echo <<<_END
  <img class="card-img-top" src="../../image/$row2[5].jpg" alt="Book Image Display here!">
  <div class="card-body">
    <h4 class="card-title">
		  <a href="#">$row2[1]</a>
			</h4>			
		  <h6>Author: $row2[2]<h6>
		<h6>Category: $row2[3]<h6>
		<h6>Number in stock: $row2[4]<h6>
  </div>
_END;

 ?>

</div>
	<div class="flexbox">	
	<form action="Search_Bar_Real.php" method="post">
  <div class="search">
    <h1>Search the book</h1>
    <h3>Click on search icon, then type the correct book's name.</h3>
    <div>
      <input type="text"  name="bookName" placeholder="Search . . ." required>
    </div>	
  </div>
  </form>
  </div>
</div>


<!-- Footer -->
  <footer class="py-5 bg-dark">
    <div class="container">
      <p class="m-0 text-center text-white">Copyright &copy; Kerun Pan's Website 2020</p>
    </div>
    <!-- /.container -->
  </footer>
  
<!-- Bootstrap core JavaScript -->
<script src="../../js/jquery-3.5.1.min.js"></script>
<script src="../../js/bootstrap.min.js"></script>

<!-- Menu Toggle Script -->
  <script>
    $("#menu-toggle").click(function(e) {
      e.preventDefault();
      $("#wrapper").toggleClass("toggled");
    });
// avoid reloading data    
    if ( window.history.replaceState ) {
      window.history.replaceState( null, null, window.location.href );
    }
  </script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script>window.jQuery || document.write('<script src="../assets/js/vendor/jquery.slim.min.js"><\/script>')</script><script src="../assets/dist/js/bootstrap.bundle.js"></script>
</body>
<?php
  $result->close();
  $result2->close();
  //$result7->close();
  $conn->close();
?>
</html>