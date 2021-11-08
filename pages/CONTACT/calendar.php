<?php



function create_calendar($times){

    session_start();

    require_once '../../dblogin.php';
    $conn = mysqli_connect($hn, $un, $pw, $db);
    if($conn->connect_error) die($conn->connect_error);
    $user = null;
    if(isset($_SESSION['name'])){
        $username = $_SESSION['name'];
        $query = "select * from User where username = '$username' ";
        $result = mysqli_query($conn, $query);
        if (!$result) die($conn->error);
        $user = mysqli_fetch_assoc($result);
    }



    $greek_days = array(
        "Monday" => "Δευτέρα",
        "Tuesday" => "Τρίτη",
        "Wednesday" => "Τετάρτη",
        "Thursday" => "Πέμπτη",
        "Friday" => "Παρασκευή",
        "Saturday" => "Σάββατο",
        "Sunday" => "Κυριακή");
    $greek_months = array(
        "January" => "Ιανουάριος",
        "February" => "Φεβρουάριος",
        "March" => "Μάρτιος",
        "April" => "Απρίλιος",
        "May" => "Μάιος",
        "June" => "Ιούνιος",
        "July" => "Ιούλιος",
        "August" => "Αύγουστος",
        "September" => "Σεπτέμβριος",
        "October" => "Οκτώβριος",
        "November" => "Νοέμβριος",
        "December" => "Δεκέμβριος"
    );

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


    $calendar="<table id='cal' class='calendar'><caption style='padding-left:150px;text-align: center'>";

    $startDate = "+". (7*$times). "day";
    $startDate = date('d-M-Y', strtotime($startDate));
    $endDate = "+". (7*$times) + 6 ."day";
    $endDate = date('d-M-Y', strtotime($endDate));
    $startDateMonth = $greek_months[date('F', strtotime($startDate))];
    $endDateMonth = $greek_months[date('F', strtotime($endDate))];
    $startDateYear = date('Y', strtotime($startDate));
    $endDateYear = date('Y', strtotime($endDate));

    $calendar.="<h3>";

    if($times)
        $calendar.="<a id='prev' class = 'fa fa-arrow-left'></a>";
    else
        $calendar.="<i  style='color: darkgrey' class = 'fa fa-arrow-left' ></i>";

    if(!strcmp($startDateMonth,$endDateMonth))
        $calendar.= "$startDateMonth $startDateYear";
    else
        $calendar.= "$startDateMonth $startDateYear - $endDateMonth $endDateYear ";



    if($times < 52)
        $calendar.="<a id='next' class = 'fa fa-arrow-right'></a>";
    else
        $calendar.="<i  style='color: darkgrey' class = 'fa fa-arrow-right' ></i>";

    $calendar.= "</h3></caption><tr>";

    $calendar.="<th style='background-color:white; color: black; border-left-color: white; border-right-color: white;border-top-color: white;'><div>Ημέρα /</div><div>Ώρα</div></th>";


    for($i = 0; $i < 7 ; $i++) {
        $day = "+". (7*$times)+ $i . "day";
        if(!strcmp(date('D', strtotime($day)) ,"Sat") ||  !strcmp(date('D', strtotime($day)) ,"Sun") )
            continue;
        $dayD = date('d', strtotime($day));
        $dayM = $greek_months_srt[date('M', strtotime($day))];
        $dayY = date('Y', strtotime($day));
        $calendar.="<th><div>". $greek_days[date('l', strtotime($day))]."</div>". $dayD."-".$dayM."-".$dayY ."</th>";
    }

    $calendar.="</tr>";


    $minutes = 0;


    for($k = 0; $k < 15 ; $k++) {
        $curr = date('H:i',mktime(8,$minutes,0));
        $next = date('H:i',mktime(8,$minutes + 20,0));
        $calendar .= "<tr><th class='vertical-cell'><div>".$curr."-".$next."</div></th>";

        for ($i = 0; $i < 7; $i++) {
            $day = "+" . (7 * $times) + $i . "day";
            if(!strcmp(date('D', strtotime($day)) ,"Sat") ||  !strcmp(date('D', strtotime($day)) ,"Sun") )
                continue;
            $day = date('d-M-Y', strtotime($day));
            $day = date('d-m-Y H:i:s', (8 * 60 + $minutes)*60 + strtotime($day));
            $dayName = date('l', (8 * 60 + $minutes)*60 + strtotime($day));

            $db_format = date('Y-m-d H:i:s', strtotime($day));
            $query = "select * from Appointment where date = '$db_format'";
            $result = mysqli_query($conn, $query);
            if (!$result) die($conn->error);
            $rows = $result->num_rows;

            if($rows == 0)
                $calendar .= "<td><a class='free' onclick='contactOpen(\""."date=". $day ."\",\"".$greek_days[$dayName]."\")'></a> </td>";
            else {
                if($user) {
                    $appointment = mysqli_fetch_assoc($result);
                    if ($user['id'] == $appointment['user_fk'] )
                        $calendar .= "<td><a class='user_closed'></a> </td>";
                    else
                        $calendar .= "<td><a class='closed'></a> </td>";
                }
                else
                    $calendar .= "<td><a class='closed'></a> </td>";

            }
            //$calendar .= "<td> <a href='#'>". $day." </a> </td>";
        }
        $minutes += 20;
        $calendar .= "</tr>";
    }

    echo $calendar."</table>";


}

if(isset($_GET['week'])){
    $times = $_GET['week'];
    if ($times < 0)
        $times = 0;
    else if ($times > 52)
        $times = 52;
    create_calendar($times);
}


?>
