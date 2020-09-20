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

  if (isset($_POST['return']) && isset($_POST['bookId']))
  {
	  //$query1  = "SELECT DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 10 DAY);";
	  //$result1 = $conn->query($query1);
	  //$row1 = $result1->fetch_array(MYSQLI_NUM);
	  //$endDate = $row1[0];
	  
	  foreach($_POST['bookId'] as $bookIdIndex) { 
	   $query2  = " INSERT INTO Return_Books VALUES".
      "('$current_userId', '$bookIdIndex', '2020-9-15 23:37:48');"; //CURRENT_TIMESTAMP
    $result2 = $conn->query($query2);
  	if (!$result2) echo "Return failed: $query2<br>" .
      $conn->error . "<br><br>";
	    
	  	//if Return_Books.returnDate > Borrow_Books.endDate insert a record to fine
		
		  //1st, get Borrow_Books.endDate
		$query3  = "SELECT Borrow_Books.endDate FROM Borrow_Books WHERE userId = '$current_userId' and bookId = '$bookIdIndex';";
		$result3 = $conn->query($query3);
		if (!$result3) die ("Database access failed: " . $conn->error);
		
		$row3 = $result3->fetch_array(MYSQLI_NUM);
		$endDate = $row3[0];
		
		//2nd, get Return_Books.returnDate
		$query4  = "SELECT Return_Books.returnDate FROM Return_Books WHERE userId = '$current_userId' and bookId = '$bookIdIndex';";
		$result4 = $conn->query($query4);
		if (!$result4) die ("Database access failed: " . $conn->error);
		
		$row4 = $result4->fetch_array(MYSQLI_NUM);
		$returnDate = $row4[0];
		
		//3rd, compare tow timestamp
		$query5  = "SELECT TIMESTAMPDIFF(DAY, '$endDate', '$returnDate');";
		$result5 = $conn->query($query5);
		if (!$result5) die ("Database access failed: " . $conn->error);
		
		$row5 = $result5->fetch_array(MYSQLI_NUM);
		$daysOverDue = $row5[0];
		
		if($daysOverDue > 0)
		{


			  echo <<<_END
<div class="fixed-top">
<div class="alert alert-warning" role="alert">
Return Book successfully! However, you return the book after dueday, your user credit decrease, and you need to pay fine for the book!
</div>
</div>
_END;

			//calculate fine number
			$fine = $daysOverDue * 2;
			
			//insert a fine record to Fine table
			$query6  = " INSERT INTO Fine VALUES".
		  "('$current_userId', '$bookIdIndex',CURRENT_TIMESTAMP, '$fine', '$daysOverDue');";
			$result6 = $conn->query($query6);
			if (!$result6) echo "Create Fine failed: $query6<br>" .
		  $conn->error . "<br><br>";
		  		  
		  //decrease the user credit by 1
			$query8  = "UPDATE User_Profiles".
			" SET User_Profiles.UserCredit = User_Profiles.UserCredit - 1".
			" WHERE User_Profiles.userId = $current_userId;";
			
			$result8 = $conn->query($query8);
			if (!$result8) echo "UPDATE User_Profiles failed: $query8<br>" .
		  $conn->error . "<br><br>";
		  
		  //Delete the record from Borrow_Books
		  $query9  = " DELETE FROM Borrow_Books WHERE Borrow_Books.bookId ='$bookIdIndex' and Borrow_Books.userId = $current_userId;";
          $result9 = $conn->query($query9);
  	      if (!$result9) echo "Delete the record from Borrow_Books failed: $query9<br>" .
      $conn->error . "<br><br>";
	  
		}
		else
		{
			  echo <<<_END
<div class="fixed-top">
<div class="alert alert-success" role="alert">
Return Book successfully! You return the book before dueday, your user credit increase!
</div>
</div>
_END;

			//increase the user credit by 1
			$query7  = "UPDATE User_Profiles".
			" SET User_Profiles.UserCredit = User_Profiles.UserCredit + 1".
			" WHERE User_Profiles.userId = $current_userId;";
			
			$result7 = $conn->query($query7);
			if (!$result7) echo "UPDATE User_Profiles failed: $query7<br>" .
		  $conn->error . "<br><br>";	  
		}	
		
	   //Increase the bookNumberInStock  in Book by 1
		$query10  = "UPDATE Book".
		" SET Book.bookNumberInStock = Book.bookNumberInStock  + 1".
		" WHERE Book.bookId = '$bookIdIndex';";
		
		$result10 = $conn->query($query10);
		if (!$result10) echo "Increase the bookNumberInStock  in Book failed: $query10<br>" .
	  $conn->error . "<br><br>";
	  
	  	//DELETE the record  in Return_Books
		$query11  = "DELETE FROM Return_Books WHERE Return_Books.bookId ='$bookIdIndex' and Return_Books.userId = $current_userId;";
		
		$result11 = $conn->query($query11);
		if (!$result11) echo "DELETE the record  in Return_Books failed: $query11<br>" .
	  $conn->error . "<br><br>";
	  
	  	//INSERT a record  to Return_Books_History
		$query12  = " INSERT INTO Return_Books_History VALUES".
      "('$current_userId', '$bookIdIndex', CURRENT_TIMESTAMP);";
		
		$result12 = $conn->query($query12);
		if (!$result12) echo "INSERT a record  to Return_Books_History failed: $query12<br>" .
	  $conn->error . "<br><br>";
	  
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
	<link href="../../css/Return_Book_Table.css" rel="stylesheet">
    <link href="../../css/Home_Page_Real.css" rel="stylesheet">
    <link href="../../css/Side_Bar.css" rel="stylesheet">
    
	
  </head>
  <body>
    <nav class="navbar navbar-1 site-header sticky-top py-1">
  <div class="py-2 container d-flex flex-column flex-md-row justify-content-between">
      <a class="navbar-brand py-2 d-md-inline-block" href="#"><img class="mb-4" src="../../image/book.png" alt="" width="65" height="65"></a>
    <button class="navbar-toggler d-lg-none d-md-none" type="button" data-toggle="collapse" data-target="#ham"
    aria-controls="ham" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
    <a class="py-2 d-none d-md-inline-block" href="Home_Page_Real.php">Home </a>
    <a class="py-2 d-none d-md-inline-block" href="Return_Book_Real.php">Return Book<span class="sr-only">(current)</span></a>
    <a class="py-2 d-none d-md-inline-block" href="Fine_Real.php">Pay Fine</a>
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
              <li class="nav-item active">
                <a class="nav-link" href="Return_Book_Real.php">Return Book<span class="sr-only">(current)</span></a>
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

    <!-- Page Content -->
    <div id="page-content-wrapper">	
      <div class="container">
	          <div class="row">
			  <div class="col-12">
			  <div class="card">
			   <div class="card-body text-center">
                    <h5 class="card-title m-b-0">Select books to return</h5>
				</div>
		  <form action="Return_Book_Real.php" method="post">
<?php	
  $query  = "SELECT * FROM Borrow_Books WHERE userId = '$current_userId';";
  $result = $conn->query($query);
  if (!$result) die ("Database access failed: " . $conn->error);

  $rows = $result->num_rows;
  
   echo <<<_END
   
  <div class="table-responsive">
                    <table class="table">
                        <thead class="thead-light">
                            <tr>
                                <th> <label class="customcheckbox m-b-20"> <input type="checkbox" id="mainCheckbox"> <span class="checkmark"></span> </label> </th>
                                <th scope="col">Book Id</th>
								<th scope="col">Book Name</th>
                                <th scope="col">Borrowed Date</th>
                                <th scope="col">Due Date</th>
                            </tr>
                        </thead>
_END;
		 for ($j = 0 ; $j < $rows ; ++$j)
		  {
			$result->data_seek($j);
			$row = $result->fetch_array(MYSQLI_NUM);
   echo <<<_END
                        <tbody class="customtable">
                            <tr>
                                <th> <label class="customcheckbox"> <input type="checkbox" class="listCheckbox" name="bookId[]" value="$row[1]"> <span class="checkmark"></span> </label> </th>
                                <td>$row[1]</td>
_END;

		$query13  = "SELECT Book.bookName FROM `Book` WHERE bookId = $row[1]";
  $result13 = $conn->query($query13);
  if (!$result13) die ("Database access failed: " . $conn->error);
  $row13 = $result13->fetch_array(MYSQLI_NUM);
  
		echo <<<_END
								<td>$row13[0]</td>
                                <td>$row[2]</td>
                                <td>$row[3]</td>
                            </tr>                        
_END;
		  }	  		  
?>			
				</tbody>
				</table>
				</div>
				<div class="text-center">
				 <p><button type="submit" name="return" value="Return"" class="btn btn-primary">Return Books</button></p>
				 </div>
				<input type="hidden" name="return" value="yes">
				</div> <!-- card-->
			</form>
			</div>
          </div>		
        </div><!-- container-->
        <!-- /.row -->     
      </div>
    </div>
    <!-- /#page-content-wrapper -->
  </div>
</body>

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
  $result10->close();
  $result11->close();
  $result12->close();
  $result13->close();
  $conn->close();
?>
</html>