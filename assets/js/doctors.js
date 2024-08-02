/*==================== ADD NEW DOCTOR ====================*/
const addDocDetails = () => {
  const fields = [
    "doc-name",
    "doc-reg-no",
    "doc-speclz-id",
    "doc-degree",
    "email",
    "doc-phno",
    "doc-address",
    "doc-with",
  ];
  const data = {};

  let isEmpty = false;
  var errMsg = "Mandatory Fields Must be Field!";

  fields.forEach((field) => {
    data[field] = document.getElementById(field).value.trim();
    if (
      !data[field] &&
      (field === "doc-name" ||
        field === "doc-reg-no" ||
        field === "doc-speclz-id" ||
        field === "doc-degree")
    ) {
      isEmpty = true;
    }
  });

  if (!data["doc-speclz-id"]) {
    Swal.fire("Alert", "Select Specialization From Dropdown!", "error");
    return;
  }

  if (data["email"]) {
    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(data["email"])) {
      Swal.fire("Alert", "Provide Correct Email Address!", "error");
      return;
    }
  }

  if (data["doc-phno"]) {
    if (data["doc-phno"].length != 10) {
      Swal.fire("Alert", "Enter 10 Digit Contact Number!", "error");
      return;
    }
  }

  if (isEmpty) {
    Swal.fire("Alert", errMsg, "info");
    return;
  }

  $.ajax({
    url: "ajax/doctors.new.data.add.ajax.php",
    type: "POST",
    data: {
      docName: data["doc-name"],
      docRegNo: data["doc-reg-no"],
      docSpecialization: data["doc-speclz-id"],
      docDegree: data["doc-degree"],
      docEmail: data["email"],
      docMob: data["doc-phno"],
      docAddress: data["doc-address"],
      docAlsoWith: data["doc-with"],
    },
    success: function (response) {
      if (response == 1) {
        Swal.fire({
          title: "Success",
          text: "Data added successfully.",
          icon: "success",
          showCancelButton: false,
          confirmButtonColor: "#3085d6",
          confirmButtonText: "Ok",
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.reload();
          }
        });

        // Clear input fields after success
        fields.forEach((field) => {
          document.getElementById(field).value = "";
        });
      } else {
        Swal.fire("Error", "Unable to add data", "error");
      }
    },
    error: function () {
      Swal.fire("Error", "Ajax request failed", "error");
    },
  });
};
/*==================== EOF ADD NEW DOCTOR ====================*/

// doctor data edit -------
const docViewAndEdit = (docId) => {
  let ViewAndEditdocId = docId;
  let url = "ajax/doctors.view.ajax.php?docId=" + ViewAndEditdocId;
  $(".docViewAndEditModal").html(
    '<iframe width="100%" height="500px" frameborder="0" allowtransparency="true"  src="' +
      url +
      '"></iframe>'
  );
}; // end of viewAndEdit function

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
      confirmButtonText: "Yes, delete it!",
    }).then((result) => {
      if (result.isConfirmed) {
        docId = $(this).data("id");
        btn = this;
        // alert(btn);

        $.ajax({
          url: "ajax/doctors.delete.ajax.php",
          type: "POST",
          data: {
            id: docId,
          },
          success: function (data) {
            if (data == 1) {
              $(btn).closest("tr").fadeOut();
            } else {
              $("#error-message").html("Deletion Field !!!").slideDown();
              $("success-message").slideUp();
            }
          },
        });
      }
    });
  });
});

// ======== script for ductors data edit update form doctors.view.ajax.php ========

function editDoc() {
  let docId = $("#doc-id").val();
  let docName = $("#doc-name").val();
  let docRegNo = $("#doc-reg-no").val();
  let docSpecialization = document.getElementById("doc-speclz-id").value;
  let docDegree = $("#doc-degree").val();
  let docAlsoWith = $("#doc-with").val();
  let docEmail = $("#email").val();
  let docPhno = $("#doc-phno").val();
  let docAddress = $("#doc-address").val();

  const fields = [
    "doc-id",
    "doc-name",
    "doc-reg-no",
    "doc-speclz-id",
    "doc-degree",
    "email",
    "doc-phno",
    "doc-address",
    "doc-with",
  ];

  const data = {};

  let isEmpty = false;
  var errMsg = "Mandatory Fields Must be Field!";

  fields.forEach((field) => {
    data[field] = document.getElementById(field).value.trim();
    if (
      !data[field] &&
      (field === "doc-name" ||
        field === "doc-reg-no" ||
        field === "doc-speclz-id" ||
        field === "doc-degree")
    ) {
      isEmpty = true;
    }
  });

  if (!data["doc-speclz-id"]) {
    Swal.fire("Alert", "Select Specialization From Dropdown!", "error");
    return;
  }

  if (data["email"]) {
    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(data["email"])) {
      Swal.fire("Alert", "Provide Correct Email Address!", "error");
      return;
    }
  }

  if (data["doc-phno"]) {
    if (data["doc-phno"].length != 10) {
      Swal.fire("Alert", "Enter 10 Digit Contact Number!", "error");
      return;
    }
  }

  if (isEmpty) {
    Swal.fire("Alert", errMsg, "info");
    return;
  }

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
      docAddress: docAddress,
    },

    success: function (data) {
      if (data == 1) {
        Swal.fire("Alert", "Updated Successfully!", "success");
      } else {
        Swal.fire("Alert", "Error occur during updation!", "error");
      }
    },
  });
}

// =======================================================================================

// //////////////////// set specialization /////////////////////
const docSpecializationId = document.getElementById("doc-speclz-id");
const docSpecializationInput = document.getElementById("doc-speclz");
const dropdown = document.getElementsByClassName("c-dropdown")[0];

docSpecializationInput.addEventListener("focus", () => {
  dropdown.style.display = "block";
});

document.addEventListener("click", (event) => {
  if (
    !docSpecializationInput.contains(event.target) &&
    !dropdown.contains(event.target)
  ) {
    dropdown.style.display = "none";
  }
});

document.addEventListener("blur", (event) => {
  if (!dropdown.contains(event.relatedTarget)) {
    setTimeout(() => {
      dropdown.style.display = "none";
    }, 100);
  }
});

docSpecializationInput.addEventListener("keyup", () => {
  let list = document.getElementsByClassName("lists")[0];
  docSpecializationId.value = "";

  if (docSpecializationInput.value.length > 2) {
    var reqUrl = `ajax/doc-specialization-list-view.ajax.php?match=${docSpecializationInput.value}`;
    request.open("GET", reqUrl, false);
    request.send(null);
    list.innerHTML = request.responseText;
  } else {
    var reqUrl = `ajax/doc-specialization-list-view.ajax.php?match=*`;
    request.open("GET", reqUrl, false);
    request.send(null);
    list.innerHTML = request.responseText;
  }
  // console.log(reqUrl);
  // console.log("check return : "+request.responseText);
});

const setDocSpecialization = (t) => {
  let specializationId = t.id.trim();
  let specializationName = t.innerHTML.trim();

  document.getElementById("doc-speclz-id").value = specializationId;
  document.getElementById("doc-speclz").value = specializationName;
  // document.getElementById("distributor-id").value = distributirName;

  document.getElementsByClassName("c-dropdown")[0].style.display = "none";
};
