var popupOpen_log = document.getElementsByClassName('login popup-trigger')[0];
var popup_log = document.getElementsByClassName('login popup')[0];
var popupClose_log = document.getElementsByClassName('login popup-close')[0];
var userName_log = document.getElementsByName('login_name')[0];
var password_log = document.getElementsByName('login_pwd')[0];
var form_log = document.getElementsByClassName('login popup-box')[0];
var submit_log = false;


function check(element, str){
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


if(popupOpen_log !== undefined && popup_log !== undefined) {

    form_log.addEventListener('submit',(e) =>{
        submit_log = true;
        let flag = false;
        if(!check(userName_log,"Εισάγετε το όνομα χρήστη") )
            flag = true;
        if(!check(password_log,"Εισάγετε τον κωδικό σας"))
            flag = true;
        if(flag)
            e.preventDefault();

    });


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


    popupOpen_log.addEventListener('click', () =>{
        popup_log.classList.add('popup-active');
        if (history.pushState) {
            var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?login=true';
            window.history.pushState({path:newurl},'',newurl);
        }


    });

    popup_log.addEventListener('click', () =>{
        popup_log.classList.remove('popup-active');
        if (history.pushState) {
            var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname;
            window.history.pushState({path:newurl},'',newurl);
        }
    });

    popup_log.children[0].addEventListener('click', e => e.stopImmediatePropagation());

    popupClose_log.addEventListener('click', () => {
        popup_log.classList.remove('popup-active')
        if (history.pushState) {
            var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname;
            window.history.pushState({path:newurl},'',newurl);
        }
    });

    window.addEventListener('load', ()=>{
        if(window.location.search.includes("?login=true"))
            popup_log.classList.add('popup-active');
    });


}




