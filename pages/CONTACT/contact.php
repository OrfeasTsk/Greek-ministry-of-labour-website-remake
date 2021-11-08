<?php
    session_start();

    require_once '../../dblogin.php';
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
                header("Location: contact.php");
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
        <h1  class="pagetitle">Εποικοινωνία</h1>
        <ul>
            <li><a href="../../index.php">ΑΡΧΙΚΗ</a></li>
            <li style="color: dodgerblue">ΕΠΙΚΟΙΝΩΝΙΑ</li>
        </ul>
        <!-- ################################################################################################ -->
    </div>
</div>

<div style="margin-bottom: 110px" class="hoc menu_content wrapper row2">
    <a href="appointment.php" id="redirect_trigger" class="btn btn-info go-to-button" style="height: 50px;text-align: center;display: inline-flex;align-items: center; "><i class="fa fa-plus" style="font-size: 20px" aria-hidden="true"></i>&nbspΚΛΕΙΣΤΕ ΡΑΝΤΕΒΟΥ</a></div>;
</div>

<div style="margin: 50px auto 600px auto; width: 1300px;" class="wrapper row3" >
    <h2 style="text-align: center;margin-top:50px; margin-left: 150px"> ΕΠΙΚΟΙΝΩΝΗΣΤΕ ΜΑΖΙ ΜΑΣ</h2>

    <table style="text-align: center; margin-top:50px; margin-left: 50px">
        <tr>
            <th style="text-align: center; padding: 15px;">Αυτοτελή τμήματα και διεύθυνση</th>
            <th style="text-align: center; padding: 15px;">Ε-mail</th>
            <th style="text-align: center; padding: 15px;">Τηλέφωνο γραμματείας</th>
            <th style="text-align: center; padding: 15px;">Περιγραφή</th>
            <th style="text-align: center; padding: 15px;">Ωράριο</th>
        </tr>

        <tr>
            <td style="padding: 10px">Κοινοβουλευτικού ελέγχου</td>
            <td style="padding: 10px">ypertns@ypakp.gr</td>
            <td style="padding: 10px">2105281284<br>2105281285</td>
            <td style="padding: 10px">Αρμόδιο για τη συλλογή πληροφοριών και τη σύνταξη σχετικών απαντήσεων
                και την επεξεργασία αυτών, σε συνεργασία με το Γραφείο Υπουργού και την προώθησή τους, μέσα
                στις προβλεπόμενες προθεσμίες, στη Βουλή.</td>
            <td style="padding: 10px">
                ΔΕΥ: 10:00-13:00<br>
                ΤΡΙ: 8:00-13:00<br>
                ΤΕΤ: 10:00-13:00<br>
                ΠΕΜ: 8:00-13:00<br>
                ΠΑΡ: 11:00-13:00<br>
            </td>
        </tr>

        <tr>
            <td style="padding: 10px">Νομοθετικής πρωτοβουλίας</td>
            <td style="padding: 10px">nomothetikotmima@ypakp.gr</td>
            <td style="padding: 10px">2103368315</td>
            <td style="padding: 10px">Συμμετέχει στο σχεδιασμό των νομοθετικών και κανονιστικών ρυθμίσεων
                και μεριμνά για τη διενέργεια κοινωνικού διαλόγου με εκπροσώπους κοινωνικών
                φορέων και ενδιαφερόμενων ομάδων..</td>
            <td style="padding: 10px">
                ΔΕΥ: 8:00-12:00<br>
                ΤΡΙ: 8:00-12:00<br>
                ΠΕΜ: 8:00-12:00<br>
                ΠΑΡ: 8:00-12:00<br>
            </td>
        </tr>

        <tr>
            <td style="padding: 10px">Εσωτερικού ελέγχου</td>
            <td style="padding: 10px">internal.audit@ypakp.gr</td>
            <td style="padding: 10px">2131511102-03</td>
            <td style="padding: 10px">Αρμόδιο για την παροχή ελεγκτικών - συμβουλευτικών αρμοδιοτήτων,
                ο έλεγχος πληροφοριακών συστημάτων, προκειμένου να διαπιστωθεί κατά πόσον
                επιτυγχάνουν τους σκοπούς τους και εάν έχουν ενσωματωθεί σε αυτά επαρκείς
                ασφαλιστικές δικλίδες/ μηχανισμοί ελέγχου. Αρμόδιο για την παροχή
                διαβεβαίωσης περί της επάρκειας των συστημάτων διαχείρισης και ελέγχου του
                Υπουργείου και την έρευνα της ύπαρξης αντικειμενικής αδυναμίας απόδοσης
                λογαριασμού χρηματικού εντάλματος προπληρωμής.</td>
            <td style="padding: 10px">
                ΔΕΥ: 9:00-13:00<br>
                ΤΡΙ: 9:00-13:00<br>
                ΤΕΤ: 9:00-13:00<br>
                ΠΕΜ: 9:00-13:00<br>
                ΠΑΡ: 11:00-13:00<br>
            </td>
        </tr>

        <tr>
            <td style="padding: 10px"> Πολιτικής Σχεδίασης Εκτάτης Ανάγκης (Π.Σ.Ε.Α.)</td>
            <td style="padding: 10px">psea@ypakp.gr</td>
            <td style="padding: 10px">21003231316 <br> 2131516185 <br>  2105203723</td>
            <td style="padding: 10px">Αρμόδιο για το σχεδιασμό, την οργάνωση, την κινητοποίηση και τη δράση κατά
                τον πόλεμο ή σε περίπτωση έκτακτης ανάγκης σε καιρό ειρήνης των υπηρεσιών
                του Υπουργείου και τη ρύθμιση κάθε σχετικού θέματος σύμφωνα με τις ισχύουσες
                κάθε φορά διατάξεις.</td>
            <td style="padding: 10px">
                ΔΕΥ: 10:00-13:00<br>
                ΤΡΙ: 8:00-13:00<br>
                ΤΕΤ: 10:00-13:00<br>
                ΠΕΜ: 8:00-13:00<br>
                ΠΑΡ: 11:00-13:00<br>
            </td>
        </tr>

        <tr>
            <td style="padding: 10px">Προμηθειών και μέριμνας</td>
            <td style="padding: 10px">promitheies@ypakp.gr</td>
            <td style="padding: 10px">2105281121</td>
            <td style="padding: 10px">Αρμόδια για την ομαλή λειτουργία των υπηρεσιών του Υπουργείου
                Εργασίας, Κοινωνικής Ασφάλισης και Κοινωνικής Αλληλεγγύης με τη
                χρήση όλων των αναγκαίων πόρων και υπηρεσιών, ήτοι με:
                α) την κατάρτιση και εκτέλεση κάθε είδους προμήθειας του Υπουργείου,
                συγχρηματοδοτούμενης ή μη από κοινοτικούς πόρους,
                β) τη διοικητική μέριμνα αυτού,
                γ) τη διαχείριση του υλικού και την εγκατάστασή του και
                δ) την εποπτεία και τον έλεγχο τεχνικών έργων και μελετών του
                Υπουργείου και των εποπτευόμενων φορέων.</td>
            <td style="padding: 10px">
                ΔΕΥ: 10:00-13:00<br>
                ΤΡΙ: 8:00-13:00<br>
                ΤΕΤ: 10:00-13:00<br>
                ΠΕΜ: 8:00-13:00<br>
                ΠΑΡ: 11:00-13:00<br>
            </td>
        </tr>

        <tr>
            <td style="padding: 10px">Ηλεκτρονικής διακυβερνησης και εξυπηρέτησης του πολίτη</td>
            <td style="padding: 10px">it-services@ypakp.gr<br>pliroforisi-politi@ypakp.gr</td>
            <td style="padding: 10px">2105281121</td>
            <td style="padding: 10px">Αρμόδια για τη διαχείριση των Πληροφοριακών και Επικοινωνιακών
                Υποδομών και αξιοποίηση της τεχνολογίας για την εξασφάλιση της
                εύρυθμης και αποτελεσματικής λειτουργίας των υπηρεσιών του
                Υπουργείου Εργασίας, Κοινωνικής Ασφάλισης και Κοινωνικής
                Αλληλεγγύης.</td>
            <td style="padding: 10px">
                ΔΕΥ: 8:00-13:00<br>
                ΤΡΙ: 8:00-13:00<br>
                ΤΕΤ: 11:00-13:00<br>
                ΠΕΜ: 8:00-13:00<br>
                ΠΑΡ: 8:00-13:00<br>
            </td>
        </tr>

        <tr>
            <td style="padding: 10px">Προμηθειών και μέριμνας</td>
            <td style="padding: 10px">promitheies@ypakp.gr</td>
            <td style="padding: 10px">2105281121</td>
            <td style="padding: 10px">Αρμόδια για την ομαλή λειτουργία των υπηρεσιών του Υπουργείου
                Εργασίας, Κοινωνικής Ασφάλισης και Κοινωνικής Αλληλεγγύης με τη
                χρήση όλων των αναγκαίων πόρων και υπηρεσιών, ήτοι με:
                α) την κατάρτιση και εκτέλεση κάθε είδους προμήθειας του Υπουργείου,
                συγχρηματοδοτούμενης ή μη από κοινοτικούς πόρους,
                β) τη διοικητική μέριμνα αυτού,
                γ) τη διαχείριση του υλικού και την εγκατάστασή του και
                δ) την εποπτεία και τον έλεγχο τεχνικών έργων και μελετών του
                Υπουργείου και των εποπτευόμενων φορέων.</td>
            <td style="padding: 10px">
                ΔΕΥ: 10:00-13:00<br>
                ΤΡΙ: 8:00-13:00<br>
                ΤΕΤ: 10:00-13:00<br>
                ΠΕΜ: 8:00-13:00<br>
                ΠΑΡ: 11:00-13:00<br>
            </td>
        </tr>
    </table>

    <br>
    <br>
    <br>
    <br>
    <ul><center><p> Τα οράρια εξηπυρέτησης των τμημάτων έχουν προσαρμοστεί λόγω COVID-19.</p></center></ul>
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

</body>
</html>
