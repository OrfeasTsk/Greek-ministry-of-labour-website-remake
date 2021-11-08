<?php
session_start();

require_once '../../../dblogin.php';
$conn = mysqli_connect($hn, $un, $pw, $db);
if($conn->connect_error) die($conn->connect_error);
$error_msg = null;
parse_str($_SERVER['QUERY_STRING'], $output);
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
            if(isset($output['redirect']) && !strcmp($output['redirect'],'telework') && !$user['employeeOf'])
                header("Location: suspension-telework.php");
            else
                header("Location: telework.php");
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
                <a href="../../signup.php" class="btn btn-success">ΔΗΜΙΟΥΡΓΙΑ ΝΕΟΥ ΛΟΓΑΡΙΑΣΜΟΥ</a>
            </form>
        </div>
        END;
}
?>

<div style="height: 1500px">
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
                    <li><a class="drop" href="../employees.php">ΕΡΓΑΖΟΜΕΝΟΙ</a>
                        <ul>
                            <li><a href="../LEAVE/leave.php">ΑΔΕΙΕΣ</a></li>
                            <li><a href="../../MISC/under_constr.html">ΕΠΙΔΟΜΑΤΑ</a></li>
                            <li><a href="../../MISC/under_constr.html">ΣΥΜΒΑΣΕΙΣ ΕΡΓΑΣΙΑΣ</a></li>
                            <li><a href="workcond.php">ΣΥΝΘΗΚΕΣ ΕΡΓΑΣΙΑΣ</a></li>
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
                    <li><a class="drop" href="../../COVID-19/covid-19.php">COVID-19</a>
                        <ul>
                            <li><a href="../../COVID-19/general_information.php">ΓΕΝΙΚΕΣ ΠΛΗΡΟΦΟΡΙΕΣ</a></li>
                            <li><a href="../../COVID-19/EMPLOYEES/employees.php">ΠΛΗΡΟΦΟΡΙΕΣ ΓΙΑ ΤΟΝ ΕΡΓΑΖΟΜΕΝΟ</a></li>
                            <li><a href="../../COVID-19/EMPLOYERS/employers.php">ΠΛΗΡΟΦΟΡΙΕΣ ΓΙΑ ΤΟΝ ΕΡΓΟΔΟΤΗ</a></li>
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
            <h1  class="pagetitle">Τηλεργασία</h1>
            <ul>
                <li><a href="../../../index.php">ΑΡΧΙΚΗ</a></li>
                <li><a href="../employees.php">ΕΡΓΑΖΟΜΕΝΟΙ</a></li>
                <li><a href="workcond.php">ΣΥΝΘΗΚΕΣ ΕΡΓΑΣΙΑΣ</a></li>
                <li style="color: dodgerblue">ΤΗΛΕΡΓΑΣΙΑ</li>
            </ul>
            <!-- ################################################################################################ -->
        </div>

    </div>

    <div  style="margin-bottom: 0px" class="hoc menu_content wrapper row2">
        <?php
        if(isset($_SESSION["name"])){
            $username = $_SESSION['name'];
            $query = "select * from User where username = '$username' ";
            $result = mysqli_query($conn, $query);
            if (!$result) die($conn->error);
            $user = mysqli_fetch_assoc($result);
            if(!$user['employeeOf'])
                echo '<a href="suspension-telework.php" class="btn btn-info go-to-button" style="height: 50px;text-align: center;display: inline-flex;align-items: center; "><i class="fa fa-pencil-square-o" style="font-size: 20px" aria-hidden="true"></i> ΔΗΛΩΣΗ ΕΞ\' ΑΠΟΣΤΑΣΕΩΣ ΕΡΓΑΣΙΑΣ</a></div><div style="margin-bottom: 500px; margin-top: 110px" class="wrapper row3">';
            else
                echo '</div><div style="margin-bottom: 500px; margin-top: 50px" class="wrapper row3">';
        }
        else if(isset($output['employee']) && !strcmp($output['employee'],"true"))
            echo '</div><div style="margin-bottom: 500px; margin-top: 50px" class="wrapper row3">';
        else
            echo '<button id="redirect_trigger" class="btn btn-info go-to-button" style="height: 50px;text-align: center;display: inline-flex;align-items: center; "><i class="fa fa-pencil-square-o" style="font-size: 20px" aria-hidden="true"></i> ΔΗΛΩΣΗ ΕΞ\' ΑΠΟΣΤΑΣΕΩΣ ΕΡΓΑΣΙΑΣ</button></div><div style="margin-bottom: 500px; margin-top: 110px" class="wrapper row3">';

        ?>


        <main class="hoc container clear">
            <!-- main body -->
            <!-- ################################################################################################ -->
            <div class="content text">
                <!-- ################################################################################################ -->
                <h3 style="text-transform: none"><b>Τι είναι η τηλεργασία;</b></h3>
                <ul>
                    <p>Η τηλεργασία είναι ένας ευρύτερος όρος, που αναφέρεται στην υποκατάσταση των τηλεπικοινωνιών με οποιαδήποτε μορφή ταξιδιού που σχετίζεται με εργασία, εκμηδενίζοντας μ' αυτόν τον τρόπο τους περιορισμούς της απόστασης στις μετακινήσεις.</p>
                </ul>
                <h3><b>Κανόνες τηλεργασίας</b></h3>
                <ul>
                    <li>Τηλεργασία είναι η συστηµατική εξ αποστάσεως παροχή της εξαρτηµένης εργασίας του εργαζομένου και µε τη χρήση της τεχνολογίας, δυνάµει της σύµβασης εργασίας πλήρους, μερικής, εκ περιτροπής ή άλλης μορφής απασχόλησης, η οποία θα µπορούσε να παρασχεθεί και από τις εγκαταστάσεις του εργοδότη.</li>
                    <li>Η τηλεργασία συμφωνείται µεταξύ εργοδότη και εργαζοµένου κατά την πρόσληψη ή µε µεταγενέστερη συµφωνία. Κατ’ εξαίρεση για λόγους δηµόσιας υγείας ή σε περίπτωση κινδύνου υγείας του εργαζοµένου, <b>ο εργοδότης οφείλει να αποδεχθεί την πρόταση του εργαζοµένου για τηλεργασία</b>, εκτός κι αν αδυνατεί για σπουδαίο και σοβαρό κατ’ αντικειµενική κρίση λόγο, τον οποίο οφείλει να εκθέσει εγγράφως προς τον εργαζόµενο. Σε περίπτωση κινδύνου δηµόσιας υγείας,<b> ο εργαζόµενος οφείλει να αποδεχθεί την πρόταση του εργοδότη για τηλεργασία,</b> εκτός κι αν αδυνατεί να το πράξει για σπουδαίο και σοβαρό κατ’ αντικειµενική κρίση λόγο, τον οποίο οφείλει να εκθέσει εγγράφως προς τον εργοδότη.</li>
                    <li>Εντός 8 ηµερών από την έναρξη της τηλεργασίας, ο εργοδότης υποχρεούται να γνωστοποιήσει εγγράφως προς τον εργαζόµενο το σύνολο των πληροφοριών που αναφέρονται στην εκτέλεση της εργασίας.</li>
                    <li>Η συµφωνία περί τηλεργασίας <b>δεν θίγει το καθεστώς απασχόλησης και εν γένει τη σύµβαση εργασίας του τηλεργαζοµένου</b> ως πλήρους, µερικής, εκ περιτροπής ή άλλης µορφής απασχόλησης, αλλά µεταβάλλει µόνο τον τρόπο µε τον οποίο εκτελείται η εργασία. Η τηλεργασία µπορεί να παρέχεται κατά πλήρη, µερική ή εκ περιτροπής απασχόληση, αυτοτελώς ή σε συνδυασµό µε απασχόληση στις εγκαταστάσεις του εργοδότη.</li>
                    <li>Oι τηλεργαζόµενοι <b>έχουν τα ίδια δικαιώµατα και υποχρεώσεις µε τους συγκρίσιμους εργαζόμενους εντός των εγκαταστάσεων της επιχείρησης</b>, ιδίως σχετικώς µε τον όγκο εργασίας, τα κριτήρια και τις διαδικασίες αξιολόγησης, τις τυχόν επιβραβεύσεις, την πρόσβαση σε πληροφορίες που αφορούν στην επιχείρηση, την κατάρτιση και επαγγελµατική τους εξέλιξη, τη συµµετοχή σε σωµατεία, τη συνδικαλιστική τους δράση και την απρόσκοπτη και εµπιστευτική επικοινωνία τους µε τους συνδικαλιστικούς τους εκπροσώπους.</li>
                    <li>Ο εργοδότης υποχρεούται <b>να τηρεί το ωράριο εργασίας και να σέβεται την ιδιωτική ζωή του τηλεργαζοµένου</b>. Απαγορεύεται ρητά η χρήση της κάµερας (web cam) για τον έλεγχο του τηλεργαζοµένου. Αν τεθεί σε λειτουργία οιοδήποτε σύστηµα ελέγχου, αυτό πρέπει να σέβεται τη νοµοθεσία περί δεδοµένων προσωπικού χαρακτήρα, να επιβάλλεται από τις λειτουργικές ανάγκες της επιχείρησης και να περιορίζεται στο πλαίσιο του επιδιωκόµενου σκοπού.</li>
                    <li>Ο εργοδότης είναι υπεύθυνος για την προστασία της υγείας και της επαγγελµατικής ασφάλειας του τηλεργαζοµένου, σύµφωνα µε τις ισχύουσες διατάξεις. Ο εργοδότης πληροφορεί τον τηλεργαζόµενο για την πολιτική της επιχείρησης όσον αφορά την υγεία και την ασφάλεια στην εργασία, η οποία περιλαµβάνει ιδίως τις προδιαγραφές του χώρου τηλεργασίας, κανόνες χρήσης οθονών οπτικής απεικόνισης, διαλείµµατα και κάθε άλλο αναγκαίο στοιχείο.</li>
                    <li>Οι εκπρόσωποι των εργαζοµένων πληροφορούνται και γνωµοδοτούν για την εισαγωγή της τηλεργασίας (µετατροπή εργασίας σε τηλεργασία, δικαίωµα στην αποσύνδεση κ.ά.), όπως προβλέπουν οι διατάξεις περί σωµατείων και συνδικαλιστικών οργανώσεων.</li>
                    <li>Το ωράριο τηλεργασίας καθώς και η αναλογία τηλεργασίας και εργασίας στις εγκαταστάσεις του εργοδότη <b>δηλώνονται στο σύστηµα «Εργάνη».</b></li>
                    <li>Με απόφαση του υπουργού Εργασίας και Κοινωνικών Υποθέσεων, καθορίζεται ο τρόπος υπολογισµού του πρόσθετου κόστους µε το οποίο επιβαρύνεται περιοδικώς ο τηλεργαζόµενος από την τηλεργασία </li>
                </ul>
            <!-- ################################################################################################ -->
            <!-- / main body -->
            <div class="clear"></div>
        </main>
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
    <script>

        var popupOpenRedirect_log = document.getElementById('redirect_trigger');
        if(popupOpenRedirect_log) {
            popupOpenRedirect_log.addEventListener('click', () => {
                popup_log.classList.add('popup-active');
                if (history.pushState) {
                    var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?login=true&redirect=telework';
                    window.history.pushState({path: newurl}, '', newurl);
                }

            });
        }
    </script>
</body>
</html>
