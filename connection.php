<?php
date_default_timezone_set('Asia/Kuala_Lumpur');

$servername = "localhost";
$username = "root";
$password = "";
$database = "siswagig";
		
$conn = mysqli_connect($servername,$username,$password,$database);
if(!$conn)
{
    die("connection failed ".mysqli_connect_error());
}

// AUTO-DELETE expired open jobs (lepas duedate, belum ada freelancer)
mysqli_query($conn, "DELETE FROM applications WHERE job_id IN (
    SELECT id FROM jobs WHERE status='open' AND datetime < NOW()
)");
mysqli_query($conn, "DELETE FROM jobs WHERE status='open' AND datetime < NOW()");
?>