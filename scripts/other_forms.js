var cal_icons = document.getElementsByClassName('fa-calendar');

function checkDate(element, str){
    const formControl = element.parentElement;
    const warning = formControl.getElementsByTagName('small')[0];

    element.classList.remove('error');
    element.classList.remove('success');
    warning.innerText = "";
    warning.classList.add('disp');


    if(element.value == ""){
        element.classList.add('error');
        warning.innerText = "* " + str;
        return false;

    }
    else{
        element.classList.add('success');
        return true;
    }
}



function checkSelect(element, str){
    const formControl = element.parentElement;
    const warning = formControl.getElementsByTagName('small')[0];

    warning.innerText = "";
    warning.classList.add('disp');


    if(element.value == "null"){
        warning.innerText = "* " + str;
        return false;

    }
    else
        return true;

}

function checkInput(element, str){
    const formControl = element.parentElement;
    const warning = formControl.getElementsByTagName('small')[0];

    element.classList.remove('error');
    element.classList.remove('success');
    formControl.getElementsByClassName('fa-exclamation-circle')[0].classList.remove('disp');
    formControl.getElementsByClassName('fa-check-circle')[0].classList.remove('disp');
    warning.innerText = "";
    warning.classList.add('disp');


    if(element.value == ""){
        element.classList.add('error');
        formControl.getElementsByClassName('fa-exclamation-circle')[0].classList.add('disp');
        warning.innerText = "* " + str;
        return false;

    }
    else{
        element.classList.add('success');
        formControl.getElementsByClassName('fa-check-circle')[0].classList.add('disp');
        return true;
    }

}

function checkRadio( radioNodeList , str){
    const warning = document.getElementById("radioWarning");
    let flag = false;
    warning.innerText = "";
    warning.classList.add('disp');

    radioNodeList.forEach((radio)=>{
        if(radio.checked)
            flag = true;
    });

    if(!flag)
        warning.innerText = "* " + str;

    return flag;

}




$.datepicker.regional['gr'] = {
    monthNames: ["Ιανουάριος", "Φεβρουάριος", "Μάρτιος", "Απρίλιος", "Μάιος", "Ιούνιος", "Ιούλιος",
        "Αύγουστος", "Σεπτέμβριος", "Οκτώβριος", "Νοέμβριος", "Δεκέμβριος"],
    monthNamesShort: ["Ιαν","Φεβ", "Μάρ", "Απρ","Μάι","Ιούν","Ιούλ","Αύγ", "Σεπ","Οκτ","Νοέ", "Δεκ"],
    dayNames: ['Δευτέρα','Τρίτη','Τετάρτη','Πέμπτη','Παρασκευή','Σάββατο','Κυριακή'],
    dayNamesMin: ["Δε","Τρ","Τε","Πε","Πα", "Σα","Κυ"]};
$.datepicker.setDefaults($.datepicker.regional['gr']);


if(cal_icons)
    for(let i = 0; i < cal_icons.length; i++)
        cal_icons[i].addEventListener('click',()=>{
            const formControl = cal_icons[i].parentElement;
            const input = formControl.getElementsByTagName('input')[0];
            input.focus();
        });

