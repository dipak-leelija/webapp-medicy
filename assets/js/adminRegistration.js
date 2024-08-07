const xmlhttp = new XMLHttpRequest();

// powerurl = 'ajax/product.getMedicineDetails.ajax.php?power=' + productId;
// // alert(url);
// xmlhttp.open("GET", powerurl, false);
// xmlhttp.send(null);
// // alert(xmlhttp.responseText);
// document.getElementById("medicine-power").value = xmlhttp.responseText;

// ======== code to chek mobile number input validity ===========
// var mobileInput = document.getElementById('mobile-number');

const validateMobileNumber = () => {

    let mobileInput = document.getElementById('mobile-number');

    var inputValue = mobileInput.value;
    var numericValue = inputValue.replace(/[^0-9]/, '');
    document.getElementById('mobile-number').value = numericValue;

}


const verifyMobileNumber = () =>{
    let cntactNumber = document.getElementById('mobile-number').value;

    if(cntactNumber.length == ' '){
        Swal.fire('alert','enter contact number', 'alert');
    }

    if(cntactNumber.length != 10){
        Swal.fire('error','enter valid contact number', 'error');
    }

    if(cntactNumber.length == 10){
        $.ajax({
            url: "ajax/admin-mail-usrnm-existance-check.ajax.php",
            type: "POST",
            data: {
                checkContact: cntactNumber,
            },
            success: function (data) {
                if (data == '1') {
                    document.getElementById('mobile-number').value = ' ';
                    Swal.fire('alert','Contact number exitst as registered!','alert');
                    document.getElementById('mobile-number').focus();
                } 
            }
        });
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
    // console.log(t.value);
    $.ajax({
        url: "ajax/admin-mail-usrnm-existance-check.ajax.php",
        type: "POST",
        data: {
            chekUsrnmExistance: admUsrnm,
        },
        success: function (data) {
            // console.log("ajax return data : " + data);
            if (data == '1') {
                alert('Username Exits as registered!');
                document.getElementById('user-name').value = ' ';
            }
        }
    });
}



// === otp submit move next ===

function moveToNext(current, nextId) {
    const maxLength = parseInt(current.getAttribute('maxlength'));
    const currentLength = current.value.length;

    if (currentLength >= maxLength) {
        const nextInput = document.getElementById(nextId);
        if (nextInput) {
            nextInput.focus();
        }
    }
}
