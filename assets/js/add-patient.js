// check mail validity

const checkMail = (t) =>{

    let email = t.value;

    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if(emailRegex.test(email)){
        checkMailExistance(email);
    }else{
        t.value = '';
        Swal.fire('Alert','Enter valid email id.','info');
    }
}

// check mail existance
const checkMailExistance = (email) =>{
    let checkEmail = email;
    $.ajax({
        url: 'ajax/mobile-email-existance-check.ajax.php',
        type: 'POST',
        data: {
            email: checkEmail,
        },
        success: function (data) {
            if (data == 1) {
                // alert("email check"+data);
                Swal.fire('Alert','Email exist.','error');
                // document.getElementById('patientEmail').focus();
                // document.getElementById('patientEmail').value = '';
            } else {
                document.getElementById('patientPhoneNumber').focus();
            }
        },
    });
}



// mobile verification and validation =======
const checkContactNo = (t) => {
    if (t.value.length != 10) {
        Swal.fire('Alert', 'Mobile number must be 10 digits', 'error');
        t.value = '';
    } else {
        let contactNo = t.value;
        $.ajax({
            url: 'ajax/mobile-email-existance-check.ajax.php',
            type: 'POST',
            data: {
                checkMobNo: contactNo,
            },
            success: function (data) {
                // alert(data);
                if (data == 1) {
                    Swal.fire('Alert', 'Mobile number already exists.', 'error');
                } else {
                    document.getElementById("appointmentDate").focus();
                }
            },
        });
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