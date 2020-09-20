<?php
   echo <<<_END
	<script>
	if(window.history.replaceState)
	{
		window.history.replaceState(null, null, window.location.href);		
	}
  </script>
	<script src ="../js/checkboxes.js">
	</script>
<h1>Main Page</h1>
  <form action="http://pan16.myweb.cs.uwindsor.ca/60334/project/html/Borrow_Books.php">
    <input type="submit" value="Library" />
	</form>
	<form action="http://pan16.myweb.cs.uwindsor.ca/60334/project/html/Return_Books.php">
    <input type="submit" value="Return books" />
	</form>
	<form action="http://pan16.myweb.cs.uwindsor.ca/60334/project/html/Fine.php">
    <input type="submit" value="Pay fine" />
	</form>
	<form action="http://pan16.myweb.cs.uwindsor.ca/60334/project/html/My_User_Profile.php">
    <input type="submit" value="My user profile" />
	</form>
		<form action="http://pan16.myweb.cs.uwindsor.ca/60334/project/html/Book_Category_Pie_Chart.php">
    <input type="submit" value="Book category pie chart" />
	</form>
	</form>
		<form action="http://pan16.myweb.cs.uwindsor.ca/60334/project/html/Which_Category_Most_Borrowed.php">
    <input type="submit" value="What category of book category is the most borrowed pie chart" />
	</form>
_END;
?>
