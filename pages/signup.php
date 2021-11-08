<?php

    session_start();

    if(isset($_SESSION["name"])){
        header("Location:../index.php");
        exit();
    }

    require_once '../dblogin.php';
    $conn = mysqli_connect($hn, $un, $pw, $db);
    if($conn->connect_error) die($conn->connect_error);
    $errmsg = null;
    $error_msg = null;
    if(isset($_POST['submit'])) {

        $isEmployee = 0;

        $afm = $_POST['afm'];
        $amka = $_POST['amka'];
        $username = $_POST['uname'];
        $password = password_hash($_POST['pwd'], PASSWORD_DEFAULT);
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $firstname = $_POST['fname'];
        $lastname = $_POST['lname'];
        $compAfm = $_POST['compAfm'];
        $compName = $_POST['compName'];
        $compEmail = $_POST['compEmail'];
        $compPhone = $_POST['compPhone'];
        $doi = $_POST['doi'];
        $ika = $_POST['ika'];

        if (!strcmp($_POST['category'], "employee"))
            $isEmployee = 1;


        $query = "select * from User where id = '$afm'";
        $result = mysqli_query($conn, $query);
        if (!$result) die($conn->error);
        $idExists = $result->num_rows;

        $query = "select * from User where username = '$username'";
        $result = mysqli_query($conn, $query);
        if (!$result) die($conn->error);
        $usernameExists = $result->num_rows;

        $query = "select * from User where email = '$email'";
        $result = mysqli_query($conn, $query);
        if (!$result) die($conn->error);
        $emailExists = $result->num_rows;

        $query = "select * from User where amka = '$amka'";
        $result = mysqli_query($conn, $query);
        if (!$result) die($conn->error);
        $amkaExists = $result->num_rows;



        if($idExists)
            $errmsg = "<div class=\"alert alert-danger\">
                        <strong>Ο Αριθμός Φορολογικού Μητρώου χρησιμοποείται ήδη!</strong></div>";
        else if($amkaExists)
            $errmsg =  "<div class=\"alert alert-danger\">
                        <strong>Ο Αριθμός Μητρώου Κοινωνικής Ασφάλισης χρησιμοποείται ήδη!</strong></div>";
        else if ($usernameExists)
            $errmsg = "<div class=\"alert alert-danger\">
                        <strong>Το όνομα χρήστη χρησιμοποείται ήδη!</strong></div>";
        else if($emailExists)
            $errmsg =  "<div class=\"alert alert-danger\">
                        <strong>Η διεύθυνση e-mail χρησιμοποείται ήδη!</strong></div>";
        else {
            $query = "select * from Company where id = '$compAfm'";
            $result = mysqli_query($conn, $query);
            if (!$result) die($conn->error);
            $compIdExists = $result->num_rows;

            if ($isEmployee) {
                if (!$compIdExists)
                    $errmsg = "<div class=\"alert alert-danger\">
                        <strong>Ο Αριθμός Φορολογικού Μητρώου της εταιρείας δεν έχει καταχωρηθεί!</strong></div>";
                else{
                    $query = "insert into User values ('$afm','$amka','$username','$password','$email','$phone','$firstname','$lastname','$compAfm')";
                    mysqli_query($conn, $query);
                    $_SESSION["name"] = $username;
                    header("Location: ../index.php");
                }
            }
            else{

                $query = "select * from Company where email = '$compEmail'";
                $result = mysqli_query($conn, $query);
                if (!$result) die($conn->error);
                $compEmailExists = $result->num_rows;

                if ($compIdExists)
                    $errmsg = "<div class=\"alert alert-danger\">
                        <strong>Ο Αριθμός Φορολογικού Μητρώου της εταιρείας χρησιμοποείται ήδη!</strong></div>";
                else if($compEmailExists)
                    $errmsg =  "<div class=\"alert alert-danger\">
                        <strong>Η διεύθυνση e-mail της εταιρείας χρησιμοποείται ήδη!</strong></div>";
                else {
                    $query = "insert into User values ('$afm','$amka','$username','$password','$email','$phone','$firstname','$lastname',null)";
                    mysqli_query($conn, $query);
                    $query = "insert into Company values ('$compAfm','$compName','$compEmail','$compPhone','$doi','$ika','$afm')";
                    mysqli_query($conn, $query);
                    $_SESSION["name"] = $username;
                    header("Location: ../index.php");
                }
            }
        }

    }

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
                header("Location: ../index.php");
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
    <link href="../styles/layout.css" rel="stylesheet" type="text/css" media="all">
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
                <a href="signup.php" class="btn btn-success">ΔΗΜΙΟΥΡΓΙΑ ΝΕΟΥ ΛΟΓΑΡΙΑΣΜΟΥ</a>
            </form>
        </div>
        END;
}
?>


<div class="wrapper row0">
    <header id="header" class="hoc clear">
        <?php
        if(!isset($_SESSION['name']))
            echo "<div id=\"log_sign\" ><button id=\"login_button\" class=\"btn btn-light login popup-trigger\">Συνδεση</button> <a href=\"signup.php\" id=\"signup\" class=\"btn btn-light\">Εγγραφη</a></div>";
        else {
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
        }
        ?>


        <!-- ################################################################################################ -->
        <div  id="logo" class="first">
            <a href="../index.php"><img src="../images/logo.jpg"  /></a>
        </div>

        <a onclick="history.back()" class="prev_button">&laquo; Επιστροφή</a>
        <!-- ################################################################################################ -->
    </header>

</div>



