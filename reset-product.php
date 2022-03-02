<?php

$servername = "localhost";
$username = "wholesaleling_dbuser";
$password = "PunchBag386**";
$database = "wholesaleling_api";

$servername2 = "localhost";
$username2 = "wholesaleling_dbuser";
$password2 = "PunchBag386**";
$database2 = "wholesaleling_magento";

//$username2 = "uu79r73232863";
//$password2 = "devmg@12345";
//$database2 = "db73ng9mf9sq6b";

$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn2 = new mysqli($servername2, $username2, $password2);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->select_db($database);
$conn2->select_db($database2);

$conn->query("UPDATE parent_product SET imported = 2");

echo 'reset flag';

$conn->close();
$conn2->close();

