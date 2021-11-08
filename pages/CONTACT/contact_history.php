<?php

session_start();


if(!isset($_SESSION["name"])){
    header("Location:../../index.php");
    exit();
}

require_once '../../dblogin.php';
$conn = mysqli_connect($hn, $un, $pw, $db);
if($conn->connect_error) die($conn->connect_error);


$username = $_SESSION['name'];
$query = "select * from User where username = '$username' ";
$result = mysqli_query($conn, $query);
if (!$result) die($conn->error);
$user = mysqli_fetch_assoc($result);


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


if(isset($_POST['delete'])){
    $date = $_POST['del_appoint'];
    $query = "delete from Appointment where date = '$date'";
    $result = mysqli_query($conn, $query);
    if (!$result) die($conn->error);


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
    <link href="../../styles/layout.css" rel="stylesheet" type="text/css" media="all">
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
    <div style="height: 700px;width: 400px" class="contact popup-box">
        <button type="button" class="contact popup-close">X</button>
        <h3 style="margin-bottom: 40px" >Ημ/<span>νια</span> Επίσκεψης:<div id = "dateName"></div><div id="date"></div></h3>
        <div class="popup-details"><h4 for="fname"><b>Όνομα </b></h4><p id="fname"></p></div>
        <div class="popup-details"><h4 for="lname"><b>Επώνυμο </b></h4><p id="lname"></p></div>
        <div class="popup-details"><h4 for="email"><b>Email </b></h4><p id="email"></p></div>
        <div class="popup-details"><h4 for="phone"><b>Τηλέφωνο </b></h4><p id="phone"></p></div>
        <div class="popup-details"><h4 for="phone"><b>Λόγος επίσκεψης </b></h4><p id="reason"></p></div>
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
                    <a href="../profile.php">ΤΟ ΠΡΟΦΙΛ ΜΟΥ</a>
                    <a href="../logout.php">ΑΠΟΣΥΝΔΕΣΗ</a>
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
            <a href="../../index.php"><img src="../../images/logo.jpg"  /></a>
        </div>

        <a href="../profile.php" class="prev_button">&laquo; Προφίλ</a>
        <a href="appointment.php" class="go-to-button"><i class="fa fa-plus-square-o" aria-hidden="true"></i> Νέο Ραντεβού</a>
        <!-- ################################################################################################ -->
    </header>

</div>

<div style="margin: 0 auto; width: 800px;" class="menu_content wrapper hoc">

        <?php
            $uId = $user['id'];
            $query = "select * from Appointment where user_fk = '$uId' order by date DESC ";
            $result = mysqli_query($conn, $query);
            if (!$result) die($conn->error);
            if($result->num_rows == 0)
                echo '<h1  style="line-height:60px;text-align: center;text-transform: none; ">Δεν υπάρχουν διαθέσιμα ραντεβού<br><a  href="appointment.php">Κλείστε το ραντεβού σας</a></h1>';
            else echo '<h1 style="text-align: center; text-transform: none">Τα ραντεβού σας</h1>';


            while($row = mysqli_fetch_assoc($result)){
                echo '<div style="width: 800px; padding-bottom: 0px; height: max-content" class="hist_item container row">';
                $day = date('d-m-Y H:i:s', strtotime($row['date']));
                $dayName = date('l', strtotime($row['date']));
                $dayD = date('d', strtotime($row['date']));
                $dayM = $greek_months_srt[date('M', strtotime($row['date']))];
                $dayY = date('Y', strtotime($row['date']));
                echo "<a class='col-md-10' onclick='contactOpenDetails(\"". $day ."\",\"".$greek_days[$dayName]."\",\"".$row['firstname']."\",\"".$row['lastname']."\",\"".$row['email']."\",\"".$row['phone']."\",\"".$row['reason']."\")'>";

                echo $greek_days[date('l', strtotime($row['date']))].' ';
                echo $dayD."-".$dayM."-".$dayY.' ' ;
                echo date('H:i:s', strtotime($row['date']));
                echo'</a>';
                if(strtotime(date('d-m-Y')) < strtotime(date('d-m-Y', strtotime($row['date'])))) echo '<footer style="display: inline; padding-bottom: 0" class="col-md-2 container row" >
                    <form action="" method="post">
                    <input type="hidden" name="del_appoint" value="'.$row["date"].'
                    "><button onclick="return confirm(\'Θέλετε να ακυρώσετε το ραντεβού σας;\')" type="submit" name="delete" style="color: black" class="btn btn-link" >ΑΚΥΡΩΣΗ</button>
                    </form></footer>';
                echo '</div>';

            }

        ?>
</div>
<script src="../../scripts/contact_popup.js"></script>
<script>
    function contactOpenDetails (params, name,f,l,e,p,r) {
        popup_cont.classList.add('popup-active');
        fname = document.getElementById('fname');
        lname = document.getElementById('lname');
        em = document.getElementById('email');
        ph = document.getElementById('phone')
        re = ph = document.getElementById('reason');

        dateN.innerText = name;
        date.innerText = params
        fname.innerText = f;
        lname.innerText = l;
        em.innerText = e;
        ph.innerText = p;
        re.innerText = r;

        if (history.pushState) {
            var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?details=true';
            window.history.pushState({path:newurl},'',newurl);
        }


    };
</script>
</body>
</html>

