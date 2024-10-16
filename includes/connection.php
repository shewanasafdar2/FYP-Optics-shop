<?php 
ob_start();
session_start();
$host = "localhost";
$username = "root";
$password = "";
$dbname = "oos_db";

$con = mysqli_connect($host,$username,$password,$dbname);



?>