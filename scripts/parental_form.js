var form = document.getElementById('form');
var date1 = document.getElementsByName('startDate')[0];
var date2 = document.getElementsByName('endDate')[0];
var firstName = document.getElementsByName('ch-fname')[0];
var lastName = document.getElementsByName('ch-lname')[0];
var age = document.getElementsByName('ch-age')[0];
var reason = document.getElementsByName('reason')[0];
var options = document.getElementsByTagName('option');


$( function() {
    $("input[name = startDate]").datepicker({
        dateFormat: 'dd-mm-yy',
        minDate:0,
        onSelect: function(selected) {
            $("input[name = endDate]").datepicker("option","minDate", selected);
            checkDate(date1,'Επιλέξτε την ημερομηνία έναρξης της άδειας');
        }
    });
    $("input[name = endDate]").datepicker({
        dateFormat: 'dd-mm-yy',
        minDate:0,
        onSelect: function(selected) {
            $("input[name = startDate]").datepicker("option","maxDate", selected)
            checkDate(date2,'Επιλέξτε την ημερομηνία λήξης της άδειας');
        }
    });

});



if(options.length)
    for(let i = 0; i < options.length; i++)
        if(options[i].value != "null")
            options[i].value = options[i].innerText;

if(firstName)
    firstName.addEventListener('blur',() => {
        checkInput(firstName,'Εισάγετε το όνομά του παιδιού σας');
    });

if(lastName)
    lastName.addEventListener('blur',() => {
        checkInput(lastName,'Εισάγετε το επώνυμό του παιδιού σας');
    });

if(date1)
    date1.addEventListener('blur',() => {
        checkDate(date1,'Επιλέξτε την ημερομηνία έναρξης της άδειας');
    });

if(date2)
    date2.addEventListener('blur',() => {
        checkDate(date2,'Επιλέξτε την ημερομηνία λήξης της άδειας');
    });

if(age){
    age.addEventListener("change",()=>{
        checkSelect(age,'Επιλέξτε την ηλικία του παιδιού σας');
    });
}

if(reason){
    reason.addEventListener("change",()=>{
        checkSelect(reason,'Επιλέξτε τον λόγο της άδειας σας');
    });
}



if(form != null)
    form.addEventListener('submit',(e) =>{
        let flag = false;

        if(firstName && !checkInput(firstName,'Εισάγετε το όνομά του παιδιού σας'))
            flag = true;
        if(lastName && !checkInput(lastName,'Εισάγετε το επώνυμό του παιδιού σας'))
            flag = true;
        if(age && !checkSelect(age,'Επιλέξτε την ηλικία του παιδιού σας'))
            flag = true;
        if(reason && !checkSelect(reason,'Επιλέξτε τον λόγο της άδειας σας'))
            flag = true;
        if(date1 && !checkDate(date1,'Επιλέξτε την ημερομηνία έναρξης της άδειας'))
            flag = true;
        if(date2 && !checkDate(date2,'Επιλέξτε την ημερομηνία λήξης της άδειας'))
            flag = true;


        if(flag)
            e.preventDefault();

    });
