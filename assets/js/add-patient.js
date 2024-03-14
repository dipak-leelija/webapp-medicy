// check mail validity

const checkMail = (t) =>{

    let email = t.value;

    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if(emailRegex.test(email)){
        checkMailExistance(email);
    }else{
        Swal.fire({
            title: "Alert",
            text: "Enter valid email id.",
            icon: "warning",
            confirmButtonColor: "#3085d6",
            confirmButtonText: "Ok"
          }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('patientEmail').value = '';
                document.getElementById('patientEmail').focus();
            }
          });
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
                Swal.fire({
                    title: "Alert",
                    text: "Email exist.",
                    icon: "warning",
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Ok"
                  }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('patientEmail').value = '';
                        document.getElementById('patientEmail').focus();
                    }
                  });
            } else {
                document.getElementById('patientAddress1').focus();
            }
        },
    });
}



// mobile verification and validation =======

const checkContactNo = (t) => {
    if (t.value.length != 10) {
        Swal.fire({
            title: "Alert",
            text: "Mobile number must be 10 digits.",
            icon: "warning",
            confirmButtonColor: "#3085d6",
            confirmButtonText: "Ok"
          }).then((result) => {
            if (result.isConfirmed) {
                t.value='';
                document.getElementById('patientPhoneNumber').focus();
            }
          });
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
                    Swal.fire({
                        title: "Alert",
                        text: "Mobile number exist.",
                        icon: "warning",
                        confirmButtonColor: "#3085d6",
                        confirmButtonText: "Ok"
                      }).then((result) => {
                        if (result.isConfirmed) {
                            t.value='';
                            document.getElementById('patientPhoneNumber').focus();
                        }
                      });
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
    if(t.value.length != 6){
        Swal.fire('Error','Enter valid PIN number(maximum 6 digit).','error');
        t.value = '';
    }
}