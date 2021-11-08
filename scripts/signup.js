var form = document.getElementById('form');
var id = document.getElementsByName('afm')[0];
var amka = document.getElementsByName('amka')[0];
var userName = document.getElementsByName('uname')[0];
var firstName = document.getElementsByName('fname')[0];
var lastName = document.getElementsByName('lname')[0];
var password = document.getElementsByName('pwd')[0];
var passwordCurr = document.getElementsByName('curr_pwd')[0];
var passwordConf = document.getElementsByName('pwdConf')[0];
var email = document.getElementsByName('email')[0];
var phone = document.getElementsByName('phone')[0];
var category = document.getElementsByName('category');
var compId = document.getElementsByName('compAfm')[0];
var compName = document.getElementsByName('compName')[0];
var compEmail = document.getElementsByName('compEmail')[0];
var compPhone = document.getElementsByName('compPhone')[0];
var doi = document.getElementsByName('doi')[0];
var ika = document.getElementsByName('ika')[0];
var options = document.getElementsByTagName('option');
var isEmployer;


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



function checkdigitsAmka(amka) {
    if(amka.length != 11)
        return false;

    function digits_of(arr){
        let digits = [];
        for (let i = 0; i < arr.length; i++)
            digits.push(parseInt(arr[i]));
        return digits;
    }

    let digits = digits_of(amka);
    odd_digits = [];
    even_digits = [];
    for(let i = 0; i < digits.length ; i+=2)
        odd_digits.push(digits[i]);
    for(let i = 1; i < digits.length ; i+=2)
        even_digits.push(digits[i]);

    checksum = 0;
    checksum += odd_digits.reduce((a, b) => a + b ,0)
    for(let i = 0; i < even_digits.length; i++)
        if(even_digits[i]*2 >= 10)
            checksum += 1 + (even_digits[i]*2)%10;
        else
            checksum += even_digits[i]*2;
    return checksum % 10 == 0;

}


function checkAmka(element,str) {

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
        if(!checkdigitsAmka(element.value)){
            element.classList.add('error');
            formControl.getElementsByClassName('fa-exclamation-circle')[0].classList.add('disp');
            warning.innerText = "* " + "Ο Αριθμός Μητρώου Κοινωνικής Ασφάλισης  δεν είναι έγκυρος";
            return false;
        }
        else {
            element.classList.add('success');
            formControl.getElementsByClassName('fa-check-circle')[0].classList.add('disp');
            return true;
        }
    }

}


function checkdigits(afm) {
    let afmLen = 9;
    if (afm.length != afmLen) {
        return false;
    }
    let total = 0;
    let check = -1;
    for (let i = afmLen - 1; i >= 0; i--) {
        let c = afm.charAt(i);
        if (!/^\d+$/.test(c)) {
            return false;
        }
        let digit = c - '0';
        if (i == afmLen - 1) {
            check = digit;
            continue;
        }
        total += digit << (afmLen - i - 1);
    }
    return check == total % 11 % 10;

}

function checkAfm(element,str) {

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
        if(!checkdigits(element.value)){
            element.classList.add('error');
            formControl.getElementsByClassName('fa-exclamation-circle')[0].classList.add('disp');
            warning.innerText = "* " + "Ο Αριθμός Φορολογικού Μητρώου δεν είναι έγκυρος";
            return false;
        }
        else {
            element.classList.add('success');
            formControl.getElementsByClassName('fa-check-circle')[0].classList.add('disp');
            return true;
        }
    }

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

function checkPwd(element){

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
        warning.innerText = "* Εισάγετε έναν κωδικό πρόσβασης";
        return false;

    }
    else{
        if(element.value.length >= 8) {
            element.classList.add('success');
            formControl.getElementsByClassName('fa-check-circle')[0].classList.add('disp');
            return true;
        }
        else{
            element.classList.add('error');
            formControl.getElementsByClassName('fa-exclamation-circle')[0].classList.add('disp');
            warning.innerText = "* Χρησιμοποιήστε τουλάχιστον 8 χαρακτήρες για τον κωδικό πρόσβασής σας";
            return false;
        }
    }
}

