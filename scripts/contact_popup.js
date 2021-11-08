var popup_cont = document.getElementsByClassName('contact popup')[0];
var popupClose_cont = document.getElementsByClassName('contact popup-close')[0];
var firstName = document.getElementsByName('fname')[0];
var lastName = document.getElementsByName('lname')[0];
var email = document.getElementsByName('email')[0];
var phone = document.getElementsByName('phone')[0];
var form_cont = document.getElementsByClassName('contact popup-box')[0];
var date = document.getElementById('date');
var dateN = document.getElementById('dateName');
var submit_cont = false;


function contactOpen (params, name) {
    popup_cont.classList.add('popup-active');
    var urlParams = new URLSearchParams(params);
    dateN.innerText = name;
    date.innerText = urlParams.get('date');
    var newparams = params.replace(" ","&time=")

    if (history.pushState) {
        var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?' + newparams;
        window.history.pushState({path:newurl},'',newurl);
    }
};


function checkEmail(element,str){
    const formControl = element.parentElement;
    const warning = formControl.getElementsByTagName('small')[0];

    element.classList.remove('error');
    formControl.getElementsByClassName('fa-exclamation-circle')[0].classList.remove('disp');
    warning.classList.remove('disp');


    if(element.value == ""){
        element.classList.add('error');
        formControl.getElementsByClassName('fa-exclamation-circle')[0].classList.add('disp');
        warning.innerText = "* " + str;
        warning.classList.add('disp');
        return false;

    }
    else{
        if(/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/.test(element.value)) {
            return true;
        }
        else{
            element.classList.add('error');
            formControl.getElementsByClassName('fa-exclamation-circle')[0].classList.add('disp');
            warning.innerText = "* Μη έγκυρη διεύθυνση e-mail";
            warning.classList.add('disp');
            return false;
        }
    }
}

function checkPhone(element){
    const formControl = element.parentElement;
    const warning = formControl.getElementsByTagName('small')[0];

    element.classList.remove('error');
    formControl.getElementsByClassName('fa-exclamation-circle')[0].classList.remove('disp');
    warning.classList.remove('disp');



    if(/^\d+$/.test(element.value) && element.value.length == 10)
       return true;
    else if (element.value.length > 0 ){
        element.classList.add('error');
        formControl.getElementsByClassName('fa-exclamation-circle')[0].classList.add('disp');
        warning.innerText = "* Μη έγκυρος αριθμός τηλεφώνου";
        warning.classList.add('disp');
        return false;
    }
    else
        return true;

}


function checkInput(element, str){
    const formControl = element.parentElement;
    const warning = formControl.getElementsByTagName('small')[0];

    element.classList.remove('error');
    formControl.getElementsByClassName('fa-exclamation-circle')[0].classList.remove('disp');
    warning.classList.remove('disp');



    if(element.value == ""){
        element.classList.add('error');
        formControl.getElementsByClassName('fa-exclamation-circle')[0].classList.add('disp');
        warning.innerText = "* " + str;
        warning.classList.add('disp');
        return false;

    }
    else
        return true;

}

popup_cont.addEventListener('click', () =>{
    popup_cont.classList.remove('popup-active');
    if (history.pushState) {
        var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname;
        window.history.back();
    }
});

popup_cont.children[0].addEventListener('click', e => e.stopImmediatePropagation());

popupClose_cont.addEventListener('click', () => {
    popup_cont.classList.remove('popup-active');
    if (history.pushState) {
        var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname;
        window.history.back();
    }
});



form_cont.addEventListener('submit',(e) =>{
    submit_cont = true;
    let flag = false;
    if(!checkInput(firstName,"Εισάγετε το όνομα σας"))
        flag = true;
    if(!checkInput(lastName,"Εισάγετε το επώνυμο σας"))
        flag = true;
    if(!checkEmail(email,"Εισάγετε το email σας"))
        flag = true;
    if(!checkPhone(phone))
        flag = true;

    if(flag)
        e.preventDefault();

});




/*


if(popupOpen_log !== undefined && popup_log !== undefined) {




    userName_log.addEventListener('input', () =>{
        if(userName_log != "" && submit_log){
            const formControl = userName_log.parentElement;
            const warning = formControl.getElementsByTagName('small')[0];
            userName_log.classList.remove('error');
            formControl.getElementsByClassName('fa-exclamation-circle')[0].classList.remove('disp');

        }

        if(submit_log)
            check(userName_log,"Εισάγετε το όνομα χρήστη")


    });

    password_log.addEventListener('input', () =>{
        if(password_log != "" && submit_log){
            const formControl = password_log.parentElement;
            const warning = formControl.getElementsByTagName('small')[0];
            password_log.classList.remove('error');
            formControl.getElementsByClassName('fa-exclamation-circle')[0].classList.remove('disp');
        }

        if(submit_log)
            check(password_log,"Εισάγετε τον κωδικό σας")

    });





}



*/
