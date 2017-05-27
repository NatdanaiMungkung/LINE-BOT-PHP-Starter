<?php
$url = parse_url(getenv("CLEARDB_DATABASE_URL"));

$server = $url["host"];
$username = $url["user"];
$password = $url["pass"];
$db = substr($url["path"], 1);

$conn = new mysqli($server, $username, $password, $db);

if ($conn->connect_errno) {
    printf("Connect failed: %s\n", $conn->connect_error);
    exit();
}

/* Create table doesn't return a resultset */
$result = $conn->query("SELECT userId from LineIDInternal");
$a=array();
  while ($row = $result->fetch_assoc()) {
      //printf ("%s \n", $row["userId"]);
      array_push($a,$row["userId"]);
  }
echo  (json_encode($a));

?>