function verifyPwd(pwd1,pwd2){

    const formControl = pwd1.parentElement;
    const warning = formControl.getElementsByTagName('small')[0];

    pwd1.classList.remove('error');
    pwd1.classList.remove('success');
    formControl.getElementsByClassName('fa-exclamation-circle')[0].classList.remove('disp');
    formControl.getElementsByClassName('fa-check-circle')[0].classList.remove('disp');
    warning.innerText = "";
    warning.classList.add('disp');


    if(pwd1.value == ""){
        pwd1.classList.add('error');
        formControl.getElementsByClassName('fa-exclamation-circle')[0].classList.add('disp');
        warning.innerText = "* Επιβεβαιώστε τον κωδικό πρόσβασης";
        return false;

    }
    else{
        if(pwd1.value == pwd2.value) {
            pwd1.classList.add('success');
            formControl.getElementsByClassName('fa-check-circle')[0].classList.add('disp');
            return true;
        }
        else{
            pwd1.classList.add('error');
            formControl.getElementsByClassName('fa-exclamation-circle')[0].classList.add('disp');
            warning.innerText = "* Οι κωδικοί δεν ταιριάζουν";
            return false;
        }
    }
}


function checkEmail(element,str){

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
        if(/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/.test(element.value)) {
            element.classList.add('success');
            formControl.getElementsByClassName('fa-check-circle')[0].classList.add('disp');
            return true;
        }
        else{
            element.classList.add('error');
            formControl.getElementsByClassName('fa-exclamation-circle')[0].classList.add('disp');
            warning.innerText = "* Μη έγκυρη διεύθυνση e-mail";
            return false;
        }
    }
}

function checkRadio( radioNodeList , str){
    const warning = document.getElementById("radioWarning");
    let flag = false;
    let retValue = false;
    warning.innerText = "";
    warning.classList.add('disp');

    radioNodeList.forEach((radio)=>{
        if(radio.checked) {
            flag = true;
            if(radio.value == str)
                retValue = true;
        }
    });

    if(!flag)
        warning.innerText = "* Επιλέξτε μια κατηγορία";

    return retValue;
}

function checkPhone(element){
    const formControl = element.parentElement;
    const warning = formControl.getElementsByTagName('small')[0];

    element.classList.remove('error');
    element.classList.remove('success');
    formControl.getElementsByClassName('fa-exclamation-circle')[0].classList.remove('disp');
    formControl.getElementsByClassName('fa-check-circle')[0].classList.remove('disp');
    warning.innerText = "";
    warning.classList.add('disp');



    if(/^\d+$/.test(element.value) && element.value.length == 10) {
            element.classList.add('success');
            formControl.getElementsByClassName('fa-check-circle')[0].classList.add('disp');
            return true;
    }
    else if (element.value.length > 0 ){
            element.classList.add('error');
            formControl.getElementsByClassName('fa-exclamation-circle')[0].classList.add('disp');
            warning.innerText = "* Μη έγκυρος αριθμός τηλεφώνου";
            return false;
    }
    else
        return true;

}

if(id)
    id.addEventListener('blur',() => {
        checkAfm(id,'Εισάγετε τον Αριθμό Φορολογικού Μητρώου σας');
    });

if(amka)
    amka.addEventListener('blur',() => {
        checkAmka(amka,'Εισάγετε τον Αριθμό Μητρώου Κοινωνικής Ασφάλισης σας');
    });

if(userName)
    userName.addEventListener('blur',() => {
        checkInput(userName,'Εισάγετε ένα όνομα χρήστη για το προφίλ σας');
    });

if(firstName)
    firstName.addEventListener('blur',() => {
        checkInput(firstName,'Εισάγετε το όνομά σας');
    });

if(lastName)
    lastName.addEventListener('blur',() => {
        checkInput(lastName,'Εισάγετε το επώνυμό σας');
    });

if(passwordCurr){
    passwordCurr.addEventListener('blur',() => {
        checkInput(passwordCurr,'Εισάγετε τον κωδικό σας');
    });
}

if(password)
    password.addEventListener('blur',() => {
        checkPwd(password);
        if(passwordConf.value != "")
            verifyPwd(passwordConf,password);
    });

