let xmlhttp = new XMLHttpRequest();

// powerurl = 'ajax/product.getMedicineDetails.ajax.php?power=' + productId;
// // alert(url);
// xmlhttp.open("GET", powerurl, false);
// xmlhttp.send(null);
// // alert(xmlhttp.responseText);
// document.getElementById("medicine-power").value = xmlhttp.responseText;

// ======== code to chek mobile number input validity ===========
var mobileInput = document.getElementById('mobile-number');

const validateMobileNumber = () => {

    let mobileInput = document.getElementById('mobile-number');

    var inputValue = mobileInput.value;
    var numericValue = inputValue.replace(/[^0-9]/g, '');
    document.getElementById('mobile-number').value = numericValue;


    if (mobileInput.value.length != 10) {
        mobileInput.focus();
        console.log('input 10 digits');
    }
}



const verifyEmail = () => {
    var inputedMail = document.getElementById('email');
    // console.log(inputedMail);

    if (inputedMail.value) {
        let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!emailRegex.test(inputedMail.value)) {
            alert('Enter valid email id!');
            document.getElementById('email').value = ' ';
        } else {
            checkEmailAvailability();
        }
    }

}



const checkEmailAvailability = () => {
    let mailId = document.getElementById('email').value;

    $.ajax({
        url: "ajax/admin-mail-usrnm-existance-check.ajax.php",
        type: "POST",
        data: {
            chekEmailExistance: mailId,
        },
        success: function (data) {
            // console.log("ajax admin return data : " + data);
            if (data == '1') {
                alert('Email Exits as registered!');
                document.getElementById('email').value = ' ';
                // document.getElementById('email').focus();
                return 1;
            } else {
                return 0;
            }
        }
    });
}




const verifyUsername = (t) => {
    let admUsrnm = document.getElementById("user-name").value;
    console.log(t.value);

    $.ajax({
        url: "ajax/admin-mail-usrnm-existance-check.ajax.php",
        type: "POST",
        data: {
            chekUsrnmExistance: admUsrnm,
        },
        success: function (data) {
            // console.log("ajax return data : " + data);
            if (data == 1) {
                alert('Username Exits as registered!');
                document.getElementById('user-name').value = ' ';
            }
        }
    });
}



// === otp submit move next ===

const moveNext = (input) => {
    let inputedValue = input.value;
    let inputLength = input.value.length;

    if (inputedValue == ' ') {
        inputLength = 0;
        inputedValue = null;
    }

    if (inputLength === parseInt(input.getAttribute('maxlength')) && input.nextElementSibling) {
        input.nextElementSibling.focus();
    }
}


// ============ otp submit button action ===============
const submitOtp = () => {

    let digit1 = document.getElementById('digit1').value;
    let digit2 = document.getElementById('digit2').value;
    let digit3 = document.getElementById('digit3').value;
    let digit4 = document.getElementById('digit4').value;
    let digit5 = document.getElementById('digit5').value;
    let digit6 = document.getElementById('digit6').value;

    var submittedOtp = (digit1 + digit2 + digit3 + digit4 + digit5 + digit6);

    console.log(submittedOtp);


    $.ajax({
        url: "ajax/registrationOnOtpSubmission.ajax.php",
        type: "POST",
        data: {
            otpsubmit: submittedOtp,
        },
        success: function (data) {
            console.log("ajax return data : " + data);
            if (data == 1) {
                handleRegistrationSuccess();
            } else if (data == 2) {
                handleFailure();
            } else {
                handleRegistrationFailure(data);
            }
        }
    });
}






function handleRegistrationSuccess() {

    Swal.fire({
        icon: "success",
        title: "Registration Successful",
        showConfirmButton: true,
        confirmButtonColor: "#3085d6",
        confirmButtonText: "OK"
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "login.php";
        }
    });
}




function handleRegistrationFailure($message) {

    Swal.fire({
        icon: "error",
        title: "'.$message.'",
        showConfirmButton: true,
        confirmButtonColor: "#3085d6",
        confirmButtonText: "OK"
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "login.php";
        }
    });

}


function handleFailure() {

    Swal.fire({
        icon: "error",
        title: "INVALID OTP",
        showConfirmButton: true,
        confirmButtonColor: "#3085d6",
        confirmButtonText: "OK"
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "verification-sent.php";
        }
    });

}