<?php header('Access-Control-Allow-Origin: *'); //разрешаем кроссдоменные запросы CORS  
?> 
<?php
$host = "localhost"; //replace with your hostname
$username = "kolegi"; //replace with your username
$password = "6I7z3I6k"; //replace with your password 
$db_name = "pov"; //replace with your database
$con = mysql_connect("$host", "$username", "$password") or die("cannot connect");
mysql_select_db("$db_name") or die("cannot select DB");
// $query = "Ф-86-29";
function search($query)
{
	$query = trim($query);
	$query = mysql_real_escape_string($query);
	$query = htmlspecialchars($query);
$sql = "SELECT * FROM `TABLE 28` WHERE `COL 1` LIKE '%$query%'";
//   $sql = "select * from emp_info"; //replace emp_info with your table name
$result = mysql_query($sql);
$json = array();
$json['pov_info'] = [];
if (mysql_num_rows($result)) {
	while ($row = mysql_fetch_row($result)) {
		$json['pov_info'][] = $row;
	}
}
$q = "SELECT * FROM `povkl` WHERE `name` LIKE '%$query%'";
$result_new = mysql_query($q);
$json['pov_new'] = [];
if (mysql_num_rows($result_new)) {
	while ($row_new = mysql_fetch_row($result_new)) {
		$json['pov_new'][] = $row_new;
	}
}
mysql_close($db_name);
echo json_encode($json);
}
if (!empty($_GET['query'])) {
	search($_GET['query']);
}
// please refer to our PHP JSON encode function tutorial for learning json_encode function in detail 
?>