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
            parse_str($_SERVER['QUERY_STRING'], $output);
            if(isset($output['redirect']) && !strcmp($output['redirect'],'parental') && $user['employeeOf'])
                header("Location: parental.php");
            else
                header("Location: parental_leave.php");
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
                            <li><a href="leave.php">ΑΔΕΙΕΣ</a></li>
                            <li><a href="../../MISC/under_constr.html">ΕΠΙΔΟΜΑΤΑ</a></li>
                            <li><a href="../../MISC/under_constr.html">ΣΥΜΒΑΣΕΙΣ ΕΡΓΑΣΙΑΣ</a></li>
                            <li><a href=../WORKCOND/workcond.php">ΣΥΝΘΗΚΕΣ ΕΡΓΑΣΙΑΣ</a></li>
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
            <h1  class="pagetitle">Γονική Άδεια</h1>
            <ul>
                <li><a href="../../../index.php">ΑΡΧΙΚΗ</a></li>
                <li><a href="../employees.php">ΕΡΓΑΖΟΜΕΝΟΙ</a></li>
                <li><a href="leave.php">ΑΔΕΙΕΣ</a></li>
                <li style="color: dodgerblue">ΓΟΝΙΚΗ ΑΔΕΙΑ</li>
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
                if($user['employeeOf'])
                    echo '<a href="parental.php" class="btn btn-info go-to-button" style="height: 50px;text-align: center;display: inline-flex;align-items: center; "><i class="fa fa-pencil-square-o" style="font-size: 20px" aria-hidden="true"></i> ΔΗΛΩΣΗ ΓΟΝΙΚΗΣ ΑΔΕΙΑΣ</a></div><div style="margin-bottom: 500px; margin-top: 110px" class="wrapper row3">';
                else
                    echo '</div><div style="margin-bottom: 500px; margin-top: 50px" class="wrapper row3">';
            }
            else
                echo '<button id="redirect_trigger" class="btn btn-info go-to-button" style="height: 50px;text-align: center;display: inline-flex;align-items: center; "><i class="fa fa-pencil-square-o" style="font-size: 20px" aria-hidden="true"></i> ΔΗΛΩΣΗ ΓΟΝΙΚΗΣ ΑΔΕΙΑΣ</button></div><div style="margin-bottom: 500px; margin-top: 110px" class="wrapper row3">';

            ?>

        <main class="hoc container clear">
            <!-- main body -->
            <!-- ################################################################################################ -->
            <div class="content text">
                <!-- ################################################################################################ -->
                <h4>Οι γονικές άδειες των εργαζόμενων διακρίνονται στις εξής κατηγορίες:</h4>
                <h3><b>Α. Άδεια θηλασμού και φροντίδας παιδιού</b></h3>
                <ul>
                    <p>Οι εργαζόμενες μητέρες δικαιούνται (εναλλακτικά μπορεί να ζητήσει και ο πατέρας,
                    εφόσον δεν κάνει χρήση η εργαζόμενη μητέρα) :</p>
                    <li>
                        Για το χρονικό διάστημα 30 μηνών από τη λήξη της άδειας λοχείας, δηλαδή 9
                        βδομάδες μετά τον τοκετό είτε να προσέρχονται
                        αργότερα, είτε να αποχωρούν νωρίτερα κατά μία ώρα κάθε ημέρα από την εργασία
                        τους.
                    </li>
                    <li>
                        Εναλλακτικά με συμφωνία του εργοδότη, το ημερήσιο ωράριο των μητέρων μπορεί
                        να ορίζεται μειωμένο κατά δύο (2) ώρες ημερησίως για τους πρώτους δώδεκα (12)
                        μήνες και σε μία (1) ώρα ημερησίως για έξη (6) επιπλέον μήνες.
                    </li>
                </ul>
                <h3><b>Β. Ειδική άδεια μητρότητας</b></h3>
                <ul>
                    <li>
                        Οι εργαζόμενες μητέρες οι οποίες είναι ασφαλισμένες στο ΙΚΑ-ΕΤΑΜ και απασχολούνται σε επιχειρήσεις ή
                        εκμεταλλεύσεις με σχέση εξαρτημένης εργασίας, ορισμένου ή αορίστου χρόνου, με
                        πλήρη ή μερική απασχόληση δικαιούνται ειδική άδεια προστασίας μητρότητας
                        χρονικής διάρκειας έξι (6) μηνών.
                    </li>
                    <li>
                        Η άδεια αυτή χορηγείται στις εργαζόμενες μετά τη λήξη της άδειας μητρότητας
                        (τοκετού-λοχείας) ή της ισόχρονης προς το μειωμένο ωράριο άδειας.
                    </li>
                    <li>
                        Η άδεια αυτή είναι με αποδοχές που είναι ίσες με τον κατώτατο μισθό όπως
                        ορίζεται κάθε φορά με βάση την ΕΓΣΕΕ και φορέας καταβολής είναι ο ΟΑΕΔ.
                    </li>

                </ul>
                <h3><b>Γ. Άδεια γέννησης παιδιού</b></h3>
                <ul>
                    <li>
                        Σε περίπτωση γέννησης παιδιού ο πατέρας δικαιούται δύο (2) ημέρες άδεια με
                        αποδοχές για κάθε παιδί. Οι άδειες αυτές είναι πρόσθετες και δεν συμψηφίζονται
                        με τις ημέρες κανονικής άδειας.
                    </li>
                </ul>
                <h3><b>Δ. Άδεια για τη φροντίδα υιοθετημένων παιδιών</b></h3>
                <ul>
                    <li>
                        Το δικαίωμα διακοπής της εργασίας ή καθυστερημένης προσέλευσης ή πρόωρης
                        αποχώρησης της μητέρας και εναλλακτικά του πατέρα για τη φροντίδα παιδιών έχουν και οι θετοί γονείς παιδιών
                        ηλικίας έως 6 ετών την άδεια φροντίδας παιδιού
                        δικαιούνται και οι άγαμοι γονείς, με χρονική αφετηρία την ημερομηνία της
                        υιοθεσίας.
                    </li>
                </ul>
                <h3><b>Ε. Άδεια ανατροφής παιδιών</b></h3>
                <ul>
                    <li>
                        Ο εργαζόμενος γονέας έχει δικαίωμα γονικής άδειας ανατροφής του παιδιού μέχρις
                        ότου συμπληρώσει την ηλικία των έξι (6) ετών.
                    </li>
                    <li>
                        Για τη χορήγηση της γονικής άδειας ανατροφής οι εργαζόμενοι πρέπει να έχουν
                        συμπληρώσει ένα (1) χρόνο συνεχόμενης ή διακεκομμένης εργασίας στον ίδιο
                        εργοδότη, εκτός αν ορίζεται ευνοϊκότερα από ειδική διάταξη νόμων.
                    </li>
                    <li>
                        Η γονική άδεια ανατροφής είναι άνευ αποδοχών, χορηγείται εγγράφως για περίοδο
                        τουλάχιστον τεσσάρων (4) μηνών και αποτελεί ατομικό δικαίωμα κάθε γονέα, χωρίς
                        δυνατότητα μεταβίβασης.
                    </li>
                    <li>
                        Η γονική άδεια ανατροφής χορηγείται εφάπαξ ή τμηματικά.
                    </li>
                </ul>
                <h3><b>ΣΤ. Άδεια για ασθένεια μελών οικογένειας</b></h3>
                <ul>
                    <li>
                        Οι μισθωτοί, σε περίπτωση ασθένειας εξαρτώμενων παιδιών ή άλλων μελών της
                        οικογένειάς τους, έχουν δικαίωμα να λάβουν άδεια ίση με έξι (6) εργάσιμες ημέρες
                        για κάθε ημερολογιακό έτος.
                    </li>
                    <li>
                        Η εν λόγω άδεια, είναι δυνατόν να χορηγηθεί εφάπαξ ή τμηματικά, ενώ μπορεί να
                        αυξηθεί σε οκτώ (8) εργάσιμες ημέρες εάν ο δικαιούχος έχει δύο παιδιά ή
                        δεκατέσσερις (14) εργάσιμες ημέρες εάν έχει τρία παιδιά και άνω.
                    </li>
                    <li>
                        Σημειωτέον ότι, η άδεια αυτή, χορηγείται χωρίς αποδοχές.
                    </li>
                </ul>
                <h3><b>Ζ. Άδεια για παρακολούθηση σχολικής επίδοσης</b></h3>
                <ul>
                    <li>
                        Οι εργαζόμενοι με σχέση εξαρτημένης εργασίας ιδιωτικού δικαίου, που έχουν
                        παιδιά ηλικίας μέχρι 16 ετών τα οποία παρακολουθούν μαθήματα στοιχειώδους ή
                        μέσης εκπαίδευσης, δικαιούνται να απουσιάζουν ορισμένες ώρες ή ολόκληρη την
                        ημέρα από την εργασία τους, μέχρι την συμπλήρωση τεσσάρων (4) εργάσιμων
                        ημερών κάθε ημερολογιακό έτος, με άδεια του εργοδότη, για να επισκεφθούν το
                        σχολείο των παιδιών τους, με σκοπό την παρακολούθηση της σχολικής τους
                        επίδοσης.
                    </li>
                    <li>
                        Η άδεια απουσίας χορηγείται στον ένα από τους δύο γονείς.
                    </li>
                </ul>
                <h3><b>Η. Μειωμένο ωράριο για παιδιά με αναπηρία</b></h3>
                <ul>
                    <p>Οι γονείς που έχουν παιδιά ανεξαρτήτως ηλικίας με πνευματική, σωματική ή ψυχική
                        αναπηρία, δικαιούνται μειωμένου ωραρίου για να μπορούν να αφιερώσουν περισσότερο
                        χρόνο στις ειδικές φροντίδες των παιδιών. Προϋποθέσεις :</p>
                    <li>
                        Να εργάζεται σε επιχείρηση που απασχολεί τουλάχιστον 50 άτομα.
                    </li>
                    <li>
                        Να έχει παιδί με πνευματική, ψυχική ή σωματική αναπηρία.
                    </li>
                    <li>
                        Το ωράριο του εργαζόμενου γονέα σ&#39; αυτήν την περίπτωση μειώνεται κατά 1 ώρα
                        την ημέρα, με ανάλογη περικοπή των αποδοχών.
                    </li>
                    <li>
                        Η πνευματική, σωματική ή ψυχική αναπηρία του παιδιού διαπιστώνεται με ιατρική
                        γνωμάτευση.
                    </li>
                </ul>
                <h3><b>Θ. Άδεια για μονογονεϊκές οικογένειες(Τρίτεκνοι-Πολύτεκνοι)</b></h3>
                <ul>
                    <li>
                        Στους εργαζόμενους – ες που έχουν χηρέψει και στον άγαμο – άγαμη γονέα, που
                        έχουν την επιμέλεια του παιδιού, χορηγείται άδεια με αποδοχές έξι εργάσιμων
                        ημερών το χρόνο, πέραν αυτής που δικαιούνται με άλλες διατάξεις.
                    </li>
                    <li>
                        Γονέας με τρία παιδιά ή περισσότερα, δικαιούται άδεια οκτώ εργάσιμων ημερών. Η
                        άδεια αυτή χορηγείται λόγω αυξημένων αναγκών φροντίδας των παιδιών, ηλικίας
                        μέχρι 12 ετών συμπληρωμένων, χορηγείται εφάπαξ ή τμηματικά μετά από
                        συνεννόηση με τον εργοδότη σύμφωνα με τις ανάγκες του γονέα και δεν πρέπει να
                        συμπίπτει χρονικά με την αρχή ή το τέλος της ετήσιας κανονικής άδειας.
                    </li>
                </ul>
                <h3><b>Ι. Γονική άδεια παιδιού το οποίο πάσχει από σοβαρό νόσημα</b></h3>
                <ul>
                    <li>
                        Στο φυσικό, θετό ή ανάδοχο γονέα παιδιού ηλικίας έως 18 ετών συμπληρωμένων, το οποίο
                        πάσχει από νόσημα που απαιτεί μεταγγίσεις αίματος και παραγώγων του ή αιμοκάθαρση,
                        από νεοπλασματική ασθένεια, ή χρήζει μεταμόσχευσης και σύνδρομο Down, χορηγείται
                        ειδική γονική άδεια, διάρκειας δέκα εργάσιμων ημερών κατ΄ έτος, με αποδοχές, έπειτα από
                        αίτησή του, κατά απόλυτη προτεραιότητα.
                    </li>
                </ul>
                <h3><b>Κ. Άδεια φροντίδας παιδιού με τη παρένθετη μητρότητα</b></h3>
                <ul>
                    <li>
                        Σε περίπτωση απόκτησης παιδιού με τη διαδικασία της παρένθετης μητρότητας οι
                        αποκτώντες γονείς δικαιούνται τις άδειες που αφορούν τη φροντίδα και την ανατροφή του
                        παιδιού, ως εάν ήσαν φυσικοί γονείς. Κατά την διάρκεια του θηλασμού, το μειωμένο
                        ωράριο του άρθρου 9 ΕΓΣΣΕ 1993 όπως ισχύει δικαιούνται η γυναίκα που γέννησε και η
                        αποκτώσα μητέρα.
                    </li>
                </ul>
            </div>
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
                var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?login=true&redirect=parental';
                window.history.pushState({path: newurl}, '', newurl);
            }

        });
    }
</script>
</body>
</html>
