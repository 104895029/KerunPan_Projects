<?php
  session_start(); 
  $current_userId = $_SESSION['current_userId'];
  
  require_once 'login.php';
  $conn = new mysqli($hn, $un, $pw, $db);
  if ($conn->connect_error) die($conn->connect_error);

  if (isset($_POST['pay_fine']) && 
      isset($_POST['mergeId']))
  {
	
		for($i = 0; $i < count($_POST['mergeId']); $i++)
		{ 
			//split the mergeId[i] by comma		
			$myArray = explode(',', $_POST['mergeId'][$i]);		
			$bookIdIndex = $myArray[0];
			$createdDateIndex = $myArray[1];
			
			$query2  = " DELETE FROM Fine ".
			"WHERE Fine.bookId ='$bookIdIndex' and Fine.userId = '$current_userId' and Fine.createdDate = '$createdDateIndex';";
			$result2 = $conn->query($query2);
			if (!$result2) echo "Pay Fine failed: $query2<br>" .
			  $conn->error . "<br><br>";
				  
			//increase the user credit by 1
			$query7  = "UPDATE User_Profiles".
			" SET User_Profiles.UserCredit = User_Profiles.UserCredit + 1".
			" WHERE User_Profiles.userId = $current_userId;";
			
			$result7 = $conn->query($query7);
			if (!$result7) echo "UPDATE User_Profiles failed: $query7<br>" .
			$conn->error . "<br><br>";
		}
  }

  $query  = "SELECT * FROM Fine WHERE userId = '$current_userId';";
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
<h1>My Fine Table</h1>
<form action="Fine.php" method="post">
  <table>
  <thead>
  <tr>
		<th>User Id</th>
		<th>Book Id</th>
		<th>Created Date</th>
		<th>Fine Number($)</th>
		<th>Days Over</th>
		<th>Merger_bookId_createdDate</th>
		<th></th>
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
	
	$merger_bookId_createdDate = "$row[1],"."$row[2]";
	echo <<<_END
	  <th>$merger_bookId_createdDate</th>
_END;

	echo <<<_END
	  <th><input type="checkbox" name="mergeId[]" value="$merger_bookId_createdDate"></th>
_END;
  }
  
     echo <<<_END
	 </tbody>
	 </table>
	 
	<input type="hidden" name="pay_fine" value="yes">
	<input type="submit" value="Pay Fine">
  </form>
  
      <form action="http://pan16.myweb.cs.uwindsor.ca/60334/project/html/Main_Page.php">
    <input type="submit" value="Main page" />
	</form>
_END;


  $result->close();
  $result2->close();
  $result7->close();
  $conn->close();
  
  function get_post($conn, $var)
  {
    return $conn->real_escape_string($_POST[$var]);
  }
?>
