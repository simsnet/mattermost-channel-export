<?php

if (isset($_GET['chanid'])) {
	date_default_timezone_set('America/New_York');
	$date = date("l M d\, Y");
	$time = date("h:i:s A");
	echo "<header>";
	echo "<h2>Mattermost Message Transcript</h2>";
	getChannelInfo();
	echo "Retrieved at <strong>" . $time . "</strong> on <strong>" . $date . ".</strong></p>";
	echo "<p><strong>START OF TRANSCRIPT</strong></p>";
	echo "</header>";
	echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"table.css\">";
	echo "<table border=\"1\"><tbody>";
	echo "<tr><td id=\"name\"><strong>Name</strong></td><td id=\"username\"><b>Username</b></td><td id=\"datetime\"><strong>Time</strong></td><td id=\"message\"><strong>Message</strong></td><td id=\"att\"><strong>Attachment</strong></td><td id=\"att_prev\"><strong>Attachment Preview</strong></td><td id=\"edited\"><strong>Edited?</strong></td><td id=\"deleted\"><strong>Deleted?</strong></td></tr>";
	getTable();
	echo "</tbody></table>";
	echo "<footer>";
	echo "<p><strong>END OF TRANSCRIPT</strong></p>";
	echo "</footer>";
} else {
	echo "<h2>Error</h2>";
	echo "<p>No channel ID was supplied.</p>";
}

function getChannelInfo() {
	$con = mysqli_connect('localhost', 'username', 'password', 'mattermost', 3306);

	$query = 'SELECT `Channels`.`Id`, `Channels`.`DisplayName` FROM `Channels` WHERE `Id` = \'' . $_GET['chanid'] . '\'';
	$result = mysqli_query($con, $query);

	while($row = mysqli_fetch_array($result)) {
		echo "<p>Retrieved from channel name: <strong>" . $row['DisplayName'] . "</strong></p>";
	}

	mysqli_close($con);

}

function getTable() {
	$con = mysqli_connect('localhost', 'username', 'password', 'mattermost', 3306);

	$query = "SELECT Users.Username, Users.Nickname, Posts.CreateAt, Posts.Message, FileInfo.Path, FileInfo.ThumbnailPath, FileInfo.Name, Posts.OriginalId, Posts.DeleteAt, Posts.FileIds FROM Posts INNER JOIN Users ON Posts.UserId = Users.Id LEFT JOIN FileInfo ON Posts.Id = FileInfo.PostId WHERE `Posts`.`ChannelId` = '" . $_GET['chanid'] . "' ORDER BY Posts.CreateAt  ASC";
	$result = mysqli_query($con, $query);

	while($row = mysqli_fetch_array($result)) {
		$epoch = substr($row['CreateAt'], 0, -3);
		$dt = new DateTime ("@$epoch");
		$dt->setTimezone(new DateTimeZone('America/New_York'));
		$db_dt = $dt->format('M d Y\, h:i:s \E\T');

		if ($row['OriginalId'] == "") {
			$edited = "";
		} else {
			$edited = "X";
		}

		if ($row['DeleteAt'] == "0") {
			$deleted = "";
		} else {
			$deleted = "X";
		}

		if ($row['FileIds'] == "[]") {
			$att = "<p>No Attachments</p>";
		} else {
			$att = "<a target=\"_blank\" href=\"./data/" . $row['Path'] . "\">" . $row['Name'] . "</a>";
		}

		if ($row['ThumbnailPath'] == "") {
			$att_thumb = "<p>No Preview Available</p>";
		} else {
			$att_thumb = "<img src=\"./data/" . $row['ThumbnailPath'] . "\"";
		}

		echo "<tr><td id=\"name\">" . htmlspecialchars($row['Nickname']) . 
		"</td><td id=\"username\">" . 
		htmlspecialchars($row['Username']) . 
		"</td><td id=\"datetime\">" . 
		htmlspecialchars($db_dt) . 
		"</td><td id=\"message\"><p id=\"msgcontent\">" . 
		htmlspecialchars($row['Message']) . 
		"</p></td><td id=\"att\">" . 
		$att . 
		"</td><td id=\"att_prev\">" . 
		$att_thumb . 
		"</td><td id=\"edited-marked\">" . 
		htmlspecialchars($edited) . 
		"</td><td id=\"deleted-marked\">" . 
		htmlspecialchars($deleted) . 
		"</td></tr>";
	}

	mysqli_close($con);
}

?>

