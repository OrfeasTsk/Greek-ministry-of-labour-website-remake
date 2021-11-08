<?php

session_start();


if(!isset($_SESSION["name"])){
    header("Location:../../../index.php");
    exit();
}

require_once '../../../dblogin.php';
$conn = mysqli_connect($hn, $un, $pw, $db);
if($conn->connect_error) die($conn->connect_error);


$username = $_SESSION['name'];
$query = "select * from User where username = '$username' ";
$result = mysqli_query($conn, $query);
if (!$result) die($conn->error);
$user = mysqli_fetch_assoc($result);
$uId = $user['id'];
$errmsg = null;

if($user['employeeOf']){
    header("Location:../../../index.php");
    exit();
}




if(isset($_POST['submit'])) {

    $startDate = date("Y-m-d",strtotime($_POST['startDate']));
    $endDate = date("Y-m-d",strtotime($_POST['endDate']));
    $emplId = $_POST['empl'];
    $compId = $_POST['comp'];
    $type = $_POST['category'];
    $comments = $_POST['comments'];



    $query = "select * from Suspension where company_id ='$compId' and employee_id ='$emplId' and ((start_date between '$startDate' and '$endDate') or (end_date between '$startDate' and '$endDate')) ";
    $result = mysqli_query($conn, $query);
    if (!$result) die($conn->error);
    $suspExists = $result->num_rows;


    if($suspExists){
        $errmsg = "<div class=\"alert alert-danger\">
                            <strong>Υπάρχει ήδη μια άλλη δήλωση για τον εργαζόμενο στο διάστημα αυτό!</strong></div>";
    }
    else {
        $query = "insert into Suspension values (null,'$type','$comments','$startDate','$endDate','$emplId','$compId')";
        mysqli_query($conn, $query);
        $_SESSION['success'] = true;
        header("Location: ../../profile.php");
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
    <link href="../../../styles/layout.css" rel="stylesheet" type="text/css" media="all">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="//apps.bdimg.com/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="//apps.bdimg.com/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://ajax.aspnetcdn.com/ajax/jquery.ui/1.10.4/themes/flick/jquery-ui.css">
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
                    <a href="../../profile.php">ΤΟ ΠΡΟΦΙΛ ΜΟΥ</a>
                    <a href="../../logout.php">ΑΠΟΣΥΝΔΕΣΗ</a>
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
            <a href="../../../index.php"><img src="../../../images/logo.jpg"  /></a>
        </div>

        <a onclick="history.back()" class="prev_button">&laquo; Επιστροφή</a>
        <!-- ################################################################################################ -->
    </header>

</div>

<!-- ################################################################################################ -->
<!-- ################################################################################################ -->
<!-- ################################################################################################ -->
<form id="form" class="menu_content wrapper hoc form-box" action="" method="post" autocomplete="off">
    <h1 style="line-height: 40px; text-align: center" >ΔΗΛΩΣΗ ΑΝΑΣΤΟΛΗΣ ΣΥΜΒΑΣΗΣ ΕΡΓΑΣΙΑΣ ΚΑΙ<br/> ΕΞ' ΑΠΟΣΤΑΣΕΩΣ ΕΡΓΑΣΙΑΣ</span></h1>


    <?php
    if($errmsg != null)
        echo $errmsg;
    ?>

    <label for="startDate">Ημερομηνία έναρξης <span class="text-danger">*</span></label>
    <div  class="form-controls other_forms">
        <input type="text" name="startDate" onkeydown="return false" >
        <i class="fa fa-calendar"></i>
        <small class="text-danger"></small>
    </div>

    <label for="endDate">Ημερομηνία λήξης <span class="text-danger">*</span></label>
    <div  class="form-controls other_forms">
        <input  type="text" name="endDate" onkeydown="return false" class="datepick">
        <i class="fa fa-calendar"></i>
        <small class="text-danger"></small>
    </div>


    <label for="comp">Εταιρεία</label>
    <div class="form-controls other_forms">
        <select name="comp" style="height: 30px">
            <?php
                $query = "select * from Company where employer_id = '$uId'";
                $result = mysqli_query($conn, $query);
                if (!$result) die($conn->error);
                while($row = mysqli_fetch_assoc($result))
                    echo '<option value="'.$row['id'].'">'.$row['name'].'</option.>';
            ?>
        </select>
        <small class="text-danger"></small>
    </div>


    <label for="empl">Εργαζόμενος προς δήλωση <span class="text-danger">*</span></label>
    <h5 class="text-muted">(Όνομα Επώνυμο ΑΦΜ)</h5>
    <div class="form-controls other_forms">
        <select name="empl" style="height: 30px"></select>
        <small class="text-danger"></small>
    </div>


    <label for="category">Κατηγορία <span class="text-danger">*</span></label>
    <div class="form-controls">
        <p style="position: relative; right: 60px; top:5px; margin: 0;">
            Προσωρινή αναστολή<input name="category" type="radio" value="suspension">
        </p>
        <p style="position: relative; right: 5px; top: 5px; margin: 0;">
            Τηλεργασία<input name="category" type="radio" value="telework">
        </p>
        <small id="radioWarning"  style="width: max-content; right: 62px" class="text-danger"></small>
    </div>


    <label style="margin-top: 10px" for="comments">Σχόλια</label>
    <div class="form-controls">
        <textarea name="comments" rows="5" cols="70" maxlength="250"></textarea>
    </div>



    <div class="form-controls">
        <button type="submit" name="submit" style="background-color: dodgerblue;" class="btn btn-light">ΔΗΛΩΣΗ</button>
    </div>
</form>
</div>



<!-- ################################################################################################ -->
<!-- ################################################################################################ -->
<!-- ################################################################################################ -->
<!--<div  id="foot" class="wrapper row5">
    <div id="copyright" class="hoc clear">

        <p class="fl_left">Copyright &copy; 2021 - All Rights Reserved - <a style="background-color: inherit" href="../../../index.php">www.ypakp.gr </a></p>

    </div>
</div> -->
<!-- ################################################################################################ -->
<!-- ################################################################################################ -->
<!-- ################################################################################################ -->
<a style="background-color: royalblue;" id="backtotop" href="#top"><i class="fas fa-chevron-up"></i></a>
<!-- JAVASCRIPTS -->
<script src="../../../scripts/jquery.backtotop.js"></script>
<script src="../../../scripts/other_forms.js"></script>
<script src="../../../scripts/contract_susp_form.js"></script>
</body>
</html>