<!-- ################################################################################################ -->
<!-- ################################################################################################ -->
<!-- ################################################################################################ -->
<form id="form" class="menu_content wrapper hoc form-box" action="" method="post">

    <h1>Δημιουργία Λογαριασμού</h1>
    <?php
        if($errmsg != null)
            echo $errmsg;
    ?>

    <div class="form-controls">
        <label for="afm">ΑΦΜ <span class="text-danger">*</span></label>
        <input name="afm" type="text">
        <i class="fas fa-check-circle"></i>
        <i class="fas fa-exclamation-circle"></i>
        <small class="text-danger"></small>
    </div>
    <div class="form-controls">
        <label for="amka">Α.Μ.Κ.Α <span class="text-danger">*</span></label>
        <input name="amka" type="text">
        <i class="fas fa-check-circle"></i>
        <i class="fas fa-exclamation-circle"></i>
        <small class="text-danger"></small>
    </div>
    <div class="form-controls">
        <label for="fname">Όνομα <span class="text-danger">*</span></label>
        <input name="fname" type="text">
        <i class="fas fa-check-circle"></i>
        <i class="fas fa-exclamation-circle"></i>
        <small class="text-danger"></small>
    </div>
    <div class="form-controls">
        <label for="lname">Επώνυμο <span class="text-danger">*</span></label>
        <input name="lname" type="text">
        <i class="fas fa-check-circle"></i>
        <i class="fas fa-exclamation-circle"></i>
        <small class="text-danger"></small>
    </div>
    <div class="form-controls">
        <label for="uname">Όνομα Χρήστη <span class="text-danger">*</span></label>
        <input name="uname" type="text">
        <i class="fas fa-check-circle"></i>
        <i class="fas fa-exclamation-circle"></i>
        <small class="text-danger"></small>
    </div>
    <div class="form-controls">
        <label for="pwd">Κωδικός <span class="text-danger">*</span></label>
        <input name="pwd" type="password">
        <i class="fas fa-check-circle"></i>
        <i class="fas fa-exclamation-circle"></i>
        <small class="text-danger"></small>
    </div>
    <div class="form-controls">
        <label for="pwdConf">Επαλήθευση Κωδικού</label>
        <input name="pwdConf" type="password">
        <i class="fas fa-check-circle"></i>
        <i class="fas fa-exclamation-circle"></i>
        <small class="text-danger"></small>
    </div>
    <div class="form-controls">
        <label for="email">E-mail <span class="text-danger">*</span></label>
        <input name="email" type="text">
        <i class="fas fa-check-circle"></i>
        <i class="fas fa-exclamation-circle"></i>
        <small class="text-danger"></small>
    </div>
    <div class="form-controls">
        <label for="phone">Τηλέφωνο</label>
        <input name="phone" type="text">
        <i class="fas fa-check-circle"></i>
        <i class="fas fa-exclamation-circle"></i>
        <small class="text-danger"></small>
    </div>
    <label style="margin-bottom: 15px;">Κατηγορία <span class="text-danger">*</span></label>
    <div class="form-controls">
        <p>
            Εργαζόμενος<input name="category" type="radio" value="employee">
        </p>
        <p>
            Εργοδότης<input name="category" type="radio" value="employer">
        </p>
        <small id="radioWarning" class="text-danger"></small>
    </div>
    <div class="form-controls">
        <label for="compAfm">ΑΦΜ Εταιρείας <span class="text-danger">*</span></label>
        <input name="compAfm" type="text">
        <i class="fas fa-check-circle"></i>
        <i class="fas fa-exclamation-circle"></i>
        <small class="text-danger"></small>
    </div>
    <div class="form-extras">
        <div class="form-controls">
            <label for="compName">Όνομα Εταιρείας <span class="text-danger">*</span></label>
            <input name="compName" type="text">
            <i class="fas fa-check-circle"></i>
            <i class="fas fa-exclamation-circle"></i>
            <small class="text-danger"></small>
        </div>
        <div class="form-controls">
            <label for="compEmail">E-mail Εταιρείας <span class="text-danger">*</span></label>
            <input name="compEmail" type="text">
            <i class="fas fa-check-circle"></i>
            <i class="fas fa-exclamation-circle"></i>
            <small class="text-danger"></small>
        </div>
        <div class="form-controls">
            <label for="compPhone">Τηλέφωνο Εταιρείας</label>
            <input name="compPhone" type="text">
            <i class="fas fa-check-circle"></i>
            <i class="fas fa-exclamation-circle"></i>
            <small class="text-danger"></small>
        </div>
        <div class="form-controls">
            <label for="doi">Δ.Ο.Υ <span class="text-danger">*</span></label>
            <select name="doi" style="width:520px">
                <option selected="selected" hidden value="null">--Επιλέξτε--</option>

                <option>3211-ΑΓΙΑΣ</option>

                <option>1151-ΑΓΙΑΣ ΠΑΡΑΣΚΕΥΗΣ</option>

                <option>4231-ΑΓΙΟΥ ΑΘΑΝΑΣΙΟΥ</option>

                <option>1129-ΑΓΙΟΥ ΔΗΜΗΤΡΙΟΥ</option>

                <option>7311-ΑΓΙΟΥ ΚΗΡΥΚΟΥ ΙΚΑΡΙΑΣ</option>

                <option>8221-ΑΓΙΟΥ ΝΙΚΟΛΑΟΥ</option>

                <option>1307-ΑΓΙΟΥ ΣΤΕΦΑΝΟΥ</option>

                <option>1136-ΑΓΙΩΝ ΑΝΑΡΓΥΡΩΝ</option>

                <option>1552-ΑΓΡΙΝΙΟΥ</option>

                <option>1101-ΑΘΗΝΩΝ Α'</option>

                <option>1102-ΑΘΗΝΩΝ Β'</option>

                <option>1103-ΑΘΗΝΩΝ Γ'</option>

                <option>1104-ΑΘΗΝΩΝ Δ'</option>

                <option>1105-ΑΘΗΝΩΝ Ε'</option>

                <option>1107-ΑΘΗΝΩΝ Ζ'</option>

                <option>1108-ΑΘΗΝΩΝ Η'</option>

                <option>1109-ΑΘΗΝΩΝ Θ'</option>

                <option>1110-ΑΘΗΝΩΝ Ι'</option>

                <option>1111-ΑΘΗΝΩΝ ΙΑ'</option>

                <option>1112-ΑΘΗΝΩΝ ΙΒ'</option>

                <option>1113-ΑΘΗΝΩΝ ΙΓ'</option>

                <option>1114-ΑΘΗΝΩΝ ΙΔ'</option>

                <option>1115-ΑΘΗΝΩΝ ΙΕ'</option>

                <option>1117-ΑΘΗΝΩΝ ΙΖ'</option>

                <option>1124-ΑΘΗΝΩΝ ΙΗ'</option>

                <option>1126-ΑΘΗΝΩΝ ΙΘ'</option>

                <option>1116-ΑΘΗΝΩΝ ΙΣΤ'</option>

                <option>1140-ΑΘΗΝΩΝ Κ'</option>

                <option>1141-ΑΘΗΝΩΝ ΚΑ'</option>

                <option>1142-ΑΘΗΝΩΝ ΚΒ'</option>

                <option>1143-ΑΘΗΝΩΝ ΚΓ'</option>

                <option>1106-ΑΘΗΝΩΝ ΣΤ'</option>

                <option>1118-ΑΘΗΝΩΝ ΦΑΒΕ</option>

                <option>1159-ΑΘΗΝΩΝ ΦΑΕΕ</option>

                <option>1137-ΑΙΓΑΛΕΩ</option>

                <option>1301-ΑΙΓΙΝΑΣ</option>

                <option>4714-ΑΙΓΙΝΙΟΥ</option>

                <option>2311-ΑΙΓΙΟΥ</option>

                <option>2312-ΑΚΡΑΤΑΣ</option>

                <option>4111-ΑΛΕΞΑΝΔΡΕΙΑΣ</option>

                <option>5211-ΑΛΕΞΑΝΔΡΟΥΠΟΛΗΣ</option>

                <option>3311-ΑΛΜΥΡΟΥ</option>

                <option>2411-ΑΜΑΛΙΑΔΑΣ</option>

                <option>1135-ΑΜΑΡΟΥΣΙΟΥ</option>

                <option>4233-ΑΜΠΕΛΟΚΗΠΩΝ</option>

                <option>4811-ΑΜΥΝΤΑΙΟΥ</option>

                <option>1821-ΑΜΦΙΚΛΕΙΑΣ</option>

                <option>1511-ΑΜΦΙΛΟΧΙΑΣ</option>

                <option>1912-ΑΜΦΙΣΣΑΣ</option>

                <option>2423-ΑΝΔΡΙΤΣΑΙΝΑΣ</option>

                <option>7111-ΑΝΔΡΟΥ</option>

                <option>1180-ΑΝΩ ΛΙΟΣΙΩΝ</option>

                <option>9311-ΑΡΓΟΣΤΟΛΙΟΥ</option>

                <option>2111-ΑΡΓΟΥΣ</option>

                <option>4313-ΑΡΓΟΥΣ ΟΡΕΣΤΙΚΟΥ</option>

                <option>1177-ΑΡΓΥΡΟΥΠΟΛΗΣ</option>

                <option>2641-ΑΡΕΟΠΟΛΗΣ</option>

                <option>4611-ΑΡΙΔΑΙΑΣ</option>

                <option>8131-ΑΡΚΑΛΟΧΩΡΙΟΥ</option>

                <option>4911-ΑΡΝΑΙΑΣ</option>

                <option>6111-ΑΡΤΑΣ</option>

                <option>1521-ΑΣΤΑΚΟΥ</option>

                <option>1822-ΑΤΑΛΑΝΤΗΣ</option>

                <option>1302-ΑΧΑΡΝΩΝ</option>

                <option>8433-ΒΑΜΟΥ ΧΑΝΙΩΝ (Τ.Γ.)</option>

                <option>2414-ΒΑΡΔΑΣ</option>

                <option>4112-ΒΕΡΟΙΑΣ</option>

                <option>3321-ΒΟΛΟΥ Α'</option>

                <option>3322-ΒΟΛΟΥ Β'</option>

                <option>1522-ΒΟΝΙΤΣΑΣ</option>

                <option>1152-ΒΥΡΩΝΑ</option>

                <option>1179-ΓΑΛΑΤΣΙΟΥ</option>

                <option>2741-ΓΑΡΓΑΛΙΑΝΩΝ</option>

                <option>2413-ΓΑΣΤΟΥΝΗΣ</option>

                <option>4290-ΓΕΝ. ΕΣΟΔΩΝ ΘΕΣ/ΝΙΚΗΣ</option>

                <option>1290-ΓΕΝ. ΕΣΟΔΩΝ ΠΕΙΡΑΙΑ</option>

                <option>4621-ΓΙΑΝΝΙΤΣΩΝ</option>

                <option>1139-ΓΛΥΦΑΔΑΣ</option>

                <option>4421-ΓΟΥΜΕΝΙΣΣΑΣ</option>

                <option>4521-ΓΡΕΒΕΝΩΝ</option>

                <option>2611-ΓΥΘΕΙΟΥ</option>

                <option>1144-ΔΑΦΝΗΣ</option>

                <option>6313-ΔΕΛΒΙΝΑΚΙΟΥ (Τ.Γ.)</option>

                <option>2511-ΔΕΡΒΕΝΙΟΥ</option>

                <option>3222-ΔΕΣΚΑΤΗΣ</option>

                <option>2211-ΔΗΜΗΤΣΑΝΑΣ</option>

                <option>5221-ΔΙΔΥΜΟΤΕΙΧΟΥ</option>

                <option>1190-ΔΙΚ. ΕΙΣΠΡ. ΑΘΗΝΩΝ</option>

                <option>1811-ΔΟΜΟΚΟΥ</option>

                <option>5111-ΔΡΑΜΑΣ</option>

                <option>4631-ΕΔΕΣΣΑΣ</option>

                <option>3221-ΕΛΑΣΣΟΝΑΣ</option>

                <option>5341-ΕΛΕΥΘΕΡΟΥΠΟΛΗΣ</option>

                <option>1303-ΕΛΕΥΣΙΝΑΣ</option>

                <option>4291-ΕΝΣ. &amp; ΔΙΚ. ΕΙΣ. ΘΕΣ/ΝΙΚΗΣ</option>

                <option>1291-ΕΝΣ. &amp; ΔΙΚ. ΕΙΣ. ΠΕΙΡΑΙΑ</option>

                <option>4221-ΖΑΓΚΛΙΒΕΡΙΟΥ</option>

                <option>9111-ΖΑΚΥΝΘΟΥ</option>

                <option>2424-ΖΑΧΑΡΩΣ</option>

                <option>1172-ΖΩΓΡΑΦΟΥ</option>

                <option>6211-ΗΓΟΥΜΕΝΙΤΣΑΣ</option>

                <option>1173-ΗΛΙΟΥΠΟΛΗΣ</option>

                <option>5632-ΗΡΑΚΛΕΙΑΣ</option>

                <option>8111-ΗΡΑΚΛΕΙΟΥ Α'</option>

                <option>1145-ΗΡΑΚΛΕΙΟΥ ΑΤΤΙΚΗΣ</option>

                <option>8113-ΗΡΑΚΛΕΙΟΥ Β'</option>

                <option>5311-ΘΑΣΟΥ</option>

                <option>1551-ΘΕΡΜΟΥ</option>

                <option>6112-ΘΕΣΠΡΩΤΙΚΟΥ (Τ.Γ.)</option>

                <option>4211-ΘΕΣΣΑΛΟΝΙΚΗΣ Α'</option>

                <option>4212-ΘΕΣΣΑΛΟΝΙΚΗΣ Β'</option>

                <option>4213-ΘΕΣΣΑΛΟΝΙΚΗΣ Γ'</option>

                <option>4214-ΘΕΣΣΑΛΟΝΙΚΗΣ Δ'</option>

                <option>4215-ΘΕΣΣΑΛΟΝΙΚΗΣ Ε'</option>

                <option>4217-ΘΕΣΣΑΛΟΝΙΚΗΣ Ζ'</option>

                <option>4228-ΘΕΣΣΑΛΟΝΙΚΗΣ Η'</option>

                <option>4229-ΘΕΣΣΑΛΟΝΙΚΗΣ Θ'</option>

                <option>4227-ΘΕΣΣΑΛΟΝΙΚΗΣ Ι'</option>

                <option>4216-ΘΕΣΣΑΛΟΝΙΚΗΣ ΣΤ'</option>

                <option>4224-ΘΕΣΣΑΛΟΝΙΚΗΣ ΦΑΕ</option>

                <option>1411-ΘΗΒΑΣ</option>

                <option>7121-ΘΗΡΑΣ</option>

                <option>8211-ΙΕΡΑΠΕΤΡΑΣ</option>

                <option>9411-ΙΘΑΚΗΣ</option>

                <option>1154-ΙΛΙΟΥ</option>

                <option>1711-ΙΣΤΙΑΙΑΣ</option>

                <option>6311-ΙΩΑΝΝΙΝΩΝ Α'</option>

                <option>6312-ΙΩΑΝΝΙΝΩΝ Β'</option>

                <option>4234-ΙΩΝΙΑΣ ΘΕΣ/ΝΙΚΗΣ</option>

                <option>3323-ΙΩΝΙΑΣ ΜΑΓΝΗΣΙΑΣ</option>

                <option>5321-ΚΑΒΑΛΑΣ Α'</option>

                <option>5322-ΚΑΒΑΛΑΣ Β'</option>

                <option>2321-ΚΑΛΑΒΡΥΤΩΝ</option>

                <option>4232-ΚΑΛΑΜΑΡΙΑΣ</option>

                <option>2711-ΚΑΛΑΜΑΤΑΣ</option>

                <option>3411-ΚΑΛΑΜΠΑΚΑΣ</option>

                <option>1130-ΚΑΛΛΙΘΕΑΣ Α'</option>

                <option>1174-ΚΑΛΛΙΘΕΑΣ Β'</option>

                <option>7221-ΚΑΛΛΟΝΗΣ</option>

                <option>7511-ΚΑΛΥΜΝΟΥ</option>

                <option>6190-ΚΑΝΑΛΑΚΙΟΥ (Τ.Γ.)</option>

                <option>3111-ΚΑΡΔΙΤΣΑΣ</option>

                <option>7321-ΚΑΡΛΟΒΑΣΙΟΥ</option>

                <option>7521-ΚΑΡΠΑΘΟΥ</option>

                <option>1611-ΚΑΡΠΕΝΗΣΙΟΥ</option>

                <option>1721-ΚΑΡΥΣΤΟΥ</option>

                <option>4921-ΚΑΣΣΑΝΔΡΑΣ</option>

                <option>8421-ΚΑΣΤΕΛΙΟΥ ΚΙΣΣΑΜΟΥ</option>

                <option>8121-ΚΑΣΤΕΛΙΟΥ ΠΕΔΙΑΔΟΣ</option>

                <option>4311-ΚΑΣΤΟΡΙΑΣ</option>

                <option>4711-ΚΑΤΕΡΙΝΗΣ Α'</option>

                <option>4712-ΚΑΤΕΡΙΝΗΣ Β'</option>

                <option>1125-ΚΑΤΟΙΚΩΝ ΕΞΩΤΕΡΙΚΟΥ</option>

                <option>2333-ΚΑΤΩ ΑΧΑΙΑΣ</option>

                <option>4293-ΚΑΤΩ ΣΤΑΥΡΟΥ</option>

                <option>7131-ΚΕΑΣ</option>

                <option>9211-ΚΕΡΚΥΡΑΣ Α'</option>

                <option>9212-ΚΕΡΚΥΡΑΣ Β'</option>

                <option>1153-ΚΗΦΙΣΙΑΣ</option>

                <option>2512-ΚΙΑΤΟΥ</option>

                <option>4411-ΚΙΛΚΙΣ</option>

                <option>2322-ΚΛΕΙΤΟΡΙΑΣ</option>

                <option>4541-ΚΟΖΑΝΗ</option>

                <option>5511-ΚΟΜΟΤΗΝΗΣ</option>

                <option>6321-ΚΟΝΙΤΣΑΣ</option>

                <option>2513-ΚΟΡΙΝΘΟΥ</option>

                <option>1210-ΚΟΡΥΔΑΛΛΟΥ</option>

                <option>1304-ΚΟΡΩΠΙΟΥ</option>

                <option>2122-ΚΡΑΝΙΔΙΟΥ</option>

                <option>2421-ΚΡΑΣΤΕΝΩΝ</option>

                <option>2631-ΚΡΟΚΕΩΝ</option>

                <option>1305-ΚΥΘΗΡΩΝ</option>

                <option>1722-ΚΥΜΗΣ</option>

                <option>2742-ΚΥΠΑΡΙΣΣΙΑΣ</option>

                <option>7531-ΚΩ</option>

                <option>4222-ΛΑΓΚΑΔΑ</option>

                <option>2233-ΛΑΓΚΑΔΙΩΝ (Τ.Γ.)</option>

                <option>1832-ΛΑΜΙΑΣ</option>

                <option>3231-ΛΑΡΙΣΑΣ Α'</option>

                <option>3232-ΛΑΡΙΣΑΣ Β'</option>

                <option>3233-ΛΑΡΙΣΑΣ Γ'</option>

                <option>1306-ΛΑΥΡΙΟΥ</option>

                <option>2232-ΛΕΒΙΔΙΟΥ (Τ.Γ.)</option>

                <option>7512-ΛΕΡΟΥ</option>

                <option>9421-ΛΕΥΚΑΔΑΣ</option>

                <option>2422-ΛΕΧΑΙΝΩΝ</option>

                <option>2213-ΛΕΩΝΙΔΙΟΥ</option>

                <option>7211-ΛΗΜΝΟΥ</option>

                <option>9321-ΛΗΞΟΥΡΙΟΥ</option>

                <option>1421-ΛΙΒΑΔΕΙΑΣ</option>

                <option>1911-ΛΙΔΟΡΙΚΙΟΥ</option>

                <option>8115-ΛΙΜΕΝΑ ΧΕΡΣΟΝΗΣΟΥ</option>

                <option>1731-ΛΙΜΝΗΣ</option>

                <option>4790-ΛΙΤΟΧΩΡΙΟΥ</option>

                <option>1831-ΜΑΚΡΑΚΩΜΗΣ</option>

                <option>2241-ΜΕΓΑΛΟΠΟΛΗΣ</option>

                <option>1308-ΜΕΓΑΡΩΝ</option>

                <option>2721-ΜΕΛΙΓΑΛΑ</option>

                <option>1531-ΜΕΣΟΛΟΓΓΙΟΥ</option>

                <option>2722-ΜΕΣΣΗΝΗΣ</option>

                <option>6315-ΜΕΤΣΟΒΟΥ</option>

                <option>7222-ΜΗΘΥΜΝΑΣ</option>

                <option>7141-ΜΗΛΟΥ</option>

                <option>8112-ΜΟΙΡΩΝ</option>

                <option>2621-ΜΟΛΑΩΝ</option>

                <option>1211-ΜΟΣΧΑΤΟΥ</option>

                <option>3112-ΜΟΥΖΑΚΙΟΥ</option>

                <option>7172-ΜΥΚΟΝΟΥ</option>

                <option>7231-ΜΥΤΙΛΗΝΗΣ</option>

                <option>7151-ΝΑΞΟΥ</option>

                <option>4121-ΝΑΟΥΣΑΣ</option>

                <option>1541-ΝΑΥΠΑΚΤΟΥ</option>

                <option>2131-ΝΑΥΠΛΙΟΥ</option>

                <option>2622-ΝΕΑΠΟΛΗ ΒΟΙΩΝ ΛΑΚΩΝΙΑΣ</option>

                <option>4511-ΝΕΑΠΟΛΗΣ ΒΟΙΟΥ ΚΟΖΑΝΗΣ</option>

                <option>4225-ΝΕΑΠΟΛΗΣ ΘΕΣ/ΝΙΚΗΣ</option>

                <option>8231-ΝΕΑΠΟΛΗΣ ΚΡΗΤΗΣ</option>

                <option>5641-ΝΕΑΣ ΖΙΧΝΗΣ</option>

                <option>1131-ΝΕΑΣ ΙΩΝΙΑΣ</option>

                <option>1132-ΝΕΑΣ ΣΜΥΡΝΗΣ</option>

                <option>1155-ΝΕΑΣ ΦΙΛΑΔΕΛΦΕΙΑΣ</option>

                <option>2514-ΝΕΜΕΑΣ</option>

                <option>4312-ΝΕΣΤΟΡΙΟΥ</option>

                <option>5112-ΝΕΥΡΟΚΟΠΙΟΥ</option>

                <option>4923-ΝΕΩΝ ΜΟΥΔΑΝΙΩΝ</option>

                <option>5611-ΝΙΓΡΙΤΑΣ</option>

                <option>1220-ΝΙΚΑΙΑΣ</option>

                <option>5411-ΞΑΝΘΗΣ Α'</option>

                <option>5412-ΞΑΝΘΗΣ Β'</option>

                <option>2515-ΞΥΛΟΚΑΣΤΡΟΥ</option>

                <option>5231-ΟΡΕΣΤΕΙΑΔΑΣ</option>

                <option>1133-ΠΑΛΑΙΟΥ ΦΑΛΗΡΟΥ</option>

                <option>3114-ΠΑΛΑΜΑ (Τ.Γ.)</option>

                <option>1312-ΠΑΛΛΗΝΗΣ</option>

                <option>9221-ΠΑΞΩΝ</option>

                <option>2221-ΠΑΡΑΛΙΟΥ ΑΣΤΡΟΥΣ</option>

                <option>6231-ΠΑΡΑΜΥΘΙΑΣ</option>

                <option>6221-ΠΑΡΓΑΣ</option>

                <option>7161-ΠΑΡΟΥ</option>

                <option>2331-ΠΑΤΡΩΝ Α'</option>

                <option>2332-ΠΑΤΡΩΝ Β'</option>

                <option>2334-ΠΑΤΡΩΝ Γ'</option>

                <option>1201-ΠΕΙΡΑΙΑ Α'</option>

                <option>1203-ΠΕΙΡΑΙΑ Γ'</option>

                <option>1204-ΠΕΙΡΑΙΑ Δ'</option>

                <option>1205-ΠΕΙΡΑΙΑ Ε'</option>

                <option>1207-ΠΕΙΡΑΙΑ ΠΛΟΙΩΝ</option>

                <option>1209-ΠΕΙΡΑΙΑ ΣΤ'</option>

                <option>1206-ΠΕΙΡΑΙΑ ΦΑΕ</option>

                <option>8342-ΠΕΡΑΜΑΤΟΣ (Τ.Γ.)</option>

                <option>1138-ΠΕΡΙΣΤΕΡΙΟΥ Α'</option>

                <option>1157-ΠΕΡΙΣΤΕΡΙΟΥ Β'</option>

                <option>1178-ΠΕΤΡΟΥΠΟΛΗΣ</option>

                <option>1192-ΠΛΗΡΩΜΩΝ ΑΘΗΝΩΝ</option>

                <option>4292-ΠΛΗΡΩΜΩΝ ΘΕΣ/ΝΙΚΗΣ</option>

                <option>1292-ΠΛΗΡΩΜΩΝ ΠΕΙΡΑΙΑ</option>

                <option>7241-ΠΛΩΜΑΡΙΟΥ</option>

                <option>4922-ΠΟΛΥΓΥΡΟΥ</option>

                <option>7290-ΠΟΛΥΧΝΙΤΟΥ(Τ.Γ.)</option>

                <option>1310-ΠΟΡΟΥ</option>

                <option>6314-ΠΡΑΜΑΝΤΩΝ (Τ.Γ.)</option>

                <option>6411-ΠΡΕΒΕΖΑΣ</option>

                <option>4531-ΠΤΟΛΕΜΑΙΔΑΣ</option>

                <option>3490-ΠΥΛΗΣ (Τ.Γ.)</option>

                <option>2731-ΠΥΛΟΥ</option>

                <option>2412-ΠΥΡΓΟΥ</option>

                <option>8341-ΡΕΘΥΜΝΟΥ</option>

                <option>7542-ΡΟΔΟΥ</option>

                <option>1309-ΣΑΛΑΜΙΝΑΣ</option>

                <option>7322-ΣΑΜΟΥ</option>

                <option>5521-ΣΑΠΠΩΝ</option>

                <option>4542-ΣΕΡΒΙΩΝ</option>

                <option>5621-ΣΕΡΡΩΝ Α'</option>

                <option>5622-ΣΕΡΡΩΝ Β'</option>

                <option>8241-ΣΗΤΕΙΑΣ</option>

                <option>4543-ΣΙΑΤΙΣΤΑΣ</option>

                <option>5631-ΣΙΔΗΡΟΚΑΣΤΡΟΥ</option>

                <option>2630-ΣΚΑΛΑΣ ΛΑΚΩΝΙΑΣ</option>

                <option>3332-ΣΚΙΑΘΟΥ</option>

                <option>3331-ΣΚΟΠΕΛΟΥ</option>

                <option>4641-ΣΚΥΔΡΑΣ</option>

                <option>5241-ΣΟΥΦΛΙΟΥ</option>

                <option>3113-ΣΟΦΑΔΩΝ</option>

                <option>2632-ΣΠΑΡΤΗΣ</option>

                <option>2121-ΣΠΕΤΣΩΝ</option>

                <option>1833-ΣΤΥΛΙΔΑΣ</option>

                <option>7171-ΣΥΡΟΥ</option>

                <option>4223-ΣΩΧΟΥ</option>

                <option>7181-ΤΗΝΟΥ</option>

                <option>4226-ΤΟΥΜΠΑΣ</option>

                <option>3412-ΤΡΙΚΑΛΩΝ</option>

                <option>2231-ΤΡΙΠΟΛΗΣ</option>

                <option>2214-ΤΡΟΠΑΙΩΝ</option>

                <option>8114-ΤΥΜΠΑΚΙΟΥ</option>

                <option>3241-ΤΥΡΝΑΒΟΥ</option>

                <option>1311-ΥΔΡΑΣ</option>

                <option>6222-ΦΑΝΑΡΙΟΥ</option>

                <option>3491-ΦΑΡΚΑΔΩΝΑΣ (Τ.Γ.)</option>

                <option>3251-ΦΑΡΣΑΛΩΝ</option>

                <option>2743-ΦΙΛΙΑΤΡΩΝ ΜΕΣΣΗΝΙΑΣ</option>

                <option>6241-ΦΙΛΙΑΤΩΝ</option>

                <option>6113-ΦΙΛΙΠΠΙΑΔΑΣ</option>

                <option>4812-ΦΛΩΡΙΝΑΣ</option>

                <option>1156-ΧΑΙΔΑΡΙΟΥ</option>

                <option>1134-ΧΑΛΑΝΔΡΙΟΥ</option>

                <option>1732-ΧΑΛΚΙΔΑΣ</option>

                <option>8431-ΧΑΝΙΩΝ Α'</option>

                <option>8432-ΧΑΝΙΩΝ Β'</option>

                <option>7411-ΧΙΟΥ</option>

                <option>1176-ΧΟΛΑΡΓΟΥ</option>

                <option>5331-ΧΡΥΣΟΥΠΟΛΗΣ</option>

                <option>1175-ΨΥΧΙΚΟΥ</option>

            </select>
            <small class="text-danger"></small>
        </div>
        <div class="form-controls">
        <label for="ika">Υποκατάστημα ΙΚΑ <span class="text-danger">*</span></label>
            <select name="ika" style="width:520px">
                <option selected="selected" hidden value="null">--Επιλέξτε--</option>

                <option>531-25ΗΣ ΜΑΡΤΙΟΥ</option>

                <option>317-ΑΓ. ΜΗΝΑ</option>

                <option>136-ΑΓΙΑΣ ΑΝΝΑΣ ΠΑΡΑΡΤΗΜΑ</option>

                <option>614-ΑΓΙΑΣ ΠΑΡΑΡΤΗΜΑ</option>

                <option>065-ΑΓΙΑΣ ΠΑΡΑΣΚΕΥΗΣ</option>

                <option>057-ΑΓΙΑΣ ΣΟΦΙΑΣ</option>

                <option>131-ΑΓΙΟΥ ΑΛΕΞΙΟΥ</option>

                <option>004-ΑΓΙΟΥ ΙΕΡΟΘΕΟΥ</option>

                <option>219-ΑΓΙΟΥ ΚΗΡΥΚΟΥ ΠΑΡΑΡΤΗΜΑ</option>

                <option>074-ΑΓΙΟΥ ΚΩΝ/ΝΟΥ ΠΑΡΑΡΤΗΜΑ</option>

                <option>305-ΑΓΙΟΥ ΝΙΚΟΛΑΟΥ</option>

                <option>009-ΑΓΙΟΥ ΣΤΕΦΑΝΟΥ</option>

                <option>083-ΑΓΙΩΝ ΑΝΑΡΓΥΡΩΝ</option>

                <option>610-ΑΓΡΙΑΣ ΠΑΡΑΡΤΗΜΑ</option>

                <option>024-ΑΓΡΙΝΙΟΥ</option>

                <option>001-ΑΘΗΝΩΝ</option>

                <option>056-ΑΙΓΑΛΕΩ</option>

                <option>034-ΑΙΓΙΝΑΣ ΠΑΡΑΡΤΗΜΑ</option>

                <option>561-ΑΙΓΙΝΙΟΥ ΠΑΡΑΡΤΗΜΑ</option>

                <option>103-ΑΙΓΙΟΥ</option>

                <option>066-ΑΙΔΗΨΟΥ ΠΑΡΑΡΤΗΜΑ</option>

                <option>146-ΑΚΡΑΤΑΣ ΠΑΡΑΡΤΗΜΑ</option>

                <option>041-ΑΛΕΞΑΝΔΡΑΣ</option>

                <option>525-ΑΛΕΞΑΝΔΡΕΙΑΣ</option>

                <option>701-ΑΛΕΞΑΝΔΡΟΥΠΟΛΗΣ</option>

                <option>036-ΑΛΙΑΡΤΟΥ</option>

                <option>030-ΑΛΙΒΕΡΙΟΥ</option>

                <option>605-ΑΛΜΥΡΟΥ</option>

                <option>113-ΑΜΑΛΙΑΔΑΣ</option>

                <option>064-ΑΜΑΡΟΥΣΙΟΥ</option>

                <option>526-ΑΜΥΝΤΑΙΟΥ</option>

                <option>011-ΑΜΦΙΑΛΗΣ</option>

                <option>012-ΑΜΦΙΘΕΑΣ</option>

                <option>147-ΑΜΦΙΚΛΕΙΑΣ ΠΑΡΑΡΤΗΜΑ</option>

                <option>070-ΑΜΦΙΛΟΧΙΑΣ ΠΑΡΑΡΤΗΜΑ</option>

                <option>043-ΑΜΦΙΣΣΑΣ</option>

                <option>624-ΑΝΑΤΟΛΙΚΗΣ ΛΑΡΙΣΑΣ</option>

                <option>088-ΑΝΔΡΟΥ ΠΑΡΑΡΤΗΜΑ</option>

                <option>909-ΑΝΩ ΛΕΥΚΙΜΜΗΣ ΠΑΡΑΡΤΗΜΑ</option>

                <option>132-ΑΝΩ ΠΟΛΗΣ ΠΑΤΡΩΝ</option>

                <option>544-ΑΞΙΟΥΠΟΛΗΣ</option>

                <option>903-ΑΡΓΟΣΤΟΛΙΟΥ</option>

                <option>106-ΑΡΓΟΥΣ</option>

                <option>565-ΑΡΓΟΥΣ ΟΡΕΣΤΙΚΟΥ ΠΑΡΑΡΤΗΜΑ</option>

                <option>543-ΑΡΙΔΑΙΑΣ ΠΑΡΑΡΤΗΜΑ</option>

                <option>311-ΑΡΚΑΛΟΧΩΡΙΟΥ ΠΑΡΑΡΤΗΜΑ</option>

                <option>545-ΑΡΝΑΙΑΣ</option>

                <option>803-ΑΡΤΑΣ</option>

                <option>405-ΑΡΧΑΓΓΕΛΟΥ ΠΑΡΑΡΤΗΜΑ</option>

                <option>145-ΑΡΧΑΙΑΣ ΟΛΥΜΠΙΑΣ ΠΑΡΑΡΤΗΜΑ</option>

                <option>053-ΑΣΠΡΟΠΥΡΓΟΥ</option>

                <option>028-ΑΣΤΑΚΟΥ ΠΑΡΑΡΤΗΜΑ</option>

                <option>130-ΑΣΤΡΟΥΣ ΠΑΡΑΡΤΗΜΑ</option>

                <option>051-ΑΤΑΛΑΝΤΗΣ</option>

                <option>095-ΑΧΑΡΝΩΝ</option>

                <option>140-ΒΑΡΘΟΛΟΜΙΟΥ ΠΑΡΑΡΤΗΜΑ</option>

                <option>142-ΒΑΡΚΙΖΑΣ ΠΑΡΑΡΤΗΜΑ</option>

                <option>547-ΒΑΣΙΛΙΚΩΝ ΠΑΡΑΡΤΗΜΑ</option>

                <option>619-ΒΕΛΕΣΤΙΝΟΥ ΠΑΡΑΡΤΗΜΑ</option>

                <option>511-ΒΕΡΟΙΑΣ</option>

                <option>316-ΒΙΑΝΝΟΥ ΠΑΡΑΡΤΗΜΑ</option>

                <option>601-ΒΟΛΟΥ</option>

                <option>907-ΒΟΝΙΤΣΑΣ ΠΑΡΑΡΤΗΜΑ</option>

                <option>006-ΒΥΡΩΝΑ</option>

                <option>537-ΓΑΛΑΤΙΣΤΑΣ ΠΑΡΑΡΤΗΜΑ</option>

                <option>096-ΓΑΛΑΤΣΙΟΥ</option>

                <option>121-ΓΑΡΓΑΛΙΑΝΩΝ ΠΑΡΑΡΤΗΜΑ</option>

                <option>129-ΓΑΣΤΟΥΝΗΣ ΠΑΡΑΡΤΗΜΑ</option>

                <option>538-ΓΕΡΑΚΙΝΗΣ ΠΑΡΑΡΤΗΜΑ</option>

                <option>535-ΓΙΑΝΝΙΤΣΩΝ</option>

                <option>084-ΓΛΥΦΑΔΑΣ</option>

                <option>551-ΓΟΥΜΕΝΙΣΣΑΣ ΠΑΡΑΡΤΗΜΑ</option>

                <option>090-ΓΡΑΒΙΑΣ ΠΑΡΑΡΤΗΜΑ</option>

                <option>541-ΓΡΕΒΕΝΩΝ</option>

                <option>117-ΓΥΘΕΙΟΥ ΠΑΡΑΡΤΗΜΑ</option>

                <option>097-ΔΑΦΝΗΣ</option>

                <option>134-ΔΕΡΒΕΝΙΟΥ ΠΑΡΑΡΤΗΜΑ</option>

                <option>562-ΔΕΣΚΑΤΗΣ ΠΑΡΑΡΤΗΜΑ</option>

                <option>706-ΔΙΔΥΜΟΤΕΙΧΟΥ</option>

                <option>087-ΔΙΣΤΟΜΟΥ ΠΑΡΑΡΤΗΜΑ</option>

                <option>038-ΔΟΜΟΚΟΥ ΠΑΡΑΡΤΗΜΑ</option>

                <option>514-ΔΡΑΜΑΣ</option>

                <option>048-ΔΡΑΠΕΤΣΩΝΑΣ</option>

                <option>513-ΕΔΕΣΣΑΣ</option>

                <option>606-ΕΛΑΣΣΟΝΑΣ ΠΑΡΑΡΤΗΜΑ</option>

                <option>523-ΕΛΕΥΘΕΡΟΥΠΟΛΗΣ ΠΑΡΑΡΤΗΜΑ</option>

                <option>027-ΕΛΕΥΣΙΝΑΣ</option>

                <option>201-ΕΡΜΟΥΠΟΛΗΣ ΣΥΡΟΥ</option>

                <option>220-ΕΥΔΗΛΟΥ (ΙΚΑΡΙΑΣ) ΠΑΡΑΡΤΗΜΑ</option>

                <option>504-ΕΥΟΣΜΟΥ</option>

                <option>623-ΖΑΓΟΡΑΣ ΠΑΡΑΡΤΗΜΑ</option>

                <option>902-ΖΑΚΥΝΘΟΥ</option>

                <option>122-ΖΑΧΑΡΩΣ ΠΑΡΑΡΤΗΜΑ</option>

                <option>063-ΖΩΓΡΑΦΟΥ</option>

                <option>906-ΗΓΟΥΜΕΝΙΤΣΑΣ</option>

                <option>002-ΗΛΙΟΥΠΟΛΗΣ</option>

                <option>546-ΗΡΑΚΛΕΙΑΣ ΠΑΡΑΡΤΗΜΑ</option>

                <option>302-ΗΡΑΚΛΕΙΟΥ</option>

                <option>013-ΗΡΑΚΛΕΙΟΥ ΑΤΤΙΚΗΣ</option>

                <option>501-ΘΕΣΣΑΛΟΝΙΚΗΣ</option>

                <option>037-ΘΗΒΑΣ</option>

                <option>212-ΘΗΡΑΣ ΠΑΡΑΡΤΗΜΑ</option>

                <option>304-ΙΕΡΑΠΕΤΡΑΣ</option>

                <option>908-ΙΘΑΚΗΣ ΠΑΡΑΡΤΗΜΑ</option>

                <option>005-ΙΛΙΟΥ</option>

                <option>077-ΙΣΤΙΑΙΑΣ</option>

                <option>050-ΙΤΕΑΣ ΠΑΡΑΡΤΗΜΑ</option>

                <option>801-ΙΩΑΝΝΙΝΩΝ</option>

                <option>505-ΙΩΝΙΑΣ ΘΕΣ/ΝΙΚΗΣ</option>

                <option>510-ΚΑΒΑΛΑΣ</option>

                <option>148-ΚΑΛΑΒΡΥΤΩΝ ΠΑΡΑΡΤΗΜΑ</option>

                <option>503-ΚΑΛΑΜΑΡΙΑΣ</option>

                <option>102-ΚΑΛΑΜΑΤΑΣ</option>

                <option>608-ΚΑΛΑΜΠΑΚΑΣ</option>

                <option>044-ΚΑΛΛΙΘΕΑΣ</option>

                <option>217-ΚΑΛΛΟΝΗΣ ΠΑΡΑΡΤΗΜΑ</option>

                <option>402-ΚΑΛΥΜΝΟΥ</option>

                <option>093-ΚΑΜΑΤΕΡΟΥ</option>

                <option>315-ΚΑΜΙΝΙΩΝ ΚΡΗΤΗΣ</option>

                <option>059-ΚΑΜΙΝΙΩΝ ΠΕΙΡΑΙΑ</option>

                <option>604-ΚΑΡΔΙΤΣΑΣ</option>

                <option>206-ΚΑΡΛΟΒΑΣΙΟΥ ΠΑΡΑΡΤΗΜΑ</option>

                <option>406-ΚΑΡΠΑΘΟΥ ΠΑΡΑΡΤΗΜΑ</option>

                <option>029-ΚΑΡΠΕΝΗΣΙΟΥ</option>

                <option>075-ΚΑΡΥΣΤΟΥ ΠΑΡΑΡΤΗΜΑ</option>

                <option>552-ΚΑΣΣΑΝΔΡΑΣ ΠΑΡΑΡΤΗΜΑ</option>

                <option>517-ΚΑΣΤΟΡΙΑΣ</option>

                <option>518-ΚΑΤΕΡΙΝΗΣ</option>

                <option>127-ΚΑΤΩ ΑΧΑΪΑΣ ΠΑΡΑΡΤΗΜΑ</option>

                <option>709-ΚΑΤΩ ΝΕΥΡΟΚΟΠΙΟΥ ΠΑΡΑΡΤΗΜΑ</option>

                <option>089-ΚΑΤΩ ΤΙΘΟΡΕΑΣ ΠΑΡΑΡΤΗΜΑ</option>

                <option>039-ΚΕΡΑΜΕΙΚΟΥ</option>

                <option>080-ΚΕΡΑΤΕΑΣ ΠΑΡΑΡΤΗΜΑ</option>

                <option>901-ΚΕΡΚΥΡΑΣ</option>

                <option>020-ΚΗΦΙΣΙΑΣ</option>

                <option>116-ΚΙΑΤΟΥ</option>

                <option>519-ΚΙΛΚΙΣ</option>

                <option>308-ΚΙΣΣΑΜΟΥ ΠΑΡΑΡΤΗΜΑ</option>

                <option>520-ΚΟΖΑΝΗΣ</option>

                <option>702-ΚΟΜΟΤΗΝΗΣ</option>

                <option>805-ΚΟΝΙΤΣΑΣ ΠΑΡΑΡΤΗΜΑ</option>

                <option>107-ΚΟΡΙΝΘΟΥ</option>

                <option>015-ΚΟΡΥΔΑΛΟΥ</option>

                <option>076-ΚΟΡΩΠΙΟΥ</option>

                <option>549-ΚΟΥΦΑΛΙΩΝ ΠΑΡΑΡΤΗΜΑ</option>

                <option>133-ΚΡΑΝΙΔΙΟΥ</option>

                <option>123-ΚΡΕΣΤΕΝΩΝ ΠΑΡΑΡΤΗΜΑ</option>

                <option>566-ΚΡΥΑΣ ΒΡΥΣΗΣ ΠΑΡΑΡΤΗΜΑ</option>

                <option>032-ΚΥΜΗΣ</option>

                <option>119-ΚΥΠΑΡΙΣΣΙΑΣ</option>

                <option>403-ΚΩ</option>

                <option>522-ΛΑΓΚΑΔΑ</option>

                <option>023-ΛΑΜΙΑΣ</option>

                <option>602-ΛΑΡΙΣΑΣ</option>

                <option>078-ΛΑΡΥΜΝΑΣ ΠΑΡΑΡΤΗΜΑ</option>

                <option>021-ΛΑΥΡΙΟΥ</option>

                <option>026-ΛΕΙΒΑΔΙΑΣ</option>

                <option>404-ΛΕΡΟΥ</option>

                <option>905-ΛΕΥΚΑΔΑΣ</option>

                <option>128-ΛΕΧΑΙΝΩΝ ΠΑΡΑΡΤΗΜΑ</option>

                <option>135-ΛΕΩΝΙΔΙΟΥ ΠΑΡΑΡΤΗΜΑ</option>

                <option>904-ΛΗΞΟΥΡΙΟΥ ΠΑΡΑΡΤΗΜΑ</option>

                <option>528-ΛΙΜΕΝΑ ΘΑΣΟΥ ΠΑΡΑΡΤΗΜΑ</option>

                <option>312-ΛΙΜΕΝΑ ΧΕΡΣΟΝΗΣΟΥ</option>

                <option>529-ΛΙΜΕΝΑΡΙΩΝ ΘΑΣΟΥ ΠΑΡΑΡΤΗΜΑ</option>

                <option>031-ΛΙΜΝΗΣ ΠΑΡΑΡΤΗΜΑ</option>

                <option>542-ΛΙΤΟΧΩΡΟΥ ΠΑΡΑΡΤΗΜΑ</option>

                <option>118-ΛΟΥΤΡΑΚΙΟΥ ΠΑΡΑΡΤΗΜΑ</option>

                <option>079-ΜΑΚΡΑΚΩΜΗΣ ΠΑΡΑΡΤΗΜΑ</option>

                <option>052-ΜΑΝΤΟΥΔΙΟΥ</option>

                <option>086-ΜΑΡΚΟΠΟΥΛΟΥ ΠΑΡΑΡΤΗΜΑ</option>

                <option>110-ΜΕΓΑΛΟΠΟΛΗΣ</option>

                <option>035-ΜΕΓΑΡΩΝ</option>

                <option>125-ΜΕΛΙΓΑΛΑ ΠΑΡΑΡΤΗΜΑ</option>

                <option>025-ΜΕΣΟΛΟΓΓΙΟΥ</option>

                <option>115-ΜΕΣΣΗΝΗΣ ΠΑΡΑΡΤΗΜΑ</option>

                <option>809-ΜΕΤΣΟΒΟΥ ΠΑΡΑΡΤΗΜΑ</option>

                <option>215-ΜΗΘΥΜΝΑΣ ΠΑΡΑΡΤΗΜΑ</option>

                <option>205-ΜΗΛΟΥ</option>

                <option>309-ΜΟΙΡΩΝ ΠΑΡΑΡΤΗΜΑ</option>

                <option>137-ΜΟΛΑΩΝ ΠΑΡΑΡΤΗΜΑ</option>

                <option>098-ΜΟΣΧΑΤΟΥ</option>

                <option>613-ΜΟΥΖΑΚΙΟΥ ΠΑΡΑΡΤΗΜΑ</option>

                <option>216-ΜΥΚΟΝΟΥ ΠΑΡΑΡΤΗΜΑ</option>

                <option>207-ΜΥΡΙΝΑΣ ΛΗΜΝΟΥ</option>

                <option>203-ΜΥΤΙΛΗΝΗΣ</option>

                <option>209-ΝΑΞΟΥ</option>

                <option>512-ΝΑΟΥΣΑΣ</option>

                <option>112-ΝΑΥΠΑΚΤΟΥ</option>

                <option>111-ΝΑΥΠΛΙΟΥ</option>

                <option>556-ΝΕΑΠΟΛΗΣ ΘΕΣ/ΝΙΚΗΣ</option>

                <option>307-ΝΕΑΠΟΛΗΣ ΚΡΗΤΗΣ ΠΑΡΑΡΤΗΜΑ</option>

                <option>139-ΝΕΑΠΟΛΗΣ ΛΑΚΩΝΙΑΣ ΠΑΡΑΡΤΗΜΑ</option>

                <option>617-ΝΕΑΣ ΑΓΧΙΑΛΟΥ ΠΑΡΑΡΤΗΜΑ</option>

                <option>314-ΝΕΑΣ ΑΛΙΚΑΡΝΑΣΣΟΥ</option>

                <option>563-ΝΕΑΣ ΖΙΧΝΗΣ ΠΑΡΑΡΤΗΜΑ</option>

                <option>054-ΝΕΑΣ ΙΩΝΙΑΣ</option>

                <option>621-ΝΕΑΣ ΙΩΝΙΑΣ ΒΟΛΟΥ ΠΑΡΑΡΤΗΜΑ</option>

                <option>559-ΝΕΑΣ ΚΑΛΛΙΚΡΑΤΕΙΑΣ ΠΑΡΑΡΤΗΜΑ</option>

                <option>534-ΝΕΑΣ ΜΗΧΑΝΙΩΝΑΣ ΠΑΡΑΡΤΗΜΑ</option>

                <option>564-ΝΕΑΣ ΣΑΝΤΑΣ ΠΑΡΑΡΤΗΜΑ</option>

                <option>073-ΝΕΑΣ ΦΙΛΑΔΕΛΦΕΙΑΣ</option>

                <option>143-ΝΕΜΕΑΣ ΠΑΡΑΡΤΗΜΑ</option>

                <option>046-ΝΕΟΥ ΚΟΣΜΟΥ</option>

                <option>536-ΝΕΩΝ ΜΟΥΔΑΝΙΩΝ</option>

                <option>539-ΝΙΓΡΙΤΑΣ ΠΑΡΑΡΤΗΜΑ</option>

                <option>058-ΝΙΚΑΙΑΣ</option>

                <option>703-ΞΑΝΘΗΣ</option>

                <option>126-ΞΥΛΟΚΑΣΤΡΟΥ ΠΑΡΑΡΤΗΜΑ</option>

                <option>085-ΟΙΝΟΦΥΤΩΝ</option>

                <option>705-ΟΡΕΣΤΙΑΔΑΣ</option>

                <option>092-ΟΡΧΟΜΕΝΟΥ ΠΑΡΑΡΤΗΜΑ</option>

                <option>042-ΠΑΓΚΡΑΤΙΟΥ</option>

                <option>616-ΠΑΛΑΜΑ ΠΑΡΑΡΤΗΜΑ</option>

                <option>210-ΠΑΠΠΑΔΟΥ ΠΑΡΑΡΤΗΜΑ</option>

                <option>072-ΠΑΡΑΛΙΑΣ ΔΙΣΤΟΜΟΥ</option>

                <option>804-ΠΑΡΑΜΥΘΙΑΣ ΠΑΡΑΡΤΗΜΑ</option>

                <option>807-ΠΑΡΓΑΣ ΠΑΡΑΡΤΗΜΑ</option>

                <option>218-ΠΑΡΟΥ ΠΑΡΑΡΤΗΜΑ</option>

                <option>040-ΠΑΤΗΣΙΩΝ</option>

                <option>407-ΠΑΤΜΟΥ ΠΑΡΑΡΤΗΜΑ</option>

                <option>101-ΠΑΤΡΑΣ</option>

                <option>010-ΠΕΙΡΑΙΑ</option>

                <option>071-ΠΕΡΑΜΑΤΟΣ</option>

                <option>313-ΠΕΡΑΜΑΤΟΣ ΡΕΘΥΜΝΟΥ ΠΑΡΑΡΤΗΜΑ</option>

                <option>045-ΠΕΡΙΣΤΕΡΙΟΥ</option>

                <option>019-ΠΕΤΡΟΥΠΟΛΗΣ</option>

                <option>061-ΠΛ. ΑΤΤΙΚΗΣ</option>

                <option>062-ΠΛ. ΟΜΟΝΟΙΑΣ</option>

                <option>060-ΠΛ. ΣΥΝΤΑΓΜΑΤΟΣ</option>

                <option>208-ΠΛΩΜΑΡΙΟΥ ΠΑΡΑΡΤΗΜΑ</option>

                <option>211-ΠΟΛΙΧΝΙΤΟΥ ΠΑΡΑΡΤΗΜΑ</option>

                <option>530-ΠΟΛΥΓΥΡΟΥ</option>

                <option>557-ΠΟΛΥΚΑΣΤΡΟΥ ΠΑΡΑΡΤΗΜΑ</option>

                <option>911-ΠΟΡΟΥ ΚΕΦΑΛΛΗΝΙΑΣ ΠΑΡΑΡΤΗΜΑ</option>

                <option>068-ΠΟΡΟΥ ΠΑΡΑΡΤΗΜΑ</option>

                <option>802-ΠΡΕΒΕΖΑΣ</option>

                <option>527-ΠΤΟΛΕΜΑΪΔΑΣ</option>

                <option>533-ΠΥΛΗΣ ΑΞΙΟΥ</option>

                <option>618-ΠΥΛΗΣ ΤΡΙΚΑΛΩΝ ΠΑΡΑΡΤΗΜΑ</option>

                <option>114-ΠΥΛΟΥ ΠΑΡΑΡΤΗΜΑ</option>

                <option>104-ΠΥΡΓΟΥ</option>

                <option>082-ΡΑΦΗΝΑΣ</option>

                <option>303-ΡΕΘΥΜΝΟΥ</option>

                <option>144-ΡΙΟΥ ΠΑΤΡΩΝ ΠΑΡΑΡΤΗΜΑ</option>

                <option>401-ΡΟΔΟΥ</option>

                <option>055-ΣΑΛΑΜΙΝΑΣ</option>

                <option>912-ΣΑΜΗΣ ΠΑΡΑΡΤΗΜΑ</option>

                <option>204-ΣΑΜΟΥ</option>

                <option>707-ΣΑΠΩΝ ΠΑΡΑΡΤΗΜΑ</option>

                <option>554-ΣΕΡΒΙΩΝ ΠΑΡΑΡΤΗΜΑ</option>

                <option>213-ΣΕΡΙΦΟΥ ΠΑΡΑΡΤΗΜΑ</option>

                <option>515-ΣΕΡΡΩΝ</option>

                <option>306-ΣΗΤΕΙΑΣ</option>

                <option>548-ΣΙΑΤΙΣΤΑΣ</option>

                <option>540-ΣΙΔΗΡΟΚΑΣΤΡΟΥ</option>

                <option>138-ΣΚΑΛΑΣ ΛΑΚΩΝΙΑΣ ΠΑΡΑΡΤΗΜΑ</option>

                <option>081-ΣΚΑΛΑΣ ΩΡΩΠΟΥ ΠΑΡΑΡΤΗΜΑ</option>

                <option>615-ΣΚΙΑΘΟΥ ΠΑΡΑΡΤΗΜΑ</option>

                <option>620-ΣΚΟΠΕΛΟΥ ΠΑΡΑΡΤΗΜΑ</option>

                <option>553-ΣΚΥΔΡΑΣ ΠΑΡΑΡΤΗΜΑ</option>

                <option>704-ΣΟΥΦΛΙΟΥ</option>

                <option>612-ΣΟΦΑΔΩΝ ΠΑΡΑΡΤΗΜΑ</option>

                <option>108-ΣΠΑΡΤΗΣ</option>

                <option>094-ΣΠΑΤΩΝ ΠΑΡΑΡΤΗΜΑ</option>

                <option>067-ΣΠΕΤΣΩΝ ΠΑΡΑΡΤΗΜΑ</option>

                <option>560-ΣΤΑΥΡΟΥ ΠΑΡΑΡΤΗΜΑ</option>

                <option>506-ΣΤΑΥΡΟΥΠΟΛΗΣ</option>

                <option>521-ΣΤΡΑΤΩΝΙΟΥ</option>

                <option>049-ΣΤΥΛΙΔΑΣ</option>

                <option>214-ΤΗΝΟΥ ΠΑΡΑΡΤΗΜΑ</option>

                <option>502-ΤΟΥΜΠΑΣ</option>

                <option>603-ΤΡΙΚΑΛΩΝ</option>

                <option>105-ΤΡΙΠΟΛΗΣ</option>

                <option>109-ΤΡΟΠΑΙΩΝ ΠΑΡΑΡΤΗΜΑ</option>

                <option>555-ΤΣΟΤΥΛΙΟΥ ΠΑΡΑΡΤΗΜΑ</option>

                <option>310-ΤΥΜΠΑΚΙΟΥ ΠΑΡΑΡΤΗΜΑ</option>

                <option>607-ΤΥΡΝΑΒΟΥ ΠΑΡΑΡΤΗΜΑ</option>

                <option>622-ΦΑΡΚΑΔΟΝΑΣ ΠΑΡΑΡΤΗΜΑ</option>

                <option>611-ΦΑΡΣΑΛΩΝ ΠΑΡΑΡΤΗΜΑ</option>

                <option>708-ΦΕΡΩΝ ΠΑΡΑΡΤΗΜΑ</option>

                <option>120-ΦΙΛΙΑΤΡΩΝ ΠΑΡΑΡΤΗΜΑ</option>

                <option>806-ΦΙΛΙΑΤΩΝ ΠΑΡΑΡΤΗΜΑ</option>

                <option>808-ΦΙΛΙΠΠΙΑΔΑΣ ΠΑΡΑΡΤΗΜΑ</option>

                <option>516-ΦΛΩΡΙΝΑΣ</option>

                <option>141-ΦΟΥΡΝΑΣ ΠΑΡΑΡΤΗΜΑ</option>

                <option>003-ΧΑΪΔΑΡΙΟΥ</option>

                <option>047-ΧΑΛΑΝΔΡΙΟΥ</option>

                <option>550-ΧΑΛΚΗΔΟΝΑΣ ΠΑΡΑΡΤΗΜΑ</option>

                <option>022-ΧΑΛΚΙΔΑΣ</option>

                <option>301-ΧΑΝΙΩΝ</option>

                <option>202-ΧΙΟΥ</option>

                <option>008-ΧΟΛΑΡΓΟΥ</option>

                <option>524-ΧΡΥΣΟΥΠΟΛΗΣ ΠΑΡΑΡΤΗΜΑ</option>

            </select>
            <small class="text-danger"></small>
        </div>
    </div>
    <div class="form-controls">
        <button type="submit" name="submit" style="background-color: dodgerblue;" class="btn btn-light">ΕΓΓΡΑΦΗ</button>
    </div>
    </form>
</div>



<!-- ################################################################################################ -->
<!-- ################################################################################################ -->
<!-- ################################################################################################ -->
<!--<div  id="foot" class="wrapper row5">
    <div id="copyright" class="hoc clear">

        <p class="fl_left">Copyright &copy; 2021 - All Rights Reserved - <a style="background-color: inherit" href="index.php">www.ypakp.gr </a></p>

    </div>
</div> -->
<!-- ################################################################################################ -->
<!-- ################################################################################################ -->
<!-- ################################################################################################ -->
<a style="background-color: royalblue;" id="backtotop" href="#top"><i class="fas fa-chevron-up"></i></a>
<!-- JAVASCRIPTS -->
<script src="../scripts/jquery.min.js"></script>
<script src="../scripts/jquery.backtotop.js"></script>
<script src="../scripts/jquery.mobilemenu.js"></script>
<script src="../scripts/signup.js"></script>
<script src="../scripts/login_popup.js"></script>
</body>
</html>
