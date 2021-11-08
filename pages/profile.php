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


$company = "";

if($user['employeeOf']) {
    $compId = $user['employeeOf'];
    $query = "select * from Company where id = '$compId' ";
    $result = mysqli_query($conn, $query);
    if (!$result) die($conn->error);
    $row = mysqli_fetch_assoc($result);
    $company.=$row['name'];
}
else{
    $afm = $user['id'];
    $query = "select * from Company where employer_id = '$afm'";
    $result = mysqli_query($conn, $query);
    if (!$result) die($conn->error);
    $rows = $result->num_rows;
    for($i = 0; $i < $rows-1; $i++) {
        $row = mysqli_fetch_assoc($result);
        $company .= $row['name'] . ',';
    }

    $row = mysqli_fetch_assoc($result);
    $company .= $row['name'];
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

        <a href="../index.php" class="prev_button">&laquo; Αρχική</a>
        <!-- ################################################################################################ -->
    </header>

</div>


<div style="background-color:dodgerblue;margin: 0 auto; width: 100%; " class="container menu_content wrapper hoc">
    <div   class="main-body">
        <div class="row gutters-sm">
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-column align-items-center text-center">
                            <div id="prof_card" class="mt-3">
                                <h3 ><b><?php echo $user['username'] ?></b></h3>
                                <p style="font-size:large"><?php if($user['employeeOf'])echo "Εργαζόμενος";else echo "Εργοδότης";?></p>
                                <?php if (!$user['employeeOf']) echo '<a href="new_company.php" style="display:block;width: max-content;margin:15px auto 0px auto;" href="#" class="btn btn-info">ΠΡΟΣΘΗΚΗ ΕΤΑΙΡΕΙΑΣ</a>' ?>
                                <a style="display:block;width: max-content;margin:15px auto;" href="profile_update.php" class="btn btn-success">ΕΠΕΞΕΡΓΑΣΙΑ ΠΡΟΦΙΛ</a>
                                <a style="display:block;width: max-content; margin: 0 auto;" href="pwd_change.php" class="btn btn-primary">ΑΛΛΑΓΗ ΚΩΔΙΚΟΥ ΠΡΟΣΒΑΣΗΣ</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="display-normal" class="card mt-3">
                    <h3 style="text-align: center; position: absolute; top: 0" >Δραστηριότητες</h3>
                    <ul style="position: absolute; top: 20%" class="list-group list-group-flush">
                        <li>
                            <a  href="CONTACT/contact_history.php"><i class="fa fa-caret-right" aria-hidden="true"></i> Ραντεβού</a>
                        </li>
                        <?php
                            if($user['employeeOf'])
                                echo '<li>
                                    <a href="parental_history.php"><i class="fa fa-caret-right" aria-hidden="true"></i> Γονικές Άδειες</a></li>';
                        ?>
                        <li>
                            <a  href="contr_susp_history.php"><i class="fa fa-caret-right" aria-hidden="true"></i> Αναστολές Σύμβασης Εργασίας</a>
                        </li>
                        <li>
                            <a  href="telework_history.php"><i class="fa fa-caret-right" aria-hidden="true"></i> Δηλώσεις Εξ΄ Αποστάσεως Εργασίας</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-8">
                <div style="width: 80%;"  class="card mb-3">
                    <div  class="card-body">
                        <h3 style="text-align: center">Στοιχεία Χρήστη</h3>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <h5 class="mb-0"><b>Όνομα:</b></h5>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                <?php echo $user['firstname']; ?>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <h5 class="mb-0"><b>Επώνυμο:</b></h5>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                <?php echo $user['lastname'] ?>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <h5 class="mb-0"><b>E-mail:</b></h5>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                <?php echo $user['email'];?>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <h5 class="mb-0"><b>Τηλέφωνο:</b></h5>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                <?php echo $user['phone'];?>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <h5 class="mb-0"><b>ΑΦΜ:</b></h5>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                <?php echo $user['id'];?>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <h5 class="mb-0"><b>Εταιρεία<span style="text-transform: lowercase">/ες:</span></b></h5>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                <?php echo $company;?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div id="display-short" class="card mt-3">
                    <h3 style="text-align: center;padding-top: 20px" >Δραστηριότητες</h3>
                    <ul  class="list-group list-group-flush">
                        <li>
                            <a  href="CONTACT/contact_history.php"><i class="fa fa-caret-right" aria-hidden="true"></i> Ραντεβού</a>
                        </li>
                        <?php
                        if($user['employeeOf'])
                            echo '<li>
                                    <a href="parental_history.php"><i class="fa fa-caret-right" aria-hidden="true"></i> Γονικές Άδειες</a></li>';
                        ?>
                        <li>
                            <a  href="contr_susp_history.php"><i class="fa fa-caret-right" aria-hidden="true"></i> Αναστολές Σύμβασης Εργασίας</a>
                        </li>
                        <li>
                            <a  href="telework_history.php"><i class="fa fa-caret-right" aria-hidden="true"></i> Δηλώσεις Εξ΄ Αποστάσεως Εργασίας</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<?php

   if(isset($_SESSION["success"])) {
       echo '<div id="snackbar">Επιτυχής Ενημέρωση</div>
        <script>
            function trigger() {
              // Get the snackbar DIV
              var x = document.getElementById("snackbar");
            
              // Add the "show" class to DIV
              x.className = "show";
            
              // After 3 seconds, remove the show class from DIV
              setTimeout(function(){ x.className = x.className.replace("show", ""); }, 2500);
        
        }
        window.addEventListener("load",()=>{
                  trigger();
              });
        </script>';
       unset($_SESSION["success"]);
    }
?>

</body>
</html>
