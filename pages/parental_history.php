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


if(!$user['employeeOf']){
    header("Location:../index.php");
    exit();
}


$greek_days = array(
    "Monday" => "Δευτέρα",
    "Tuesday" => "Τρίτη",
    "Wednesday" => "Τετάρτη",
    "Thursday" => "Πέμπτη",
    "Friday" => "Παρασκευή",
    "Saturday" => "Σάββατο",
    "Sunday" => "Κυριακή");

$greek_months_srt = array(
    "Jan" => "Ιαν",
    "Feb" => "Φεβ",
    "Mar" => "Μάρ",
    "Apr" => "Απρ",
    "May" => "Μάι",
    "Jun" => "Ιούν",
    "Jul" => "Ιούλ",
    "Aug" => "Αύγ",
    "Sep" => "Σεπ",
    "Oct" => "Οκτ",
    "Nov" => "Νοέ",
    "Dec" => "Δεκ");




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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body id="top">
<!-- ################################################################################################ -->
<!-- ################################################################################################ -->
<!-- ################################################################################################ -->
<div class="contact popup">
    <div style="height: 600px" class="contact popup-box">
        <button type="button" class="contact popup-close">X</button>
        <div class="popup-details"><h4><b>Τύπος </b></h4><p>Γονική άδεια</p></div>
        <div class="popup-details"><h4 for="date1"><b>Ημ/<span style="text-transform: lowercase">νία</span> έναρξης </b></h4><p id="date1"></p></div>
        <div class="popup-details"><h4 for="date2"><b>Ημ/<span style="text-transform: lowercase">νία</span> λήξης </b></h4><p id="date2"></p></div>
        <div style="margin-bottom: 40px" class="popup-details"><h4 for="reason"><b>Λόγος άδειας </b></h4><p id="reason"></p></div>

    </div>
</div>





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
        <a href="EMPLOYEES/LEAVE/parental.php" class="go-to-button"><i class="fa fa-plus-square-o" aria-hidden="true"></i> Νέα Δήλωση</a>
        <!-- ################################################################################################ -->
    </header>

</div>

<div style="margin: 0 auto; width: 800px;" class="menu_content wrapper hoc">

    <?php
    $uId = $user['id'];
    $query = "select * from day_off where user_fk = '$uId' order by start_date DESC ";
    $result = mysqli_query($conn, $query);
    if (!$result) die($conn->error);
    if($result->num_rows == 0)
        echo '<h1  style="line-height:60px;text-align: center;text-transform: none; ">Δεν υπάρχουν γονικές άδειες καταχωρημένες<br><a  href="EMPLOYEES/LEAVE/parental.php">Δηλώστε την γονική άδεια σας</a></h1>';
    else echo '<h1 style="text-align: center; text-transform: none">Οι γονικές άδειες σας</h1>';


    while($row = mysqli_fetch_assoc($result)){
        echo '<div style="width: 800px; padding-bottom: 10px;" class="hist_item container row">';
        $st_day = date('d-m-Y', strtotime($row['start_date']));
        $st_dayName = date('l', strtotime($row['start_date']));
        $st_dayD = date('d', strtotime($row['start_date']));
        $st_dayM = $greek_months_srt[date('M', strtotime($row['start_date']))];
        $st_dayY = date('Y', strtotime($row['start_date']));
        $en_day = date('d-m-Y', strtotime($row['end_date']));
        $en_dayName = date('l', strtotime($row['end_date']));
        $en_dayD = date('d', strtotime($row['end_date']));
        $en_dayM = $greek_months_srt[date('M', strtotime($row['end_date']))];
        $en_dayY = date('Y', strtotime($row['end_date']));
        echo "<a class='col-md-10' onclick='contactOpenDetails(\"".$greek_days[$st_dayName].' '.$st_day."\",\"".$greek_days[$en_dayName].' '.$en_day ."\",\"".$row['reason']."\")'>";

        echo "Γονική άδεια ";
        echo $greek_days[date('l', strtotime($row['start_date']))].' ';
        echo $st_dayD."-".$st_dayM."-".$st_dayY.' έως ' ;
        echo $greek_days[date('l', strtotime($row['end_date']))].' ';
        echo $en_dayD."-".$en_dayM."-".$en_dayY;

        echo'</a></footer></div>';

    }

    ?>
</div>
<script src="../scripts/contact_popup.js"></script>
<script>
    function contactOpenDetails (date1, date2,reason) {
        popup_cont.classList.add('popup-active');
        d1 = document.getElementById('date1');
        d2 = document.getElementById('date2');
        r = document.getElementById('reason');

        d1.innerText = date1;
        d2.innerText = date2;
        r.innerText = reason;

        if (history.pushState) {
            var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?details=true';
            window.history.pushState({path:newurl},'',newurl);
        }


    };
</script>
</body>
</html>
