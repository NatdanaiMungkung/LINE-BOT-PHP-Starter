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
if ($conn->query("CREATE TABLE LineIDInternal (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
userId VARCHAR(50) NOT NULL
)") === TRUE) {
    printf("Table myCity successfully created.\n");
}

printf("Hello World.\n");
?>
