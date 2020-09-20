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

  if (isset($_POST['borrow']) && isset($_POST['mergeId']))
  {

		$query4    = "SELECT User_Profiles.UserCredit FROM User_Profiles WHERE userId = '$current_userId';";
		$result4   = $conn->query($query4);
		$row = $result4->fetch_array(MYSQLI_NUM);
		
		//1st check if this user's credit == 0, he cannot borrow any book.
		if($row[0] == 0)
		{
  echo <<<_END
<div class="fixed-top">
<div class="alert alert-danger" role="alert">
  Your user credit is 0, so you can’t borrow books!
</div>
</div>
_END;
		}
		else
		{
			$query6    = "SELECT User_Profiles.userCode FROM User_Profiles WHERE userId = '$current_userId'";
			$result6   = $conn->query($query6);
			$row6 = $result6->fetch_array(MYSQLI_NUM);
			$userCode = $row6[0];
		
			$query7    = "SELECT Users_Codes.userType FROM Users_Codes WHERE userCode = '$userCode'";
			$result7   = $conn->query($query7);
			$row7 = $result7->fetch_array(MYSQLI_NUM);
			
			if(strcmp("$row7[0]","student") == 0)
			{
				echo "student";
				$days = 15;
			}
			if(strcmp("$row7[0]","teacher") == 0)
			{
				echo "teacher";
				$days = 30;
			}	
			$query1  = "SELECT DATE_ADD(CURRENT_TIMESTAMP, INTERVAL ".$days." DAY);";
			$result1 = $conn->query($query1);
			$row1 = $result1->fetch_array(MYSQLI_NUM);
			$endDate = $row1[0];
	  
			//for($i = 0; $i < count($_POST['mergeId']); $i++)
			//{ 
				//split the mergeId[i] by comma		
				$myArray = explode(',', $_POST['mergeId']);		
				$bookIdIndex = $myArray[0];
				$bookCategory = $myArray[1];
			
				$query2  = " INSERT INTO Borrow_Books VALUES".
				"('$current_userId', '$bookIdIndex', CURRENT_TIMESTAMP, '$endDate');";
				$result2 = $conn->query($query2);
				if (!$result2)
				{
					  echo <<<_END
<div class="fixed-top">
<div class="alert alert-danger" role="alert">
  Borrow failed: $query2<br>
</div>
</div>
_END;
				}
	 
				//Borrow_Books succeed
				else
				{
  echo <<<_END
<div class="fixed-top">
<div class="alert alert-success" role="alert">
Borrow Book successfully!
</div>
</div>
_END;

					//INSERT book to Borrow_Books_History
					$query3  = " INSERT INTO Borrow_Books_History VALUES".
					"('$current_userId', '$bookIdIndex', CURRENT_TIMESTAMP, '$bookCategory');";
					$result3 = $conn->query($query3);
					if (!$result3) echo "INSERT book to Borrow_Books_History failed: $query3<br>" .
					$conn->error . "<br><br>";
				  
					//decrease the book number in stock by 1
					$query8  = "UPDATE Book".
					" SET Book.bookNumberInStock = Book.bookNumberInStock - 1".
					" WHERE Book.bookId = '$bookIdIndex';";
					
					$result8 = $conn->query($query8);
					if (!$result8) echo "UPDATE Book failed: $query8<br>" .
					$conn->error . "<br><br>";				
				}		
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
    <link href="../../css/Home_Page_Real.css" rel="stylesheet">
    <link href="../../css/Side_Bar.css" rel="stylesheet">
    
  </head>
  <body>
    <nav class="navbar navbar-1 site-header sticky-top py-1">
  <div class="py-2 container d-flex flex-column flex-md-row justify-content-between">
      <a class="navbar-brand py-2 d-md-inline-block" href="#"><img class="mb-4" src="../../image/book.png" alt="" width="65" height="65"></a>
    <button class="navbar-toggler d-lg-none d-md-none" type="button" data-toggle="collapse" data-target="#ham"
    aria-controls="ham" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
    <a class="py-2 d-none d-md-inline-block" href="Home_Page_Real.php">Home <span class="sr-only">(current)</span></a>
    <a class="py-2 d-none d-md-inline-block" href="Return_Book_Real.php">Return Book</a>
    <a class="py-2 d-none d-md-inline-block" href="Fine_Real.php">Pay Fine</a>
    <a class="py-2 d-none d-md-inline-block" href="Report_Real.php">Report</a>
	<a class="py-2 d-none d-md-inline-block" href="Search_Bar_Real.php">Search</a>
	
    <div class="justify-content-right">
    <a class="py-2 d-none d-md-inline-block" href="My_User_Profile_Real.php">My User Profile</a>
    </div>
          <!-- Collapsible content -->
          
          <div style="text-align:center;" class="collapse navbar-collapse" id="ham">
            <ul class="navbar-nav mr-auto">
              <li class="nav-item active">
                <a class="nav-link" href="Home_Page_Real.php">Home <span class="sr-only">(current)</span></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="Return_Book_Real.php">Return Book</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="Fine_Real.php">Pay Fine</a>
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

<div class="d-flex" id="wrapper">

    <!-- Sidebar -->
		<div class="col-lg-3" id="sidebar-wrapper">

        <h1 class="my-4">Category</h1>
        <div class="list-group">

		<a href="#" class="list-group-item list-group-item-action bg-light" onclick="changeCategory('Action')">Action</a>
        <a href="#" class="list-group-item list-group-item-action bg-light" onclick="changeCategory('bible')">Bible</a>
        <a href="#" class="list-group-item list-group-item-action bg-light" onclick="changeCategory('fiction')">Fiction</a>
		<a href="#" class="list-group-item list-group-item-action bg-light" onclick="changeCategory('History')">History</a>
        <a href="#" class="list-group-item list-group-item-action bg-light" onclick="changeCategory('Nutrition')">Nutrition</a>
        <a href="#" class="list-group-item list-group-item-action bg-light" onclick="changeCategory('Psychologie')">Psychologie</a>
        </div>
		<form action="Book_Category_Real.php" method="post">
		<pre>
                <input type="hidden" name="search" id="search">
      </pre></form>
      </div>
	  

    <!-- /#sidebar-wrapper -->

    <!-- Page Content -->
    <div id="page-content-wrapper">

      <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
        <button class="btn btn-outline-info" id="menu-toggle">Toggle Button</button>
      </nav>

      <div class="container-fluid">
	  
	          <div class="row">
		  <form action="Home_Page_Real.php" method="post">

<?php

  $query  = "SELECT * FROM Book";
  $result = $conn->query($query);
  if (!$result) die ("Database access failed: " . $conn->error);

  $rows = $result->num_rows;
  
		for ($j = 0 ; $j < $rows ; ++$j)
	  {
		$result->data_seek($j);
		$row = $result->fetch_array(MYSQLI_NUM);

   echo <<<_END
			<div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100">
_END;
 echo <<<_END
			 <a href="#"><img class="card-img-top" src="../../image/$row[5].jpg" alt=""></a>
				 <div class="card-body">
				 <h4 class="card-title">
					  <a href="#">$row[1]</a>
				   </h4>			
				  <h6>Author: $row[2]<h6>
				<h6>Category: $row[3]<h6>
				<h6>Number in stock: $row[4]<h6>
				</div>
				<div class="card-footer">
				<div class="text-center">
_END;
 if($row[4] > 0)
	{
	$merger_string = "$row[0],"."$row[3]";
	echo  <<<_END
	  <p><button type="submit" name="mergeId" value="$merger_string" class="btn btn-primary">Borrow</button></p>
_END;
    }
	
	else
	{
   //if book number in stock == 0, cannot borrow
   echo <<<_END
	  <p><button type="submit" name="nothing" value="$merger_string" class="btn btn btn-secondary" disabled>Not in library</button></p>
_END;
	}
	
 echo <<<_END
				</div>
              </div>
			</div>
			<input type="hidden" name="borrow" value="yes">
			</div>
_END;
			
			  }
?>			
			</form>
          </div>
        </div>
        <!-- /.row -->     
      </div>
    </div>
    <!-- /#page-content-wrapper -->

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

<script>function changeCategory(Cat){
        document.getElementById('search').value = Cat;
        document.forms[0].submit();
      }
</script>

<?php
$result->close();
  $result2->close();
  $result3->close();
  $result4->close();
  $result5->close();
  $result6->close();
  $result7->close();
  $result8->close();
  $result9->close();
  $conn->close();
?>
</body>
</html>