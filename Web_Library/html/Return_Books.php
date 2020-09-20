<?php
  session_start();
  $current_userId = $_SESSION['current_userId'];
  
  require_once 'login.php';
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
      "('$current_userId', '$bookIdIndex', '2020-08-30 23:37:48');"; //CURRENT_TIMESTAMP
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

  $query  = "SELECT * FROM Borrow_Books WHERE userId = '$current_userId';";
  $result = $conn->query($query);
  if (!$result) die ("Database access failed: " . $conn->error);

  $rows = $result->num_rows;
  
   echo <<<_END
   <link rel="stylesheet" type="text/css" href="../css/table_lab.css">

   <script>
	if(window.history.replaceState)
	{
		window.history.replaceState(null, null, window.location.href);		
	}
  </script>
  
	<script src ="../js/checkboxes.js">
	</script>
<h1>My Borrowed Books</h1>
<form action="Return_Books.php" method="post">
  <table>
  <thead>
  <tr>
		<th>User Id</th>
		<th>Book Id</th>
		<th>Start Date</th>
		<th>End Date</th>
		<th><input type="checkbox""></th>
  </tr>
</thead>
_END;

  for ($j = 0 ; $j < $rows ; ++$j)
  {
    $result->data_seek($j);
    $row = $result->fetch_array(MYSQLI_NUM);

    echo <<<_END
  <pre>
  <tbody>
  <tr>
      <th>$row[0]</th>
      <th>$row[1]</th>
      <th>$row[2]</th>
      <th>$row[3]</th>
	  <th><input type="checkbox" name="bookId[]" value="$row[1]"></th>
  </tr>
_END;
  }
  
     echo <<<_END
	 </tbody>
	 </table>
	 
	<input type="hidden" name="return" value="yes">
	<input type="submit" value="Return">
  </form>
  
    <form action="http://pan16.myweb.cs.uwindsor.ca/60334/project/html/Main_Page.php">
    <input type="submit" value="Main page" />
	</form>
_END;


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
  //$result13->close();
  $conn->close();
  
  function get_post($conn, $var)
  {
    return $conn->real_escape_string($_POST[$var]);
  }
?>
