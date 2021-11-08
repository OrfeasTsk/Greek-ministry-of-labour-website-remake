<?php
session_start();

require_once '../../dblogin.php';
$conn = mysqli_connect($hn, $un, $pw, $db);
if($conn->connect_error) die($conn->connect_error);
$error_msg = null;
$user = null;


if(isset($_POST['login_submit'])){
    $username = $_POST['login_name'];
    $password = $_POST['login_pwd'];
    $query = "select * from User where username = '$username'";
    $result = mysqli_query($conn, $query);
    if (!$result) die($conn->error);
    $rows = $result->num_rows;

    if($rows == 1){
        $user = mysqli_fetch_assoc($result);
        if(password_verify($password,$user['password'])) {
            $_SESSION['name'] = $username;
            header("Location: date.php");
        }
        else{
            $error_msg = "<div class=\"alert alert-danger\">
                            <strong>Ο κωδικός πρόσβασης δεν είναι σωστός!</strong></div>";
        }
    }
    else{
        $error_msg = "<div class=\"alert alert-danger\">
                            <strong>Το όνομα χρήστη δεν υπάρχει!</strong></div>";
    }

}

if(isset($_SESSION['name'])){
    $username = $_SESSION['name'];
    $query = "select * from User where username = '$username' ";
    $result = mysqli_query($conn, $query);
    if (!$result) die($conn->error);
    $user = mysqli_fetch_assoc($result);

}


