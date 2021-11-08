var companies = document.getElementsByName('comp')[0];
var employees = document.getElementsByName('empl')[0];
var form = document.getElementById('form');
var date1 = document.getElementsByName('startDate')[0];
var date2 = document.getElementsByName('endDate')[0];
var category = document.getElementsByName('category');


$(document).ready(function () {
    let company = companies.value;
    $.ajax({
        type: 'GET',
        url: '../../fetch_employees.php',
        data: 'comp='+ company,
        success: function (response) {
            $('select[name="empl"]').html(response);
        }
    });
});

companies.addEventListener('change',() =>{
    let company = companies.value;
    $.ajax({
        type: 'GET',
        url: '../../fetch_employees.php',
        data: 'comp='+ company,
        success: function (response) {
            $('select[name="empl"]').html(response);
        }
    });
});




$( function() {
    $("input[name = startDate]").datepicker({
        dateFormat: 'dd-mm-yy',
        minDate:0,
        onSelect: function(selected) {
            $("input[name = endDate]").datepicker("option","minDate", selected);
            checkDate(date1,'Επιλέξτε την ημερομηνία έναρξης');
        }
    });
    $("input[name = endDate]").datepicker({
        dateFormat: 'dd-mm-yy',
        minDate:0,
        onSelect: function(selected) {
            $("input[name = startDate]").datepicker("option","maxDate", selected)
            checkDate(date2,'Επιλέξτε την ημερομηνία λήξης');
        }
    });

});


if(category.length)
    category.forEach((radio)=>{

        radio.addEventListener("change",()=>{
            const warning = document.getElementById("radioWarning");
            if(radio.checked == true){
                warning.innerText = "";
                warning.classList.remove('disp');
            }
        });

    })



if(date1)
    date1.addEventListener('blur',() => {
        checkDate(date1,'Επιλέξτε την ημερομηνία έναρξης');
    });

if(date2)
    date2.addEventListener('blur',() => {
        checkDate(date2,'Επιλέξτε την ημερομηνία λήξης');
    });

if(employees){
    employees.addEventListener("change",()=>{
        checkSelect(employees,'Επιλέξτε έναν εργαζόμενο σας');
    });
}


if(form != null)
    form.addEventListener('submit',(e) =>{
        let flag = false;

        if(category.length && !checkRadio(category,'Επιλέξτε μια κατηγορία'))
            flag = true;
        if(employees && !checkSelect(employees,'Επιλέξτε έναν εργαζόμενο σας'))
            flag = true;
        if(date1 && !checkDate(date1,'Επιλέξτε την ημερομηνία έναρξης'))
            flag = true;
        if(date2 && !checkDate(date2,'Επιλέξτε την ημερομηνία λήξης'))
            flag = true;


        if(flag)
            e.preventDefault();

    });
