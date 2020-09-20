<script>
if(window.history.replaceState)
{
	window.history.replaceState(null, null, window.location.href);		
}
</script>

<?php 
    require_once '../login.php';
    $conn = new mysqli($hn, $un, $pw, $db);
    if ($conn->connect_error) die($conn->connect_error);      //connect to mysql
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
      

    </style>
    <!-- Custom styles for this template -->
    <link href="../../css/Book_Slot.css" rel="stylesheet">
	<link href="../../css/Return_Book_Table.css" rel="stylesheet">
    <link href="../../css/Home_Page_Real.css" rel="stylesheet">
    <link href="../../css/Side_Bar.css" rel="stylesheet">
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
	
	
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
    <a class="py-2 d-none d-md-inline-block" href="#">Report</a>
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

 <div class="container-fluid">
			<div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
			</div>
 
 <!-- Content Row -->
          <div class="row">

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total number of books in Library</div>
<?php 
      $query2    = "SELECT SUM(bookNumberInStock) FROM `Book`";
      $result2  = $conn->query($query2);
	  $row2 = $result2->fetch_array(MYSQLI_NUM);
	 

    echo <<<_END
                      <div class="h5 mb-0 font-weight-bold text-gray-800">$row2[0]</div>
_END;

?>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-calendar fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
			
			<!-- Earnings (Monthly) Card Example -->
			<div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-success text-uppercase mb-1">The number of teachers</div>
					  
<?php 
      $query3    = "SELECT SUM(userCode) FROM `User_Profiles` where userCode = 1";
      $result3  = $conn->query($query3);
	  $row3 = $result3->fetch_array(MYSQLI_NUM);
	 
    echo <<<_END
			  <div class="h5 mb-0 font-weight-bold text-gray-800">$row3[0]</div>
_END;

?>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
			
			
			<div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-success text-uppercase mb-1">The number of students</div>
					  
<?php 
      $query4    = "SELECT SUM(userCode) FROM `User_Profiles` where userCode = 2";
      $result4  = $conn->query($query4);
	  $row4 = $result4->fetch_array(MYSQLI_NUM);
	 
    echo <<<_END
			  <div class="h5 mb-0 font-weight-bold text-gray-800">$row4[0]</div>
_END;

?>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
			
			
          <!-- Content Row -->

          <div class="row">
		  
			<div class="col-xl-4 col-lg-5">
              <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Book number group by category in library</h6>             
                </div>
                <!-- Card Body -->
                <div class="card-body">  
				  
<?php 
     $query    = "SELECT bookCategory, SUM(bookNumberInStock) FROM Book group by bookCategory";
     $result  = $conn->query($query);

?>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Categry', 'Count'],
<?php	  
		  $rows = $result->num_rows;
		  for ($j = 0 ; $j < $rows ; ++$j)
		  {
			$result->data_seek($j);
			$row = $result->fetch_array(MYSQLI_NUM);
			echo "['".$row[0]."', ".$row[1]."],";
		  }
?>
        ]);

        var options = {
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
      }
    </script>
    <div id="piechart" style="width: 900px; height: 500px;"></div>
                </div>
              </div>
            </div>
			
			
			<div class="col-xl-4 col-lg-5">
              <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Which type of book is the most borrowed?</h6>             
                </div>
                <!-- Card Body -->
                <div class="card-body">  
				  
<?php 
     $query2    = "SELECT bookCategory,count(*) as cnt FROM Borrow_Books_History group by bookCategory";
     $result2  = $conn->query($query2);

?>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Categry', 'Count'],
<?php	  
		  $rows = $result->num_rows;
		  for ($j = 0 ; $j < $rows ; ++$j)
		  {
			$result2->data_seek($j);
			$row2 = $result2->fetch_array(MYSQLI_NUM);
			echo "['".$row2[0]."', ".$row2[1]."],";
		  }
?>
        ]);

        var options = {
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart2'));

        chart.draw(data, options);
      }
    </script>
    <div id="piechart2" style="width: 900px; height: 500px;"></div>
                </div>
              </div>
            </div>
			
			
		</div>
			
			
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

<!-- Custom scripts for all pages-->
  <script src="../../js/sb-admin-2.min.js"></script>


  <!-- Page level custom scripts -->
  <script src="../../js/demo/chart-area-demo.js"></script>
  <script src="../../js/demo/chart-pie-demo.js"></script>
  
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
  $conn->close();
?>
</html>