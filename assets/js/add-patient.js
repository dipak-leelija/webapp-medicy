// check mail validity

const checkMail = (t) =>{

    let email = t.value;

    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if(emailRegex.test(email)){
        document.getElementById('patientPhoneNumber').focus();
    }else{
        t.value = '';
        Swal.fire('Alert','Enter valid email id.','info');
    }
}


//// check inputed mobile length validity
const checkMobNo = (t) =>{
    if(t.value.length > 9){
        Swal.fire('Alert','Enter Maximum 10 digit','error');
        t.value = '';
    }
}

const checkContactNo = (t) =>{
    if(t.value.length < 9){
        Swal.fire('Error','Mobile number must be 10 digit','error');
        t.value = '';
    }
}

// patient weight validity
const checkWeight = (t) =>{
    if(t.value.length > 3){
        Swal.fire('Error','Enter valid weight','error');
        t.value = '';
    }
}

// patient age validity
const checkAge = (t) =>{
    if(t.value.length > 3){
        Swal.fire('Error','Enter valid age','error');
        t.value = '';
    }
}


// pin validity check
const checkPin = (t) =>{
    if(t.value.length > 6){
        Swal.fire('Error','Enter valid PIN number(maximum 6 digit).','error');
        t.value = '';
    }
}