<?php


require_once '../dblogin.php';
$conn = mysqli_connect($hn, $un, $pw, $db);
if($conn->connect_error) die($conn->connect_error);

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



$limit = 3;

$query = "select * from News";
$result = mysqli_query($conn, $query);
if (!$result) die($conn->error);
$total = $result->num_rows;

if(!isset($_GET['page']))
    exit();

$page = $_GET['page'];

$start = ($page - 1) * $limit;
$query = "select * from News order by date DESC limit $start,$limit";
$result = mysqli_query($conn, $query);
if (!$result) die($conn->error);
$rows = $result->num_rows;




?>


<?php
    if($rows){
        $item = mysqli_fetch_assoc($result);
        $day = date('d-m-Y H:i:s', strtotime($item['date']));
        $dayName = $greek_days[date('l', strtotime($item['date']))];
        $dayD = date('d', strtotime($item['date']));
        $dayM = $greek_months_srt[date('M', strtotime($item['date']))];
        $dayY = date('Y', strtotime($item['date']));
        $hour = date('H:i:s', strtotime($item['date']));
        echo '<article class="one_third first"><img src="data:image/png;base64,' . base64_encode($item["img"]) . '"/>
                    <div class="excerpt">
                        <p style="text-align: right;margin-bottom: 25px;">'.$dayName.' '.$dayD.' '.$dayM.' '.$dayY.' '.$hour.'</p>
                        <a href="' . $item["link"] . '"><h4 class="heading">'.$item['title'].'</h4></a>
                        <p>' . $item["description"] . '</p>
                    </div>
                </article>';

        while ($item = mysqli_fetch_assoc($result)){
            $day = date('d-m-Y H:i:s', strtotime($item['date']));
            $dayName = $greek_days[date('l', strtotime($item['date']))];
            $dayD = date('d', strtotime($item['date']));
            $dayM = $greek_months_srt[date('M', strtotime($item['date']))];
            $dayY = date('Y', strtotime($item['date']));
            $hour = date('H:i:s', strtotime($item['date']));
            echo '<article class="one_third"><img src="data:image/png;base64,' . base64_encode($item["img"]) . '"/>
                <div class="excerpt">
                     <p style="text-align: right;margin-bottom: 25px;">'.$dayName.' '.$dayD.' '.$dayM.' '.$dayY.' '.$hour.'</p>
                    <a href="' . $item["link"] . '"><h4 class="heading">'.$item['title'].'</h4></a>
                    <p>' . $item["description"] . '</p>
                </div>
            </article>';
        }

    }
?>

<div class="pagination">
    <ul>
        <?php
            if($page > 1)
                echo '<li><button id="prev" class="btn btn-link" >&laquo; ΠΡΟΗΓΟΥΜΕΝΟ</button></li>';
            else
                echo '<li><button disabled class="btn btn-link" >&laquo; ΠΡΟΗΓΟΥΜΕΝΟ</button></li>';
        ?>  

        <?php
            for($i = 1; $i <= ceil($total/$limit); $i++ )
                if($i == $page)
                    echo '<li class="current"><strong>'.$i.'</strong></li>';
                else
                    echo '<li><button class="btn btn-link other-page">'.$i.'</button></li>';
        ?>
        <?php
        if($page < ceil($total/$limit))
            echo '<li><button id="next" class="btn btn-link">ΕΠΟΜΕΝΟ &raquo;</button></li>';
        else
            echo '<li><button disabled class="btn btn-link" >ΕΠΟΜΕΝΟ &raquo;</button></li>';
        ?>
    </ul>
</div>
