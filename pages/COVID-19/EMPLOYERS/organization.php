<?php
session_start();

require_once '../../../dblogin.php';
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
            header("Location: employers.php");
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
    <link href="../../../styles/layout.css" rel="stylesheet" type="text/css" media="all">
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
                <a href="../../signup.php" class="btn btn-success">ΔΗΜΙΟΥΡΓΙΑ ΝΕΟΥ ΛΟΓΑΡΙΑΣΜΟΥ</a>
            </form>
        </div>
        END;
}
?>

<div style="height: 2900px">
    <div class="wrapper row0">
        <header id="header" class="hoc clear">
            <?php
            if(!isset($_SESSION['name']))
                echo "<div id=\"log_sign\" ><button id=\"login_button\" class=\"btn btn-light login popup-trigger\">Συνδεση</button> <a href=\"../../signup.php\" id=\"signup\" class=\"btn btn-light\">Εγγραφη</a></div>";
            else {
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
            }
            ?>


            <!-- ################################################################################################ -->
            <div  id="logo" class="first">
                <a href="../../../index.php"><img src="../../../images/logo.jpg"  /></a>
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
                    <li class="active"><a href="../../../index.php">ΑΡΧΙΚΗ</a></li>
                    <li><a class="drop" href="../../EMPLOYEES/employees.php">ΕΡΓΑΖΟΜΕΝΟΙ</a>
                        <ul>
                            <li><a href="../../EMPLOYEES/LEAVE/leave.php">ΑΔΕΙΕΣ</a></li>
                            <li><a href="../../MISC/under_constr.html">ΕΠΙΔΟΜΑΤΑ</a></li>
                            <li><a href="../../MISC/under_constr.html">ΣΥΜΒΑΣΕΙΣ ΕΡΓΑΣΙΑΣ</a></li>
                            <li><a href="../../EMPLOYEES/WORKCOND/workcond.php">ΣΥΝΘΗΚΕΣ ΕΡΓΑΣΙΑΣ</a></li>
                        </ul>
                    </li>
                    <li><a class="drop" href="../../EMPLOYERS/employers.php">ΕΡΓΟΔΟΤΕΣ</a>
                        <ul>
                            <li><a href="../../MISC/under_constr.html">ΕΠΙΧΕΙΡΗΣΙΑΚΑ ΘΕΜΑΤΑ</a></li>
                            <li><a href="../../EMPLOYERS/WORKFORCE/workforce.php">ΑΝΘΡΩΠΙΝΟ ΔΥΝΑΜΙΚΟ</a></li>
                        </ul>
                    </li>
                    <li><a class="drop" href="../../other_groups.php">ΑΛΛΕΣ ΟΜΑΔΕΣ</a>
                        <ul>
                            <li><a class="drop" href="../../MISC/under_constr.html">ΣΥΝΤΑΞΙΟΥΧΟΙ</a>
                                <ul>
                                    <li><a href="../../MISC/under_constr.html">ΣΥΝΤΑΞΕΙΣ</a></li>
                                </ul>
                            </li>
                            <li><a class="drop" href="../../MISC/under_constr.html">ΑΝΕΡΓΟΙ</a>
                                <ul>
                                    <li><a href="../../MISC/under_constr.html">ΠΡΟΓΡΑΜΜΑΤΑ ΥΠΟΣΤΗΡΙΞΗΣ</a></li>
                                </ul>
                            </li>
                            <li><a class="drop" href="../../MISC/under_constr.html">ΝΕΟΛΑΙΑ</a>
                                <ul>
                                    <li><a href="../../MISC/under_constr.html">ΕΠΑΓΓΕΛΜΑΤΙΚΟΣ ΠΡΟΣΑΝΑΤΟΛΙΣΜΟΣ</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li><a href="../../MISC/under_constr.html">ΒΙΒΛΙΟΘΗΚΗ</a></li>
                    <li><a href="../../CONTACT/contact.php">ΕΠΙΚΟΙΝΩΝΙΑ</a></li>
                    <li><a class="drop" href="../covid-19.php">COVID-19</a>
                        <ul>
                            <li><a href="../general_information.php">ΓΕΝΙΚΕΣ ΠΛΗΡΟΦΟΡΙΕΣ</a></li>
                            <li><a href="../EMPLOYEES/employees.php">ΠΛΗΡΟΦΟΡΙΕΣ ΓΙΑ ΤΟΝ ΕΡΓΑΖΟΜΕΝΟ</a></li>
                            <li><a href="employers.php">ΠΛΗΡΟΦΟΡΙΕΣ ΓΙΑ ΤΟΝ ΕΡΓΟΔΟΤΗ</a></li>
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
            <h1  class="pagetitle">Πληροφορίες για τον Εργοδότη</h1>
            <ul>
                <li><a href="../../../index.php">ΑΡΧΙΚΗ</a></li>
                <li><a href="../COVID-19.php">COVID-19</a></li>
                <li><a href="employers.php">ΓΙΑ ΤΟΝ ΕΡΓΟΔΟΤΗ</a></li>
                <li style="color: dodgerblue">ΟΡΓΑΝΟΤΙΚΑ ΜΕΤΡΑ</li>
            </ul>
            <!-- ################################################################################################ -->
        </div>
    </div>

    <div  style="margin-bottom: 0px" class="hoc menu_content wrapper row2">
        <?php
        echo '</div><div style="margin-bottom: 500px; margin-top: 50px" class="wrapper row3">';
        ?>

        <main class="hoc container clear">
            <!-- main body -->
            <!-- ################################################################################################ -->
            <div class="content text">
                <!-- ################################################################################################ -->

                <h3><b>Βασικές υποχρεώσεις εργοδοτών</b></h3>

                <ul>
                    <li>Επικαιροποίηση της εκτίμησης
                        επαγγελματικού κινδύνου ως προς την
                        αξιολόγηση του κινδύνου και τα μέτρα
                        πρόληψης και προστασίας έναντι του
                        κορωνοϊού.</li>

                    <li>Ενημέρωση των εργαζομένων για την
                        κίνδυνο λοίμωξης από τον κορωνοϊό και
                        τα μέτρα πρόληψης και προστασίας, βάσει
                        και των
                        <a href="https://eody.gov.gr/loimoxi-apo-to-neo-koronoio-covid-19-odigies-profylaxis-gia-to-koino/" target="_blank">οδηγιών του ΕΟΔΥ</a>

                    <li>Διαβούλευση με τους εργαζόμενους
                        και ενθάρρυνση για υποβολή σχετικών
                        προτάσεων.</li>

                    <li>Λήψη μέτρων περιβαλλοντικής και
                        ατομικής υγιεινής, όπως τακτικός
                        αερισμός των χώρων εργασίας,
                        συντήρηση των συστημάτων εξαερισμού -
                        κλιματισμού και καθαρισμός επιφανειών,
                        συσκευών κ.λπ., σύμφωνα και με τις
                        <a href="https://eody.gov.gr/wp-content/uploads/2020/05/covid19-apolimansi-14-05-20.pdf" target="_blank">οδηγίες του ΕΟΔΥ</a>
                        .</li>

                    <li>Χορήγηση κατάλληλων μέσων ατομικής
                        προστασίας (Μ.Α.Π.) και επίβλεψη της
                        ορθής χρήσης τους.</li>

                <p> <b>Επισημαίνεται ότι η διάδοση του κορωνοϊού στην κοινότητα-εργασιακούς χώρους και ο
                    περιορισμός των κρουσμάτων και της επιδημίας αποτελεί πρωτίστως θέμα Δημόσιας Υγείας.
                    Συνεπώς η διαχείριση των προληπτικών μέτρων πρέπει, σε κάθε περίπτωση, να ακολουθεί
                    τις συστάσεις που εκδίδει ο Εθνικός Οργανισμός Δημόσιας Υγείας (Ε.Ο.Δ.Υ.) και οι αρμόδιοι
                    φορείς και Υπουργεία.</b></p>

                </ul>

                <h3><b>Οργανωτικά μέτρα</b></h3>

                <ul>
                    <p> Για τον περιορισμό του συνωστισμού και των συναθροίσεων μεταξύ των εργαζομένων, συστήνονται
                        μέτρα τα οποία ευνοούν την ελαχιστοποίηση των επαφών να:</p>

                    <li>Εξετάζεται η εισαγωγή, σύμφωνα με την
                        υφιστάμενη νομοθεσία, τρόπων οργάνωσης
                        του χρόνου εργασίας με σκοπό τη σταδιακή
                        προσέλευση στους χώρους εργασίας, για
                        την αποφυγή συνωστισμού στους χώρους
                        εργασίας, αλλά και στα μέσα μαζικής
                        μεταφοράς.</li>

                    <li>Εξετάζεται η εισαγωγή, σύμφωνα με
                        τη υφισταμένη νομοθεσία, σχημάτων εξ’
                        αποστάσεως παροχής εργασίας, στο βαθμό
                        βέβαια που αυτό είναι οργανωτικά και
                        τεχνικά εφικτό (π.χ. με χρήση τεχνολογιών
                        πληροφορικής και επικοινωνιών, κ.λπ.).</li>

                    <li>Ακολουθούνται οι συστάσεις του ιατρού
                        εργασίας και του τεχνικού ασφαλείας,
                        σχετικά με ενδεχόμενες αλλαγές της
                        χωροταξικής θέσης των εργαζομένων ή
                        και του αντικειμένου εργασίας, όπου είναι
                        εφικτό, ιδίως δε για άτομα που ανήκουν σε
                        ευπαθείς ομάδες, σύμφωνα με τις οδηγίες
                        του Ε.Ο.Δ.Υ.</li>

                    <li>Αποφεύγονται οι κάθε μορφής
                        εκδηλώσεις και συγκεντρώσεις με
                        επισκέπτες από το εξωτερικό.</li>

                    <li>Εξεταστεί η δυνατότητα χρονικής
                        μετάθεσης των προγραμματισμένων
                        ταξιδιών στο εξωτερικό.</li>

                    <li>Μετατεθούν μελλοντικά όλες οι
                        προγραμματισμένες δραστηριότητες
                        που συνεπάγονται συναθροίσεις ατόμων
                        (σεμινάρια, ημερίδες, συμπόσια κ.λπ.).</li>

                    <li>Αποφεύγεται ο συνωστισμός και οι
                        συνεργασίες να πραγματοποιούνται με άλλο
                        τρόπο (τηλεφωνική επικοινωνία, e-mail,
                        τηλεδιάσκεψη κ.λπ. εφόσον είναι εφικτό).</li>

                    <li>Ρυθμιστεί η πρόσβαση σε κοινόχρηστους
                        χώρους όπως αποδυτήρια, λουτρά, χώροι
                        εστίασης εντός της επιχείρησης, κ.λπ.,
                        με στόχο τη μείωση της πυκνότητας
                        συγκέντρωσης ατόμων στον ίδιο χώρο και
                        την τήρηση του κριτηρίου της ασφαλούς
                        απόστασης.</li>
                </ul>

                <h3 style="text-transform: none"><b>Προβλέψεις της νομοθεσίας για την υγεία και ασφάλεια στην εργασία</b></h3>
                <ul>
                    <p> Σύμφωνα με τον Κώδικα Νόμων για την Υγεία & Ασφάλεια των Εργαζομένων (ΚΝΥΑΕ) που
                        κυρώθηκε με το άρθρο πρώτο του
                        <a href="../../MISC/under_constr.html" target="_blank">ν.3850/2010 (ΦΕΚ 84 Α΄)</a>,
                        η νομοθεσία για την ασφάλεια και την υγεία στην εργασία εφαρμόζεται στη χώρα μας, εφόσον δεν
                        ορίζεται αλλιώς, σε όλες τις επιχειρήσεις, εγκαταστάσεις, εκμεταλλεύσεις και εργασίες του
                        ιδιωτικού και του δημόσιου τομέα (καθώς και τα ν.π.δ.δ. και τους Ο.Τ.Α.) και <b> για κάθε
                        εργαζόμενο που απασχολείται από τον εργοδότη με οποιαδήποτε σχέση εργασίας,
                        περιλαμβανομένων των ασκούμενων και μαθητευόμενων.</b></p>

                    <p> Βασικός πυλώνας του παραπάνω θεσμικού πλαισίου είναι η νομική έννοια της αποκλειστικής
                        ευθύνης του εργοδότη, ο οποίος υποχρεούται να εξασφαλίζει την ασφάλεια και την υγεία
                        των εργαζομένων ως προς όλες τις πτυχές της εργασίας, να λαμβάνει μέτρα που να
                        εξασφαλίζουν την υγεία και ασφάλεια των τρίτων
                        (<a href="../../MISC/under_constr.html" target="_blank">άρθρ.42, παρ.1. ΚΝΥΑΕ</a>)
                        και να χρησιμοποιεί τις υπηρεσίες τεχνικού ασφαλείας και (όπου προβλέπεται)
                        ιατρού εργασίας.</p>

                    <p>Στο πλαίσιο των ευθυνών του ο εργοδότης οφείλει να λαμβάνει τα αναγκαία μέτρα για την
                        προστασία της υγείας και της ασφάλειας των εργαζομένων, συμπεριλαμβανομένων των
                        δραστηριοτήτων πρόληψης, ενημέρωσης και κατάρτισης, καθώς και της δημιουργίας της
                        απαραίτητης οργάνωσης και της παροχής των αναγκαίων μέσων. Τα μέτρα για την ασφάλεια,
                        την υγεία και την υγιεινή κατά την εργασία σε καμία περίπτωση δεν συνεπάγονται την
                        οικονομική επιβάρυνση των εργαζομένων
                        (<a href="../../MISC/under_constr.html" target="_blank">άρθρ. 42, παρ. 10</a>) .</p>

                    <p> Ο εργοδότης επιπλέον οφείλει να έχει στη διάθεσή του μια γραπτή εκτίμηση των υφισταμένων
                        κατά την εργασία κινδύνων για την ασφάλεια και την υγεία, συμπεριλαμβανομένων εκείνων
                        που και αφορούν ομάδες εργαζομένων που εκτίθενται σε ιδιαίτερους κινδύνους καθώς και να
                        καθορίζει τα
                        <a href="../EMPLOYEES/hygiene.php" target="_blank">μέτρα ατομικής προστασίας</a>,
                        που πρέπει να ληφθούν και, αν χρειαστεί, το υλικό
                        προστασίας που πρέπει να χρησιμοποιηθεί. Η γραπτή εκτίμηση επαγγελματικού κινδύνου θα
                        πρέπει να επικαιροποιηθεί και ως προς τους κινδύνους και τα μέτρα πρόληψης από τον
                        COVID-19.</p>

                    <p> Ο εργοδότης πρέπει να λαμβάνει τα κατάλληλα μέτρα προκειμένου οι εργαζόμενοι και οι
                        εκπρόσωποί τους στην επιχείρηση να λαμβάνουν όλες τις απαραίτητες πληροφορίες όσον
                        αφορά τη νομοθεσία, τον τρόπο εφαρμογής της από την επιχείρηση και τους κινδύνους για την
                        ασφάλεια και την υγεία, καθώς και τα μέτρα και τις δραστηριότητες προστασίας και πρόληψης
                        που λαμβάνονται.</p>

                    <p> Ο εργοδότης πρέπει να εφαρμόζει αυστηρά το παραπάνω θεσμικό πλαίσιο για την υγεία
                        και την ασφάλεια στην εργασία, καθώς και τις ειδικότερες διατάξεις για συγκεκριμένους
                        επαγγελματικούς κινδύνους και χώρους εργασίας, λαμβάνοντας υπόψη σε κάθε περίπτωση τις
                        εξειδικευμένες και επείγουσες οδηγίες των αρμόδιων θεσμικών οργάνων (
                        <a href="https://www.moh.gov.gr" target="_blank">Υπουργείο Υγείας</a>
                        –
                        <a href="https://eody.gov.gr" target="_blank">ΕΟΔΥ</a>
                        ,
                        <a href="employers.php" target="_blank">Υπουργείο Εργασίας</a>
                        –
                        <a href="https://www.sepenet.gr/liferayportal/archike" target="_blank">ΣΕΠΕ</a>
                        κ.λπ.) για τον περιορισμό της εξάπλωσης του κορωνοϊού,
                        με τη συνδρομή του ιατρού εργασίας και του τεχνικού ασφάλειας, και σε συνεργασία με την
                        επιτροπή υγείας και ασφάλειας ή τους εκπροσώπους των εργαζομένων για θέματα υγείας και
                        ασφάλειας στην εργασία.</p>
                </ul>

                <!-- ################################################################################################ -->
                <!-- / main body -->
                <div class="clear"></div>
        </main>
    </div>
</div>
<!-- ################################################################################################ -->
<!-- ################################################################################################ -->
<!-- ################################################################################################ -->
<div  id="foot" class="wrapper row5">
    <div id="copyright" class="hoc clear">
        <!-- ################################################################################################ -->
        <p class="fl_left">Copyright &copy; 2021 - All Rights Reserved - <a style="background-color: inherit" href="../../../index.php">www.ypakp.gr </a></p>
        <!-- ################################################################################################ -->
    </div>
</div>
<!-- ################################################################################################ -->
<!-- ################################################################################################ -->
<!-- ################################################################################################ -->
<a style="background-color: royalblue;" id="backtotop" href="#top"><i class="fas fa-chevron-up"></i></a>
<!-- JAVASCRIPTS -->
<script src="../../../scripts/jquery.min.js"></script>
<script src="../../../scripts/jquery.backtotop.js"></script>
<script src="../../../scripts/jquery.mobilemenu.js"></script>
<script src="../../../scripts/login_popup.js"></script>

</body>
</html>
