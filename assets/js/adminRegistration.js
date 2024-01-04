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
            alert('Email valid email id!');
        } else {
            checkEmailAvailability();
        }
    } else {
        alert('Email input field not found');
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
            // console.log("ajax return data : " + data);
            if (data == 1) {
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