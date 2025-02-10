<?php

$conn = new mysqli('localhost','root','','student_management_system');

if($conn->connect_error){
    die('Connection Failed : '.$conn->connect_error);
}
else{
    echo "";
}


?>