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
$errmsg = null;

if(!$user['employeeOf']){
    header("Location:../../../index.php");
    exit();
}


if(isset($_POST['submit'])) {

    $startDate = date("Y-m-d",strtotime($_POST['startDate']));
    $endDate = date("Y-m-d",strtotime($_POST['endDate']));
    $ch_fname = $_POST['ch-fname'];
    $ch_lname = $_POST['ch-lname'];
    $ch_age = $_POST['ch-age'];
    $reason = $_POST['reason'];
    $comments = $_POST['comments'];
    $uId = $user['id'];
    $compId = $user['employeeOf'];


    $query = "select * from day_off where user_fk = '$uId' and ((start_date between '$startDate' and '$endDate') or (end_date between '$startDate' and '$endDate')) ";
    $result = mysqli_query($conn, $query);
    if (!$result) die($conn->error);
    $dayOffExists = $result->num_rows;


    if($dayOffExists){
        $errmsg = "<div class=\"alert alert-danger\">
                            <strong>Υπάρχει ήδη μια άλλη άδεια σας που έχει δηλωθεί στο διάστημα αυτό!</strong></div>";
    }
    else {
        $query = "insert into day_off values (null,'$ch_fname','$ch_lname','$ch_age','$reason','$comments','$startDate','$endDate','$uId','$compId')";
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
    <h1 style="margin-bottom: 10px" >ΓΟΝΙΚΗ ΑΔΕΙΑ</h1>
    <h2 style="margin-bottom: 50px; margin-top: 0px" >ΔΗΛΩΣΗ ΑΔΕΙΑΣ ΕΙΔΙΚΟΥ ΣΚΟΠΟΥ</h2>


    <?php
    if($errmsg != null)
        echo $errmsg;
    ?>


    <label for="startDate">Ημερομηνία έναρξης της άδειας <span class="text-danger">*</span></label>
    <div  class="form-controls other_forms">
        <input type="text" name="startDate" onkeydown="return false" >
        <i class="fa fa-calendar"></i>
        <small class="text-danger"></small>
    </div>

    <label for="endDate">Ημερομηνία λήξης της άδειας <span class="text-danger">*</span></label>
    <div  class="form-controls other_forms">
        <input  type="text" name="endDate" onkeydown="return false" class="datepick">
        <i class="fa fa-calendar"></i>
        <small class="text-danger"></small>
    </div>


    <label for="ch-fname">Όνομα Παιδιού <span class="text-danger">*</span></label>
    <div class="form-controls other_forms">
        <input name="ch-fname" type="text">
        <i class="fas fa-check-circle"></i>
        <i class="fas fa-exclamation-circle"></i>
        <small class="text-danger"></small>
    </div>
    <label for="ch-lname">Επώνυμο Παιδιού <span class="text-danger">*</span></label>
    <div class="form-controls other_forms">
        <input name="ch-lname" type="text">
        <i class="fas fa-check-circle"></i>
        <i class="fas fa-exclamation-circle"></i>
        <small class="text-danger"></small>
    </div>

    <label for="ch-age">Ηλικία Παιδιού <span class="text-danger">*</span></label>
    <div class="form-controls other_forms">
        <select name="ch-age" style="width: 250px">
            <option selected="selected" hidden value="null">--Επιλέξτε--</option>
            <option>0-6</option>
            <option>7-12</option>
            <option>13-17</option>
            <option>>17</option>
        </select>
        <small class="text-danger"></small>
    </div>


    <label for="reason">Αιτία <span class="text-danger">*</span></label>
    <div class="form-controls other_forms">
        <select name="reason" style="height: 30px">
            <option selected="selected" hidden value="null">--Επιλέξτε--</option>
            <option>Άδεια Θηλασμού & Φροντίδας παιδιών</option>
            <option>Ειδική άδεια μητρότητας</option>
            <option>Άδεια γέννησης παιδιού</option>
            <option>Άδεια για τη φροντίδα υιοθετημένων παιδιών</option>
            <option>Γονική Άδεια ανατροφής παιδιού</option>
            <option>Άδεια για ασθένεια μελών οικογένειας</option>
            <option>Άδεια για παρακολούθηση σχολικής επίδοσης</option>
            <option>Μειωμένο ωράριο για παιδιά με αναπηρία</option>
            <option>Άδεια για μονογονεϊκές οικογένειες-Τρίτεκνοι-Πολύτεκνοι</option>
            <option>Γονική Άδεια παιδιού το οποίο πάσχει από νόσημα που απαιτεί μεταγγίσεις αίματος</option>
            <option>Άδεια φροντίδας παιδιού με τη παρένθετη μητρότητα</option>
        </select>
        <small style="position: absolute; right: 180px" class="text-danger"></small>
    </div>



    <label for="comments">Σχόλια</label>
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
<script src="../../../scripts/parental_form.js"></script>
</body>
</html>

