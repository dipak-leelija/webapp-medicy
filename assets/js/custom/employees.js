
// employee edit modal Script
viewAndEdit = (empId) => {
    let employeeId = empId;
    let url = "ajax/emp.view.ajax.php?employeeId=" + employeeId;
    $(".viewnedit").html('<iframe width="99%" height="440px" frameborder="0" allowtransparency="true" src="' +
        url + '"></iframe>');
} // end of viewAndEdit function



// employee delete scritp
$(document).ready(function() {
    $(document).on("click", ".delete-btn", function() {

        if (confirm("Are you want delete data?")) {
            empId = $(this).data("id");
            //echo $empDelete.$this->conn->error;exit;

            btn = this;
            $.ajax({
                url: "ajax/employee.Delete.ajax.php",
                type: "POST",
                data: {
                    id: empId
                },
                success: function(response) {

                    if (response == 1) {
                        $(btn).closest("tr").fadeOut()
                    } else {
                        // $("#error-message").html("Deletion Field !!!").slideDown();
                        // $("success-message").slideUp();
                        alert(response);
                    }

                }
            });
        }
        return false;
    })

})



// password show hide script
function showHide(fieldId) {
    const password = document.getElementById(fieldId);
    const toggle = document.getElementById('toggle');

    if (password.type === 'password') {
        password.setAttribute('type', 'text');
        // toggle.classList.add('hide');
    } else {
        password.setAttribute('type', 'password');
        // toggle.classList.remove('hide');
    }
}



// ========== employee username and email contol ==========

const checkEmpUsrNm = (t) =>{

    let empUsrNm = t.value;

    $.ajax({
        url: "ajax/empUsernameEmailCheckExistance.ajax.php",
        type: "POST",
        data: {
            empUsrNm: empUsrNm,
        },
        success: function (data) {
            console.log("ajax employee username return data : " + data);
            if (data == '1') {
                alert('Username Exits as registered!');
                document.getElementById('emp-username').value = ' ';
                // document.getElementById('email').focus();
                return 1;
            } else {
                return 0;
            }
        }
    });
}


const checkEmpEmail = (t) =>{

    let email = t.value;

    $.ajax({
        url: "ajax/empUsernameEmailCheckExistance.ajax.php",
        type: "POST",
        data: {
            empEmail: email,
        },
        success: function (data) {
            console.log("ajax employee email return data : " + data);
            if (data == '1') {
                alert('Email Exits as registered!');
                document.getElementById('emp-mail').value = ' ';
                // document.getElementById('email').focus();
                return 1;
            } else {
                return 0;
            }
        }
    });
}