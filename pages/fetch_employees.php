<?php

session_start();


if(!isset($_SESSION["name"])){
    header("Location:../index.php");
    exit();
}

require_once '../dblogin.php';
$conn = mysqli_connect($hn, $un, $pw, $db);
if($conn->connect_error) die($conn->connect_error);


$username = $_SESSION['name'];
$query = "select * from User where username = '$username' ";
$result = mysqli_query($conn, $query);
if (!$result) die($conn->error);
$user = mysqli_fetch_assoc($result);
$uId = $user['id'];


if($user['employeeOf']){
    header("Location:../index.php");
    exit();
}


if($_GET['comp']) {
    $compId = $_GET['comp'];
    $query = "select * from Company where id = '$compId' ";
    $result = mysqli_query($conn, $query);
    $company = mysqli_fetch_assoc($result);

    if(!$company || $uId != $company['employer_id'] ){
        header("Location:../index.php");
        exit();
    }

    $query = "select * from User where employeeOf = '$compId'";
    $result = mysqli_query($conn, $query);
    if (!$result) die($conn->error);
    $rows = $result->num_rows;
    echo '<option selected="selected" hidden value="null">--Επιλέξτε--</option>';
    if(!$rows)
        echo '<option disabled>Δεν υπάρχουν καταχωρημένοι εργαζόμενοι</option>';

    while ($row = mysqli_fetch_assoc($result))
        echo '<option value="'.$row['id'].'">' . $row['firstname'] . ' ' . $row['lastname'] . ' ' . $row['id'] . '</option>';
}
else{
    header("Location:../index.php");
    exit();
}

?>
