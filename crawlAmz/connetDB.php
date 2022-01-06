<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname="craw";


// Create connection
$conn= mysqli_connect($servername, $username, $password, $dbname);

function importdata($conn, $link){
    $qr= "INSERT INTO `link`(`link`) VALUES ('$link')";
   if($conn->query($qr)==true){
       echo "đã crawl ok";
   }else {
       echo "có lỗi";
   };

    }
    $link = "chinh dẹptrai";
      importdata($conn, $link);
    