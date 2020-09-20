<?php
  session_start();
  $current_userId = $_SESSION['current_userId'];
  
  require_once 'login.php';
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
			echo "Your user credit is 0, so you can’t borrow books!";
		}
		else
		{
			$query1  = "SELECT DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 10 DAY);";
			$result1 = $conn->query($query1);
			$row1 = $result1->fetch_array(MYSQLI_NUM);
			$endDate = $row1[0];
	  
			for($i = 0; $i < count($_POST['mergeId']); $i++)
			{ 
				//split the mergeId[i] by comma		
				$myArray = explode(',', $_POST['mergeId'][$i]);		
				$bookIdIndex = $myArray[0];
				$bookCategory = $myArray[1];
			
				$query2  = " INSERT INTO Borrow_Books VALUES".
				"('$current_userId', '$bookIdIndex', CURRENT_TIMESTAMP, '$endDate');";
				$result2 = $conn->query($query2);
				if (!$result2) echo "Borrow failed: $query2<br>" .
				  $conn->error . "<br><br>";
		 
				//Borrow_Books succeed
				else
				{
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
 }


  $query  = "SELECT * FROM Book";
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
<h1>Books in library</h1>
<form action="Borrow_Books.php" method="post">
  <table>
  <thead>
  <tr>
		<th>Book Id</th>
		<th>Book Name</th>
		<th>Book Author</th>
		<th>Book Category</th>
		<th>Book Number in stock</th>
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
	  <th>$row[4]</th>
_END;
    if($row[4] > 0)
	{
	$merger_string = "$row[0],"."$row[3]";
   echo <<<_END
	  <th><input type="checkbox" name="mergeId[]" value="$merger_string"></th>
_END;
	}
	else
	{
   //if book number in stock == 0, cannot borrow
   echo <<<_END
	  <th><input type="checkbox" hidden></th>
_END;
	}
	}

  
     echo <<<_END
    </tr>
	 </tbody>
	 </table>
	 
	<input type="hidden" name="borrow" value="yes">
	<input type="submit" value="Borrow">
  </form>
  
      <form action="http://pan16.myweb.cs.uwindsor.ca/60334/project/html/Main_Page.php">
    <input type="submit" value="Main page" />
	</form>
_END;


  $result->close();
  $result2->close();
  $result3->close();
  $result4->close();
  $result8->close();
  $conn->close();
  
  function get_post($conn, $var)
  {
    return $conn->real_escape_string($_POST[$var]);
  }
?>
