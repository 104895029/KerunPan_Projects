<?php
  session_start();
  $current_userId = $_SESSION['current_userId'];
  
  require_once 'login.php';
  $conn = new mysqli($hn, $un, $pw, $db);
  if ($conn->connect_error) die($conn->connect_error);

  $query  = "SELECT * FROM User_Profiles where User_Profiles.userId = '$current_userId';";
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
<h1>My User Profile</h1>
  <table>
  <thead>
  <tr>
		<th>User Id</th>
		<th>User Name</th>
		<th>User Email</th>
		<th>User Password</th>
		<th>User Code</th>
		<th>User Credit</th>
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
	  <th>$row[5]</th>
_END;
	} 
     echo <<<_END
    </tr>
	 </tbody>
	 </table>
	 
	     <form action="http://pan16.myweb.cs.uwindsor.ca/60334/project/html/Main_Page.php">
    <input type="submit" value="Main page" />
	</form>
_END;

  $result->close();
  $conn->close();
  
  function get_post($conn, $var)
  {
    return $conn->real_escape_string($_POST[$var]);
  }
?>
