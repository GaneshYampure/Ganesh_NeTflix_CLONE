<?php
$host="localhost";
$user="root";
$password="";
$db="netfliex";

$con=new mysqli($host,$user,$password,$db);
if($con->connect_error){
    echo "db error";
    die;

}
?>