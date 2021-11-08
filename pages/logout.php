<?php
session_start();

if(!isset($_SESSION["name"])){
    header("Location:../index.php");
    exit();
}


if(isset($_SESSION["success"]))
    unset($_SESSION["success"]);

if(isset($_SESSION["name"])) {
    unset($_SESSION["name"]);
    header("Location: ../index.php");
}

session_destroy();

?>
