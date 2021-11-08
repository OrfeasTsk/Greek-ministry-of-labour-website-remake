<?php
 require_once 'dblogin.php';
 session_start();
 $conn = mysqli_connect($hn, $un, $pw, $db);
 if($conn->connect_error) die($conn->connect_error);
 $error_msg = null;
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
            header("Location: index.php");
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
<link href="styles/layout.css" rel="stylesheet" type="text/css" media="all">
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
                <a href="pages/signup.php" class="btn btn-success">ΔΗΜΙΟΥΡΓΙΑ ΝΕΟΥ ΛΟΓΑΡΙΑΣΜΟΥ</a>
            </form>
        </div>
        END;
}
?>


<div class="wrapper row0">
    <header id="header" class="hoc clear">
        <?php
        if(!isset($_SESSION['name']))
            echo "<div id=\"log_sign\" ><button id=\"login_button\" class=\"btn btn-light login popup-trigger\">Συνδεση</button> <a href=\"pages/signup.php\" id=\"signup\" class=\"btn btn-light\">Εγγραφη</a></div>";
        else {
            $name = $_SESSION['name'];
            echo <<<END
            <div id="profile">
                <button class="btn btn-light" ><label>$name </label>
                <i class="fas fa-user-circle"></i></button>
                <div class="content">
                    <a href="pages/profile.php">ΤΟ ΠΡΟΦΙΛ ΜΟΥ</a>
                    <a href="pages/logout.php">ΑΠΟΣΥΝΔΕΣΗ</a>
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
            <a href="index.php"><img src="images/logo.jpg"  /></a>
        </div>


        <!-- ################################################################################################ -->
    </header>
</div>




<!-- ################################################################################################ -->
<!-- ################################################################################################ -->
<!-- ################################################################################################ -->
<div class="wrapper row1">
  <section class="hoc clear"> 
    <!-- ################################################################################################ -->
    <nav id="mainav">
      <ul class="clear">
        <li class="active"><a href="index.php">ΑΡΧΙΚΗ</a></li>
        <li><a class="drop" href="pages/EMPLOYEES/employees.php">ΕΡΓΑΖΟΜΕΝΟΙ</a>
          <ul>
            <li><a href="pages/EMPLOYEES/LEAVE/leave.php">ΑΔΕΙΕΣ</a></li>
            <li><a href="pages/MISC/under_constr.html">ΕΠΙΔΟΜΑΤΑ</a></li>
            <li><a href="pages/MISC/under_constr.html">ΣΥΜΒΑΣΕΙΣ ΕΡΓΑΣΙΑΣ</a></li>
            <li><a href="pages/EMPLOYEES/WORKCOND/workcond.php">ΣΥΝΘΗΚΕΣ ΕΡΓΑΣΙΑΣ</a></li>
          </ul>
        </li>
        <li><a class="drop" href="pages/EMPLOYERS/employers.php">ΕΡΓΟΔΟΤΕΣ</a>
          <ul>
            <li><a href="pages/MISC/under_constr.html">ΕΠΙΧΕΙΡΗΣΙΑΚΑ ΘΕΜΑΤΑ</a></li>
            <li><a href="pages/EMPLOYERS/WORKFORCE/workforce.php">ΑΝΘΡΩΠΙΝΟ ΔΥΝΑΜΙΚΟ</a></li>
          </ul>
        </li>
        <li><a class="drop" href="pages/other_groups.php">ΑΛΛΕΣ ΟΜΑΔΕΣ</a>
          <ul>
            <li><a class="drop" href="pages/MISC/under_constr.html">ΣΥΝΤΑΞΙΟΥΧΟΙ</a>
              <ul>
                <li><a href="pages/MISC/under_constr.html">ΣΥΝΤΑΞΕΙΣ</a></li>
              </ul>
            </li>
            <li><a class="drop" href="pages/MISC/under_constr.html">ΑΝΕΡΓΟΙ</a>
              <ul>
                <li><a href="pages/MISC/under_constr.html">ΠΡΟΓΡΑΜΜΑΤΑ ΥΠΟΣΤΗΡΙΞΗΣ</a></li>
              </ul>
            </li>
            <li><a class="drop" href="pages/MISC/under_constr.html">ΝΕΟΛΑΙΑ</a>
              <ul>
                <li><a href="pages/MISC/under_constr.html">ΕΠΑΓΓΕΛΜΑΤΙΚΟΣ ΠΡΟΣΑΝΑΤΟΛΙΣΜΟΣ</a></li>
              </ul>
            </li>
          </ul>
        </li>
        <li><a href="pages/MISC/under_constr.html">ΒΙΒΛΙΟΘΗΚΗ</a></li>
        <li><a href="pages/CONTACT/contact.php">ΕΠΙΚΟΙΝΩΝΙΑ</a></li>
        <li><a class="drop" href="pages/COVID-19/covid-19.php">COVID-19</a>
        <ul>
            <li><a href="pages/COVID-19/general_information.php">ΓΕΝΙΚΕΣ ΠΛΗΡΟΦΟΡΙΕΣ</a></li>
            <li><a href="pages/COVID-19/EMPLOYEES/employees.php">ΠΛΗΡΟΦΟΡΙΕΣ ΓΙΑ ΤΟΝ ΕΡΓΑΖΟΜΕΝΟ</a></li>
            <li><a href="pages/COVID-19/EMPLOYERS/employers.php">ΠΛΗΡΟΦΟΡΙΕΣ ΓΙΑ ΤΟΝ ΕΡΓΟΔΟΤΗ</a></li>
        </ul>
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
<div class="wrapper bgded overlay" style="background-color: royalblue;">
  <img id="mainphoto" src="images/mainphoto.png"/>
  <div id="pageintro" class="hoc clear">
    <!-- ################################################################################################ -->
    <article>
      <p class="heading" style="position:relative; font-size:large;  bottom: 33px;line-height: 35px;">"ΚΟΝΤΑ ΣΤΟΥΣ ΑΝΘΡΩΠΟΥΣ ΤΗΣ ΕΡΓΑΣΙΑΣ <br/> ΚΑΙ ΣΤΟΥΣ ΑΠΟΜΑΧΟΥΣ ΤΗΣ ΖΩΗΣ"
      </p>
      <footer class="btn-group" role="group" >
        <a href="pages/EMPLOYEES/employees.php" style=" border-right: 1.3px solid black; background-color:royalblue;" class="btn btn-light select_cat"><span>ΕΙΜΑΙ <br/> ΕΡΓΑΖΟΜΕΝΟΣ</span></a>
        <a href="pages/EMPLOYERS/employers.php" style="border-left: 1.3px solid black; background-color:royalblue;" class="btn btn-light select_cat"><span>ΕΙΜΑΙ<br/>ΕΡΓΟΔΟΤΗΣ</span></a>
      </footer>
    </article>
    <!-- ################################################################################################ -->
  </div>
</div>
<!-- ################################################################################################ -->
<!-- ################################################################################################ -->
<!-- ################################################################################################ -->
<div class="wrapper row2">
  <main class="hoc container clear"> 
    <!-- main body -->
    <!-- ################################################################################################ -->
    <div class="sectiontitle">
      <h3 class="heading">Γρήγορη Πρόσβαση</h3>
    </div>
    <div class="group center btmspace-30">
      <article class="one_third first"><a class="ringcon btmspace-50" href="pages/EMPLOYEES/LEAVE/leave.php"><i class="fa fa-bed" aria-hidden="true"></i></a>
        <h6 class="heading">ΑΔΕΙΕΣ</h6>
        <p>Για πληροφορίες σχετικά με τις άδειες των εργαζομένων</p>
      </article>
      <article class="one_third"><a class="ringcon btmspace-50" href="pages/MISC/under_constr.html"><i class="fa fa-briefcase" aria-hidden="true"></i></i></a>
        <h6 class="heading">ΕΠΙΧΕΙΡΗΣΙΑΚΑ ΘΕΜΑΤΑ</h6>
        <p>Για πληροφορίες σχετικά με τα επιχειρησιακά θέματα που απασχολούν τους εργοδότες</p>
      </article>
      <article class="one_third"><a href="pages/COVID-19/covid-19.php" class="ringcon btmspace-50" href="#"><i class="fa fa-medkit" aria-hidden="true"></i></a>
        <h6 class="heading">COVID-19</h6>
        <p>Για πληροφορίες σχετικά με το νέο κορωνοϊό COVID-19  </p>
      </article>
    </div>
    <!-- ################################################################################################ -->
    <!-- / main body -->
    <div class="clear"></div>
  </main>
</div>
<!-- ################################################################################################ -->
<!-- ################################################################################################ -->
<!-- ################################################################################################ -->
<div id="news" class="wrapper row2">
  <section  class="hoc container clear">
    <!-- ################################################################################################ -->
    <div class="sectiontitle">
      <h6 class="heading">Νέα & Ανακοινώσεις</h6>
    </div>
    <div id="latest" class="group"></div>

    <!-- ################################################################################################ -->
  </section>
    <script>

        function rec_ajax_next(page){
            $('#next').click(function () {
                $.ajax({
                    type: 'GET',
                    async: 'false',
                    url: 'pages/news.php',
                    data: 'page='+page,
                    success: function (response) {
                        $('#latest').html(response);
                        rec_ajax_next(parseInt(page)+1);
                        rec_ajax_prev(parseInt(page)-1);
                        set_pages();
                    }
                });
            });

        }

        function rec_ajax_prev(page){
            $('#prev').click(function () {
                $.ajax({
                    type: 'GET',
                    async: 'false',
                    url: 'pages/news.php',
                    data: 'page='+page,
                    success: function (response) {
                        $('#latest').html(response);
                        rec_ajax_next(parseInt(page)+1);
                        rec_ajax_prev(parseInt(page)-1);
                        set_pages();
                    }
                });
            });
        }

        function set_pages(){
            $('.other-page').each(function() {
                $(this).click(function () {
                    let page = $(this).text();
                    $.ajax({
                        type: 'GET',
                        async: 'false',
                        url: 'pages/news.php',
                        data: 'page='+page,
                        success: function (response) {
                            $('#latest').html(response);
                            rec_ajax_next(parseInt(page)+1);
                            rec_ajax_prev(parseInt(page)-1);
                            set_pages();
                        }
                    });
                });
            });
        }


        $(document).ready(function () {
            let page = "1";
            $.ajax({
                type: 'GET',
                url: 'pages/news.php',
                data: 'page='+page,
                success: function (response) {
                    $('#latest').html(response);
                    rec_ajax_next(parseInt(page)+1);
                    set_pages();
                }
            });
        });

    </script>



</div>




<!-- ################################################################################################ -->
<!-- ################################################################################################ -->
<!-- ################################################################################################ -->
<div style="background-color: royalblue;" class="wrapper row5">
  <div id="copyright" class="hoc clear"> 
    <!-- ################################################################################################ -->
    <p class="fl_left">Copyright &copy; 2021 - All Rights Reserved - <a style="background-color: inherit" href="index.php">www.ypakp.gr </a></p>
    <!-- ################################################################################################ -->
  </div>
</div>
<!-- ################################################################################################ -->
<!-- ################################################################################################ -->
<!-- ################################################################################################ -->
<a style="background-color: royalblue;" id="backtotop" href="#top"><i class="fas fa-chevron-up"></i></a>
<!-- JAVASCRIPTS -->
<script src="scripts/jquery.min.js"></script>
<script src="scripts/jquery.backtotop.js"></script>
<script src="scripts/jquery.mobilemenu.js"></script>
<script src="scripts/login_popup.js"></script>
</body>
</html>
