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

<div style="height: 2500px">
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
                <li style="color: dodgerblue">ΜΕΤΡΑ ΥΓΙΕΙΝΗΣ ΚΑΙ ΠΡΟΣΤΑΣΙΑΣ</li>
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
                <h3 style="text-transform: none"><b>Μέτρα ατομικής υγιεινής και μέσα ατομικής προστασίας</b></h3>
                <ul>
                    <p>Θεωρείται απαραίτητο ο εργοδότης να λάβει μέτρα για την εφαρμογή ορθών πρακτικών ατομικής
                        υγιεινής, τόσο των εργαζομένων όσο και των τρίτων στο χώρο εργασίας</p>

                    <li>Αποφυγή των επαφών με άτομα που
                        παρουσιάζουν συμπτώματα λοίμωξης
                        αναπνευστικού, χωρίς τη λήψη κατάλληλων
                        μέτρων προφύλαξης.</li>

                    <li>Προτροπή και επίβλεψη της συμμόρφωσης
                        των εργαζομένων και των τρίτων για την
                        εφαρμογή ορθών πρακτικών ατομικής
                        υγιεινής (χέρια, αναπνευστικές εκκρίσεις,
                        κ.λπ.) παρέχοντας επίσης τα κατάλληλα μέσα
                        καθαρισμού και απολύμανσης, όπως επίσης
                        και σακούλες απορριμμάτων.</li>

                    <li>Παροχή αντισηπτικών διαλυμάτων (σε
                        μορφή υγρού, αφρού, γέλης, εμποτισμένα
                        μαντηλάκια) στους εργαζομένους και
                        τοποθέτηση κατάλληλων μηχανισμών
                        για αντισηψία των χεριών στις εξόδους/
                        εισόδους και στους κοινόχρηστους
                        χώρους της επιχείρησης, με έμφαση στις
                        περιπτώσεις που οι εργαζόμενοι έρχονται σε
                        επαφή με το ευρύ κοινό.</li>

                    <li>Αποφυγή κατανάλωσης κάθε είδους τροφής
                        στους εργασιακούς χώρους, εκτός των
                        χώρων εστίασης.</li>

                    <li>Διάθεση και τοποθέτηση κάδων με
                        ποδοκίνητο καπάκι και σακούλα
                        απορριμμάτων, όπου απορρίπτονται τα
                        ΜΑΠ μιας χρήσης, καθώς και τα μαντηλάκια,
                        οι χειροπετσέτες ή άλλα μέσα που
                        χρησιμοποιήθηκαν για την απολύμανση
                        επιφανειών ή αντικειμένων στο χώρο
                        εργασίας.</li>

                    <li>Παροχή των κατάλληλων ατομικών
                        μέσων προστασίας μιας χρήσης (ΜΑΠ).
                        Επισημαίνεται η ευθύνη του εργοδότη για
                        την εκπαίδευση των εργαζομένων για την
                        ασφαλή χρήση και την επίβλεψη χρήσης των
                        ΜΑΠ.</li>
                    </ul>

                <h3 style="text-transform: none"><b> Περιβαλλοντικά μέτρα</b></h3>
                <ul>
                    <p> Οι κορωνοϊοί μεταδίδονται κυρίως μέσω μεγάλων αναπνευστικών σταγονιδίων και επαφής, αλλά
                        ενδεχομένως να υπάρχουν και άλλοι τρόποι μετάδοσης.
                        Ο χρόνος επιβίωσης και οι συνθήκες που επηρεάζουν την επιβίωση του SARS-CoV-2 στο περιβάλλον
                        παραμένουν αυτή τη στιγμή άγνωστες. Σύμφωνα όμως με μελέτες που εκτιμούν τη σταθερότητα άλλων
                        κορωνοϊών, εκτιμάται ότι ο SARS-CoV-2 μπορεί να επιβιώσει αρκετές ημέρες στο περιβάλλον πάνω σε
                        διαφορετικές επιφάνειες. Ενδεικτικά, η επιβίωση του ιού SARS-CoV στο περιβάλλον εκτιμάται ότι είναι
                        μερικές ημέρες, ενώ αυτή του ιού MERS-CoV είναι μεγαλύτερη από 48 ώρες, σε μέση θερμοκρασία
                        δωματίου (20oC), πάνω σε διαφορετικές επιφάνειες. Ο επαρκής καθαρισμός και η απολύμανση του
                        περιβάλλοντος θεωρούνται απαραίτητα για τον περιορισμό της διασποράς του ιού.
                        Τα περιβαλλοντικά μέτρα ελέγχου της διασποράς του ιού περιλαμβάνουν:</p>

                    <li>Tον επαρκή αερισμό των εργασιακών χώρων
                        και την τακτική συντήρηση των συστημάτων
                        εξαερισμού - κλιματισμού.</li>

                    <li>Tον συστηματικό καθαρισμό των χώρων
                        και των επιφανειών εργασίας, του
                        εξοπλισμού εργασίας καθώς και των
                        εργαλείων, συσκευών και αντικειμένων που
                        χρησιμοποιούνται (πόμολα, τηλεχειριστήρια,
                        διακόπτες, τηλέφωνα, πληκτρολόγια κ.λ.π.).</li>

                    <li>Tον συστηματικό καθαρισμό των
                        κοινόχρηστων χώρων όπως, αποδυτήρια,
                        λουτρά, χώροι εστίασης κ.λ.π.</li>

                    <li>Tην απολύμανση μέσω ψεκασμού (με
                        εγκεκριμένα παρασκευάσματα) των χώρων
                        όπου έχει εντοπιστεί πιθανό ή επιβεβαιωμένο
                        κρούσμα της λοίμωξης COVID-19. Οι εργασίες
                        ψεκασμού πρέπει να εκτελούνται από
                        προσωπικό εξοπλισμένο με τα κατάλληλα
                        ΜΑΠ (μάσκα φίλτρου, γάντια μίας χρήσης,
                        αδιάβροχη φόρμα με μακριά μανίκια κ.λπ.),
                        επίσης πρέπει να τηρούνται τα μέτρα που
                        υποδεικνύονται για την ασφαλή χρήση,
                        αφαίρεση ή/και απόρριψη των ΜΑΠ, καθώς
                        και οι χρόνοι απόδοσης των χώρων στο
                        κοινό.</li>

                    <li>Tη διάθεση και την τοποθέτηση σκεπαστών
                        κάδων απορριμμάτων, όπου απορρίπτονται
                        τα ΜΑΠ μιας χρήσης, καθώς και τα
                        μαντιλάκια, χειροπετσέτες ή άλλα μέσα που
                        χρησιμοποιήθηκαν για την απολύμανση
                        των επιφανειών εργασίας, καθώς και είδη
                        προσωπικής υγιεινής.</li>

                    <li>Tο συχνό καθαρισμό των ενδυμάτων
                        εργασίας (φόρμες, ποδιές κ.λπ.), καθώς και
                        των ΜΑΠ που χορηγούνται (κράνη, γυαλιά,
                        μέσα προστασίας της ακοής, άρβυλα κ.λπ.).</li>

                </ul>

                <h3 style="text-transform: none"><b>  Γενικές οδηγίες για τον καθαρισμό και την απολύμανση των εργασιακών χώρων</b></h3>
                <ul>
                    <p>Ο συστηματικός και σωστός καθαρισμός με τη συνήθη διαδικασία (χρήση απορρυπαντικού παράγοντα,
                        νερό και μηχανική τριβή) και η απολύμανση των επιφανειών και των αντικειμένων στους εργασιακούς
                        χώρους, είναι κρίσιμης σημασίας.
                        Καθώς δεν υπάρχουν δεδομένα για την αποτελεσματικότητα των συνήθως χρησιμοποιούμενων αντιμικροβιακών
                        παραγόντων κατά του συγκεκριμένου ιού SARS-CoV-2, συστήνεται η χρήση προϊόντων με
                        ελεγμένη αποτελεσματικότητα κατά των κορωνοϊών.
                        Συγκεκριμένα προτείνεται η χρήση 0,5% υποχλωριώδους νατρίου (αραίωση 1:10 αν χρησιμοποιείται οικιακή
                        χλωρίνη αρχικής συγκέντρωσης 5% ή ισοδύναμο 5.000 ppm, αν χρησιμοποιούνται ταμπλέτες) μετά
                        από τον καθαρισμό με ουδέτερο απορρυπαντικό. Για επιφάνειες που είναι πιθανόν να καταστραφούν
                        από τη χρήση υποχλωριώδους νατρίου, μπορεί να χρησιμοποιηθεί αιθανόλη συγκέντρωση 70% κατόπιν
                        καθαρισμού με ουδέτερο απορρυπαντικό, λαμβάνοντας όμως πάντα υπόψη τις οδηγίες του κατασκεαστή για τη
                        συμβατότητα του υλικού με τον αντιμικροβιακό παράγοντα που θα χρησιμοποιηθεί.
                    </p>
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