if(passwordConf)
    passwordConf.addEventListener('blur',() => {
        if(password.value.length >= 8)
            verifyPwd(passwordConf,password);
    });

if(email)
    email.addEventListener('blur',() => {
        checkEmail(email,'Εισάγετε το e-mail σας');
    });

if(phone)
    phone.addEventListener('blur',() => {
        checkPhone(phone);
    });



if(category.length)
    category.forEach((radio)=>{

        radio.addEventListener("change",()=>{
            const warning = document.getElementById("radioWarning");
            if(radio.checked == true){
                warning.innerText = "";
                warning.classList.remove('disp');
            }

            if(radio.value == "employer")
                document.getElementsByClassName('form-extras')[0].classList.add('disp-extras');
            else
                document.getElementsByClassName('form-extras')[0].classList.remove('disp-extras');

        });

    })

if(options.length)
    for(let i = 0; i < options.length; i++)
        if(options[i].value != "null")
            options[i].value = options[i].innerText;

if(doi){
    doi.addEventListener("change",()=>{
        checkSelect(doi,'Επιλέξτε την Δημόσια Οικονομική Υπηρεσία της εταιρείας σας');
    });

}
if(ika){
    ika.addEventListener("change",()=>{
        checkSelect(ika,'Επιλέξτε το υποκατάστημα ΙΚΑ της εταιρείας σας');
    });

}



if(compId)
    compId.addEventListener('blur',() => {
        checkAfm(compId,'Εισάγετε τον Αριθμό Φορολογικού Μητρώου της εταιρείας σας');
    });

if(compName)
    compName.addEventListener('blur',() => {
        checkInput(compName,'Εισάγετε το όνομα της εταιρείας σας');
    });

if(compEmail)
    compEmail.addEventListener('blur',() => {
        checkEmail(compEmail,'Εισάγετε το email της εταιρείας σας');
    });

if(compPhone)
    compPhone.addEventListener('blur',() => {
        checkPhone(compPhone);
    });


if(form != null)
    form.addEventListener('submit',(e) =>{
        let flag = false;


        if(id && !checkAfm(id,'Εισάγετε τον Αριθμό Φορολογικού Μητρώου σας'))
           flag = true;
        if(amka && !checkAmka(amka,'Εισάγετε τον Αριθμό Μητρώου Κοινωνικής Ασφάλισης σας'))
         flag = true;

        if(userName && !checkInput(userName,'Εισάγετε ένα όνομα χρήστη για το προφίλ σας'))
            flag = true;
        if(firstName && !checkInput(firstName,'Εισάγετε το όνομά σας'))
            flag = true;
        if(lastName && !checkInput(lastName,'Εισάγετε το επώνυμό σας'))
            flag = true;
        if(passwordCurr && !checkInput(passwordCurr,"Εισάγετε τον κωδικό σας"))
            flag = true;
        if(password && !checkPwd(password))
            flag = true;
        if(passwordConf && password.value.length >= 8)
            if(!verifyPwd(passwordConf,password))
                flag = true;
        if(email && !checkEmail(email,'Εισάγετε το e-mail σας'))
            flag = true;
        if(phone && !checkPhone(phone))
            flag =true;

        if(category.length)
            isEmployer = checkRadio(category,"employer");

        if(compId && !checkAfm(compId,'Εισάγετε τον Αριθμό Φορολογικού Μητρώου της εταιρείας σας'))
           flag = true;
        if(compName && !checkInput(compName,'Εισάγετε το όνομα της εταιρείας σας') && isEmployer)
            flag = true;
        if(compEmail && !checkEmail(compEmail,'Εισάγετε το email της εταιρείας σας') && isEmployer)
            flag = true;
        if(compPhone && !checkPhone(compPhone) && isEmployer)
            flag = true;
        if(doi && !checkSelect(doi,'Επιλέξτε την Δημόσια Οικονομική Υπηρεσία της εταιρείας σας') && isEmployer)
            flag = true;
        if(ika && !checkSelect(ika,'Επιλέξτε το υποκατάστημα ΙΚΑ της εταιρείας σας') && isEmployer)
            flag = true;

        if(flag)
            e.preventDefault();

    });







