// doctor data add modal link -------
const addDoctor = (docId) => {
    let ViewAndEditdocId = docId;
    // alert(ViewAndEditdocId);
    let url = "ajax/doctor.data.add.ajax.php";

    $(".addDoctorDataModal").html('<iframe width="100%" height="500px" frameborder="0" allowtransparency="true"  src="' + url + '"></iframe>');
}

// doctor data add --------
const addDocDetails = () => {
    // alert('add details');
    let docName = document.getElementById('doc-name').value;
    let docRegNo = document.getElementById('doc-reg-no').value;
    let docSpecialization = document.getElementById('doc-speclz-id').value;
    let docDegree = document.getElementById('doc-degree').value;
    let docEmail = document.getElementById('email').value;
    let docMob = document.getElementById('doc-phno').value;
    let docAddress = document.getElementById('doc-address').value;
    let docAlsoWith = document.getElementById('doc-with').value;

    if (docName != '' && docSpecialization != '' && docAlsoWith != '' && docEmail != '' && docMob != '' && docRegNo != '' && docDegree != '' && docAddress != '') {

        console.log(docAlsoWith);

        $.ajax({
            url: 'doctors.new.data.add.ajax.php',
            type: 'POST',
            data: {
                docName: docName,
                docSpecialization: docSpecialization,
                docAlsoWith: docAlsoWith,
                docEmail: docEmail,
                docMob: docMob,
                docRegNo: docRegNo,
                docDegree: docDegree,
                docAddress: docAddress
            },

            success: function (data) {
                // alert(data);
                if (data == 1) {
                    Swal.fire({
                        title: "Success",
                        text: "Data added successfull.",
                        icon: "success",
                        showCancelButton: false,
                        confirmButtonColor: "#3085d6",
                        confirmButtonText: "Ok"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.reload();
                        }
                    });
                } else {
                    Swal.fire('Error', 'Unable to add data', 'error');
                }


                // Clear input fields after success
                document.getElementById('doc-name').value = '';
                document.getElementById('doc-reg-no').value = '';
                document.getElementById('doc-speclz-id').value = '';
                document.getElementById('doc-speclz').value = '';
                document.getElementById('doc-degree').value = '';
                document.getElementById('email').value = '';
                document.getElementById('doc-phno').value = '';
                document.getElementById('doc-address').value = '';
                document.getElementById('doc-with').value = '';

            }
        });
    } else {
        Swal.fire('Alert', 'Must fill all blank fields.', 'info');
    }

}




// doctor data edit -------
const docViewAndEdit = (docId) => {
    let ViewAndEditdocId = docId;
    // alert(ViewAndEditdocId);
    let url = "ajax/doctors.view.ajax.php?docId=" + ViewAndEditdocId;

    $(".docViewAndEditModal").html('<iframe width="100%" height="500px" frameborder="0" allowtransparency="true"  src="' + url + '"></iframe>');
} // end of viewAndEdit function





// delete doctor data ----------
$(document).ready(function () {
    $(document).on("click", ".delete-btn", function () {

        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"

        }).then((result) => {
            if (result.isConfirmed) {

                docId = $(this).data("id");
                btn = this;
                // alert(btn);

                $.ajax({
                    url: "ajax/doctors.delete.ajax.php",
                    type: "POST",
                    data: {
                        id: docId
                    },
                    success: function (data) {
                        if (data == 1) {
                            $(btn).closest("tr").fadeOut()
                        } else {
                            $("#error-message").html("Deletion Field !!!").slideDown();
                            $("success-message").slideUp();
                        }
                    }
                });
            }
        });
    })
})



/// check inputed data is mail or not

const checkMail = (t) =>{

    let email = t.value;

    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if(emailRegex.test(email)){
        document.getElementById('doc-phno').focus();
    }else{
        document.getElementById('email').value = '';
        Swal.fire('Alert','Enter valid email id.','info');
    }
}


//// check inputed mobile length validity

const checkMobNo = (t) =>{
    if(t.value.length > 9){
        Swal.fire('Alert','Enter Maximum 10 digit','error');
        document.getElementById('doc-phno').value = '';
    }
}

const checkContactNo = (t) =>{
    if(t.value.length < 9){
        Swal.fire('Error','Mobile number must be 10 digit','error');
        document.getElementById('doc-phno').value = '';
    }
}

// ======== script for ductors data edit update form doctors.view.ajax.php ========

function editDoc() {

    let docId = $("#doc-id").val();
    let docName = $("#doc-name").val();
    let docRegNo = $("#doc-reg-no").val();
    let docSpecialization = document.getElementById('doc-speclz-id').value;
    let docDegree = $("#doc-degree").val();
    let docAlsoWith = $("#doc-with").val();
    let docEmail = $("#email").val();
    let docPhno = $("#doc-phno").val();
    let docAddress = $("#doc-address").val();


    $.ajax({
        url: "doctors.edit.ajax.php",
        type: "POST",
        data: {
            docId: docId,
            docName: docName,
            docRegNo: docRegNo,
            docSpecialization: docSpecialization,
            docDegree: docDegree,
            docAlsoWith: docAlsoWith,
            docEmail: docEmail,
            docPhno: docPhno,
            docAddress: docAddress
        },

        success: function(data) {
            // alert(data);
            if (data == 1) {
                document.getElementById('reportUpdateSuccess').style.display='block';
                document.getElementById('reportUpdateSuccess').innerHTML = 'Data edited & updated successfully.';
            } else {
                document.getElementById('reportUpdateFail').style.display='block';
                document.getElementById('reportUpdateFail').innerHTML = 'Error occur during updation';
            }
        }
    });

    //--------------------------------------------------
}

