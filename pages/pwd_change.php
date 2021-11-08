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
$errmsg = null;

if(isset($_POST['submit'])) {

    $curr_pwd = $_POST['curr_pwd'];
    $new_pwd = password_hash($_POST['pwd'], PASSWORD_DEFAULT);
    $uId = $user['id'];

    if (password_verify($curr_pwd, $user['password'])) {
        $query = "update User set password = '$new_pwd' where id = '$uId'";
        mysqli_query($conn, $query);
        $_SESSION['success'] = true;
        header("Location: profile.php");
    }
    else
        $errmsg = "<div class=\"alert alert-danger\">
                        <strong>Ο τρέχων κωδικός πρόσβασης δεν είναι σωστός!</strong></div>";
}


?>

<!DOCTYPE html>
<!--
Template Name: Wavefire
Author: <a href="https://www.os-templates.com/">OS Templates</a>
Author URI: https://www.os-templates.com/
Copyright: OS-Templates.com
Licence: Free to use under our free template licence terms
Licence URI: https://www.os-templates.com/template-terms
-->
<html lang="">
<!-- To declare your language - read more here: https://www.w3.org/International/questions/qa-html-language-declarations -->
<head>
    <title>Υπουργείο Εργασίας & Κοινωνικών Υποθέσεων</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link href="../styles/layout.css" rel="stylesheet" type="text/css" media="all">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body id="top">
<!-- ################################################################################################ -->
<!-- ################################################################################################ -->
<!-- ################################################################################################ -->




<div class="wrapper row0">
    <header id="header" class="hoc clear">
        <?php
        $name = $_SESSION['name'];
        echo <<<END
            <div id="profile">
                <button class="btn btn-light" ><label>$name </label>
                <i class="fas fa-user-circle"></i></button>
                <div class="content">
                    <a href="profile.php">ΤΟ ΠΡΟΦΙΛ ΜΟΥ</a>
                    <a href="logout.php">ΑΠΟΣΥΝΔΕΣΗ</a>
                </div>
                <script>
                    var profile = document.getElementById('profile');
                    var icon = document.getElementsByTagName('i')[0];
                    var clicked = false;
                    profile.addEventListener('click',(e) =>{
                        e.stopImmediatePropagation();
                        if(!clicked){
                            profile.getElementsByClassName('content')[0].classList.add('disp')
                            clicked = true;
                        }
                        else{
                            profile.getElementsByClassName('content')[0].classList.remove('disp')
                            clicked = false;
                            
                        }
                    });
                    window.addEventListener('click', () => {
                        profile.getElementsByClassName('content')[0].classList.remove('disp')
                        clicked = false;
                    });
                    
                </script>
            </div>
            END;
        ?>


        <!-- ################################################################################################ -->
        <div  id="logo" class="first">
            <a href="../index.php"><img src="../images/logo.jpg"  /></a>
        </div>

        <a href="profile.php" class="prev_button">&laquo; Προφίλ</a>
        <!-- ################################################################################################ -->
    </header>

</div>


<form id="form" class="menu_content wrapper hoc form-box" action="" method="post">

    <h1>Αλλαγή Κωδικού Πρόσβασης</h1>
    <?php
    if($errmsg != null)
        echo $errmsg;
    ?>
    <div class="form-controls">
        <label for="curr_pwd">Τρέχων κωδικός <span class="text-danger">*</span></label>
        <input name="curr_pwd" type="password">
        <i class="fas fa-check-circle"></i>
        <i class="fas fa-exclamation-circle"></i>
        <small class="text-danger"></small>
    </div>
    <div class="form-controls">
        <label for="pwd">Νέος κωδικός <span class="text-danger">*</span></label>
        <input name="pwd" type="password">
        <i class="fas fa-check-circle"></i>
        <i class="fas fa-exclamation-circle"></i>
        <small class="text-danger"></small>
    </div>
    <div class="form-controls">
        <label for="pwdConf">Επαλήθευση Κωδικού</label>
        <input name="pwdConf" type="password">
        <i class="fas fa-check-circle"></i>
        <i class="fas fa-exclamation-circle"></i>
        <small class="text-danger"></small>
    </div>
    <div class="form-controls">
        <button type="submit" name="submit" style="background-color: dodgerblue;" class="btn btn-light">ΕΝΗΜΕΡΩΣΗ</button>
    </div>
</form>


</div>
</body>
<script src="../scripts/signup.js"></script>
</html>
