<html>
  <head>
<?php 
    require_once 'login.php';
    $conn = new mysqli($hn, $un, $pw, $db);
    if ($conn->connect_error) die($conn->connect_error);      //connect to mysql
     $query    = "SELECT bookCategory,count(*) as cnt FROM Borrow_Books_History group by bookCategory";
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
          title: 'Which type of book is the most borrowed?'
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
      }
    </script>
<?php
     $conn->close();
?>
  </head>
  <body>
    <div id="piechart" style="width: 900px; height: 500px;"></div>
	
	 <form action="http://pan16.myweb.cs.uwindsor.ca/60334/project/html/Main_Page.php">
    <input type="submit" value="Main page" />
	</form>
  </body>
</html>