if(isset($_POST['contact_submit']) ){
    $firstname = $_POST['fname'];
    $lastname = $_POST['lname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $reason = $_POST['reason'];
    parse_str($_SERVER['QUERY_STRING'], $output);
    $time = explode(":", $output['time']);
    $date = date("Y-m-d H:i:s",strtotime($output['date']."+".$time[0]. " hours ".$time[1]." minutes"));

    $query = "select * from Appointment where 'date' = '$date'";
    $result = mysqli_query($conn, $query);
    if (!$result) die($conn->error);
    $rows = $result->num_rows;


    if($rows == 0){
        if($user){
            $uId = $user['id'];
            $query = "insert into Appointment values ('$date','$firstname','$lastname','$email','$phone','$reason','$uId')";
        }
        else
            $query = "insert into Appointment values ('$date','$firstname','$lastname','$email','$phone','$reason',null)";
        mysqli_query($conn, $query);

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
    <link href="../../styles/layout.css" rel="stylesheet" type="text/css" media="all">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body id="top">
<!-- ################################################################################################ -->
<!-- ################################################################################################ -->
<!-- ################################################################################################ -->

<?php

if(!isset($_SESSION['name'])) {
    echo <<<END
        <div class="login popup">
            <form class="login popup-box" action="" method="post">
                <button type="button" class="login popup-close">X</button>
                <h2>Σύνδεση Χρήστη</h2>
        END;

    if ($error_msg != null)
        echo $error_msg;

    echo <<<END
                <div class="popup-controls">
                    <label for="login_name">Όνομα Χρήστη</label>
                    <input name="login_name" type="text">
                    <i class="fas fa-exclamation-circle"></i>
                    <small class="text-danger">* Εισάγετε το όνομα χρήστη</small>
                </div>
                <div class="popup-controls">
                    <label for="login_pwd">Κωδικός</label>
                    <input name="login_pwd" type="password">
                    <i class="fas fa-exclamation-circle"></i>
                    <small class="text-danger">* Εισάγετε τον κωδικό σας</small>
                </div> 
                <button type="submit" name="login_submit" style="background-color: dodgerblue;" class="btn btn-light popup-submit">Συνδεση</button>
                <a href="../signup.php" class="btn btn-success">ΔΗΜΙΟΥΡΓΙΑ ΝΕΟΥ ΛΟΓΑΡΙΑΣΜΟΥ</a>
            </form>
        </div>
        END;
}



$contact = "<div class=\"contact popup\"><form style=\"height: 700px; width: 400px\" class=\"contact popup-box\" action=\"\" method=\"post\">
            <button type=\"button\" class=\"contact popup-close\">X</button><h3 >Ημ/<span>νια</span> Επίσκεψης:<div id = \"dateName\"></div><div id=\"date\"></div></h3>
            <div class=\"popup-controls\"><label for=\"fname\">Όνομα <span class=\"text-danger\">*</span></label><input name=\"fname\" type=\"text\" ";

if(isset($_SESSION['name']))
    $contact.= "value=".$user['firstname'];
$contact.= '><i class="fas fa-exclamation-circle"></i><small class="text-danger">* Εισάγετε το όνομα σας</small></div>
            <div class="popup-controls"><label for="lname">Επώνυμο <span class="text-danger">*</span></label><input name="lname" type="text" ';

if(isset($_SESSION['name']))
    $contact.= "value=".$user['lastname'];

$contact.= '><i class="fas fa-exclamation-circle"></i><small class="text-danger">* Εισάγετε το επώνυμο σας</small></div>
            <div class="popup-controls"><label for="email">E-mail <span class="text-danger">*</span></label><input name="email" type="text" ';

if(isset($_SESSION['name']))
    $contact.= "value=".$user['email'];

$contact.= '><i class="fas fa-exclamation-circle"></i><small class="text-danger">* Εισάγετε το e-mail σας</small></div>
            <div class="popup-controls"><label for="phone">Τηλέφωνο</label><input name="phone" type="text" ';

if(isset($_SESSION['name']))
    $contact.= "value=".$user['phone'];

$contact.='><i class="fas fa-exclamation-circle"></i><small class="text-danger">* Εισάγετε το τηλέφωνο σας</small></div>
            <div class="popup-controls"><label for="reason">Λόγος επίσκεψης</label><select style="height: 30px" name="reason">
            <option selected="selected" value="Ραντεβού με την Γραμματεία του Υπουργείου">Ραντεβού με την Γραμματεία του Υπουργείου</option>
            <option selected="selected" value="Ραντεβού με την Γραμματεία Κοινωνικών Ασφαλίσεων">Ραντεβού με την Γραμματεία Κοινωνικών Ασφαλίσεων</option>
            <option selected="selected" value="Ραντεβού με την Γραμματεία Πρόνοιας">Ραντεβού με την Γραμματεία Πρόνοιας</option>
            <option selected="selected" value="Ραντεβού με το Σώμα Επιθεώρησης Εργασίας">Ραντεβού με το Σώμα Επιθεώρησης Εργασίας</option>
            </select></div>';


$contact.= '<button type="submit" name="contact_submit" style="background-color: dodgerblue; margin-top: 25px;" class="btn btn-light popup-submit">ΕΠΙΒΕΒΑΙΩΣΗ</button></form></div>';

echo $contact;

?>


<div class="wrapper row0">
    <header id="header" class="hoc clear">
        <?php
        if(!isset($_SESSION['name']))
            echo "<div id=\"log_sign\" ><button id=\"login_button\" class=\"btn btn-light login popup-trigger\">Συνδεση</button> <a href=\"../signup.php\" id=\"signup\" class=\"btn btn-light\">Εγγραφη</a></div>";
        else {
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
        }
        ?>


        <!-- ################################################################################################ -->
        <div  id="logo" class="first">
            <a href="../../index.php"><img src="../../images/logo.jpg"  /></a>
        </div>


        <!-- ################################################################################################ -->
    </header>
</div>


<!-- ################################################################################################ -->
<!-- ################################################################################################ -->
<!-- ################################################################################################ -->
<div  class="wrapper row1">
    <section class="hoc clear">
        <!-- ################################################################################################ -->
        <nav id="mainav">
            <ul class="clear">
                <li class="active"><a href="../../index.php">ΑΡΧΙΚΗ</a></li>
                <li><a class="drop" href="../EMPLOYEES/employees.php">ΕΡΓΑΖΟΜΕΝΟΙ</a>
                    <ul>
                        <li><a href="../EMPLOYEES/LEAVE/leave.php">ΑΔΕΙΕΣ</a></li>
                        <li><a href="../MISC/under_constr.html">ΕΠΙΔΟΜΑΤΑ</a></li>
                        <li><a href="../MISC/under_constr.html">ΣΥΜΒΑΣΕΙΣ ΕΡΓΑΣΙΑΣ</a></li>
                        <li><a href="../EMPLOYEES/WORKCOND/workcond.php">ΣΥΝΘΗΚΕΣ ΕΡΓΑΣΙΑΣ</a></li>
                    </ul>
                </li>
                <li><a class="drop" href="../EMPLOYERS/employers.php">ΕΡΓΟΔΟΤΕΣ</a>
                    <ul>
                        <li><a href="../MISC/under_constr.html">ΕΠΙΧΕΙΡΗΣΙΑΚΑ ΘΕΜΑΤΑ</a></li>
                        <li><a href="../EMPLOYERS/WORKFORCE/workforce.php">ΑΝΘΡΩΠΙΝΟ ΔΥΝΑΜΙΚΟ</a></li>
                    </ul>
                </li>
                <li><a class="drop" href="../other_groups.php">ΑΛΛΕΣ ΟΜΑΔΕΣ</a>
                    <ul>
                        <li><a class="drop" href="../MISC/under_constr.html">ΣΥΝΤΑΞΙΟΥΧΟΙ</a>
                            <ul>
                                <li><a href="../MISC/under_constr.html">ΣΥΝΤΑΞΕΙΣ</a></li>
                            </ul>
                        </li>
                        <li><a class="drop" href="../MISC/under_constr.html">ΑΝΕΡΓΟΙ</a>
                            <ul>
                                <li><a href="../MISC/under_constr.html">ΠΡΟΓΡΑΜΜΑΤΑ ΥΠΟΣΤΗΡΙΞΗΣ</a></li>
                            </ul>
                        </li>
                        <li><a class="drop" href="../MISC/under_constr.html">ΝΕΟΛΑΙΑ</a>
                            <ul>
                                <li><a href="../MISC/under_constr.html">ΕΠΑΓΓΕΛΜΑΤΙΚΟΣ ΠΡΟΣΑΝΑΤΟΛΙΣΜΟΣ</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li><a href="../MISC/under_constr.html">ΒΙΒΛΙΟΘΗΚΗ</a></li>
                <li><a href="contact.php">ΕΠΙΚΟΙΝΩΝΙΑ</a></li>
                <li><a class="drop" href="../COVID-19/covid-19.php">COVID-19</a>
                    <ul>
                        <li><a href="../COVID-19/general_information.php">ΓΕΝΙΚΕΣ ΠΛΗΡΟΦΟΡΙΕΣ</a></li>
                        <li><a href="../COVID-19/EMPLOYEES/employees.php">ΠΛΗΡΟΦΟΡΙΕΣ ΓΙΑ ΤΟΝ ΕΡΓΑΖΟΜΕΝΟ</a></li>
                        <li><a href="../COVID-19/EMPLOYERS/employers.php">ΠΛΗΡΟΦΟΡΙΕΣ ΓΙΑ ΤΟΝ ΕΡΓΟΔΟΤΗ</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
        <!-- ################################################################################################ -->
        <div id="searchform">
            <div>
                <form action="#" method="post">
                    <fieldset>
                        <input style="display: inline" type="text" placeholder="Enter search term&hellip;">
                        <button style="display: inline" type="submit"><i class="fas fa-search"></i></button>
                    </fieldset>
                </form>
            </div>
        </div>
        <!-- ################################################################################################ -->
    </section>
</div>
<!-- ################################################################################################ -->
<!-- ################################################################################################ -->
<!-- ################################################################################################ -->
<div class="wrapper bgded overlay" style="background-color:royalblue">
    <div id="breadcrumb" class="hoc clear">
        <!-- ################################################################################################ -->
        <h1  class="pagetitle">Ραντεβού</h1>
        <ul>
            <li><a href="../../index.php">ΑΡΧΙΚΗ</a></li>
            <li><a href="contact.php">ΕΠΙΚΟΙΝΩΝΙΑ</a></li>
            <li style="color: dodgerblue">ΡΑΝΤΕΒΟΥ</li>
        </ul>
        <!-- ################################################################################################ -->
    </div>
</div>

<h2 style="text-align: center;margin-top:50px; margin-left: 150px">ΚΛΕΙΣΤΕ ΤΟ ΡΑΝΤΕΒΟΥ ΣΑΣ</h2>


<div style="margin-bottom: 10px" class="menu_content wrapper hoc"></div>

<script>

    function rec_ajax_next(week){
        $('#next').click(function () {
            $.ajax({
                type: 'GET',
                async: 'false',
                url: 'calendar.php',
                data: 'week='+week,
                success: function (response) {
                    $('.menu_content.wrapper.hoc').html(response);
                    rec_ajax_next(week+1);
                    rec_ajax_prev(week-1);
                }
            });
        });

    }

    function rec_ajax_prev(week){
        $('#prev').click(function () {
            $.ajax({
                type: 'GET',
                async: 'false',
                url: 'calendar.php',
                data: 'week='+week,
                success: function (response) {
                    $('.menu_content.wrapper.hoc').html(response);
                    rec_ajax_next(week+1);
                    rec_ajax_prev(week-1);
                }
            });
        });
    }

    $(document).ready(function () {
        var week = 0;
        $.ajax({
            type: 'GET',
            url: 'calendar.php',
            data: 'week='+week,
            success: function (response) {
                $('.menu_content.wrapper.hoc').html(response);
                rec_ajax_next(week+1);

            }
        });
    });

</script>

<div style="margin: 50px auto 200px auto; width: 200px;" class="wrapper row3" >
    <div style="margin-bottom: 15px;">
        <div style="display:inline; position: absolute;  width: 28px;height: 28px; background-color: lightgreen"></div><label style="position: relative; top: 3px; left:40px;" >Διαθέσιμη ημ/νία & ώρα</label>
    </div>
    <div  style="margin-bottom: 15px;">
        <div style="display:inline; position: absolute;  width: 28px;height: 28px; background-color: indianred"></div><label style="position: relative; top: 3px; left:40px;" >Μη διαθέσιμη ημ/νία & ώρα</label>
    </div>
    <?php
    if(isset($_SESSION['name']))
        echo '<div>
            <div style="display:inline; position: absolute;  width: 28px;height: 28px; background-color: lightblue"></div><label style="position: relative; top: 3px; left:40px;" >Τα ραντεβού σας</label>
        </div>'
    ?>
</div>

<!-- ################################################################################################ -->
<!-- ################################################################################################ -->
<!-- ################################################################################################ -->
<div  id="foot" class="wrapper row5">
    <div id="copyright" class="hoc clear">
        <!-- ################################################################################################ -->
        <p class="fl_left">Copyright &copy; 2021 - All Rights Reserved - <a style="background-color: inherit" href="../../index.php">www.ypakp.gr </a></p>
        <!-- ################################################################################################ -->
    </div>
</div>
<!-- ################################################################################################ -->
<!-- ################################################################################################ -->
<!-- ################################################################################################ -->
<a style="background-color: royalblue;" id="backtotop" href="#top"><i class="fas fa-chevron-up"></i></a>
<!-- JAVASCRIPTS -->
<script src="../../scripts/jquery.min.js"></script>
<script src="../../scripts/jquery.backtotop.js"></script>
<script src="../../scripts/jquery.mobilemenu.js"></script>
<script src="../../scripts/login_popup.js"></script>
<script src="../../scripts/contact_popup.js"></script>

</body>
</html>
