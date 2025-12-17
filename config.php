<?php
$host='localhost'; $db='dictionary'; $user='root'; $pass='';
$conn=new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4",$user,$pass);
session_start();