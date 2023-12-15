let xmlhttp = new XMLHttpRequest();

// powerurl = 'ajax/product.getMedicineDetails.ajax.php?power=' + productId;
// // alert(url);
// xmlhttp.open("GET", powerurl, false);
// xmlhttp.send(null);
// // alert(xmlhttp.responseText);
// document.getElementById("medicine-power").value = xmlhttp.responseText;



const verifyEmail = () => {
    var inputedMail = document.getElementById('email');

    let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!emailRegex.test(inputedMail.value)) {
        inputedMail.focus();
        console.log('error email');
    } else {
        checkEmailAvailability(inputedMail);
    }
}


const checkEmailAvailability = (inputedMail) =>{
    let mailId = inputedMail.value;
    

    $.ajax({
        url: "ajax/email-verification-validation.ajax.php",
        type: "POST",
        data: {
            chekExistance : mailId,
        },
        success: function(data) {
            console.log(data);
            if (data == 0) {
                alert('Email Exits!');
                // document.getElementById('email').focus();
            } else {
                console.log(data);
            }
        }
    });
}

