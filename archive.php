<html>
	<head>
		<link rel="stylesheet" type="text/css" href="table.css">
	</head>
	<body>
		<?php ob_start(); ?>
		<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<p>Channel ID: <input type="text" name="chanid" required="required"></p>
		<input name="submit" type="submit" value="Submit">
		</form>
	</body>
</html>

<?php

if (isset($_POST['chanid')) {
	ob_get_clean();
	date_default_timezone_set('America/New_York');
	$date = date("l M d\, Y");
	$time = date("h:i:s A");
	echo "<h2>Mattermost Message Transcript</h2>";
	echo "<p>Captured from channel ID: <strong>" . $_POST['chanid'] . "</strong></p>";
	echo "Captured at <strong>" . $time . "</strong> on <strong>" . $date . "</strong></p>";
	echo "<link rel=\"stylesheet\" type=\"text\/css\" href=\"table.css\">";
	getMessages();
}

function getMessages() {
	$con = mysqli_connect('hostname', 'username', 'password', 'database', 3306);

	$query = "SELECT Users.Username, Posts.CreateAt, Posts.Message FROM Posts INNER JOIN Users ON Posts.UserId = Users.Id WHERE `ChannelId` = '" . $_POST['chanid'] . "' ORDER BY Posts.CreateAt  ASC";
	$result = mysqli_query($con, $query);

	echo "<table border=\"1\">";

	while($row = mysqli_fetch_array($result)) {
		$epoch = substr($row['CreateAt'], 0, -3);
		$dt = new DateTime ("@$epoch");
		$db_dt = $dt->format('M d Y\, h:i:s \U\T\C');
		echo "<tr><td>" . htmlspecialchars($row['Username']) . "</td><td>" . htmlspecialchars($db_dt) . "</td><td>" . htmlspecialchars($row['Message']) ."</td></tr>";
	}

	echo "</table>";

	mysqli_close($con);
}

?>
