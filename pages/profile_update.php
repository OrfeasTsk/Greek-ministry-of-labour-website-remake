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

    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $firstname = $_POST['fname'];
    $lastname = $_POST['lname'];
    $uId = $user['id'];

    if($email != $user['email']) {
        $query = "select * from User where email = '$email'";
        $result = mysqli_query($conn, $query);
        if (!$result) die($conn->error);
        $emailExists = $result->num_rows;
        if($emailExists)
            $errmsg =  "<div class=\"alert alert-danger\">
                        <strong>Η διεύθυνση e-mail χρησιμοποείται ήδη!</strong></div>";
        else {
            $query = "update User set firstname='$firstname',lastname='$lastname',email='$email',phone ='$phone' where id = '$uId'";
            mysqli_query($conn, $query);
            $_SESSION['success'] = true;
            header("Location: profile.php");
        }
    }
    else{
        $query = "update User set firstname='$firstname',lastname='$lastname',phone ='$phone' where id = '$uId'";
        mysqli_query($conn, $query);
        $_SESSION['success'] = true;
        header("Location: profile.php");
    }

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

    <h1>Ενημέρωση Ατομικών Στοιχείων</h1>
    <?php
    if($errmsg != null)
        echo $errmsg;
    ?>

    <div class="form-controls">
        <label for="fname">Όνομα <span class="text-danger">*</span></label>
        <?php echo '<input name="fname" type="text" value="'.$user['firstname'].'">'?>
        <i class="fas fa-check-circle"></i>
        <i class="fas fa-exclamation-circle"></i>
        <small class="text-danger"></small>
    </div>
    <div class="form-controls">
        <label for="lname">Επώνυμο <span class="text-danger">*</span></label>
        <?php echo '<input name="lname" type="text" value="'.$user['lastname'].'">'?>
        <i class="fas fa-check-circle"></i>
        <i class="fas fa-exclamation-circle"></i>
        <small class="text-danger"></small>
    </div>

    <div class="form-controls">
        <label for="email">E-mail <span class="text-danger">*</span></label>
        <?php echo '<input name="email" type="text" value="'.$user['email'].'">'?>
        <i class="fas fa-check-circle"></i>
        <i class="fas fa-exclamation-circle"></i>
        <small class="text-danger"></small>
    </div>
    <div class="form-controls">
        <label for="phone">Τηλέφωνο</label>
        <?php echo '<input name="phone" type="text" value="'.$user['phone'].'">'?>
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
<script>
    var input = document.getElementsByTagName("input");
    const event = new Event('blur');
    for(let i = 0; i < input.length; i++)
        input[i].dispatchEvent(event);
</script>
</html>
