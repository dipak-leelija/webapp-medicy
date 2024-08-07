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
    Swal.fire("Failed", "Select Specialization From Dropdown!", "error");
    return;
  }

  if (data["email"]) {
    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(data["email"])) {
      Swal.fire("Failed", "Provide Correct Email Address!", "error");
      return;
    }
  }

  if (data["doc-phno"]) {
    if (data["doc-phno"].length != 10) {
      Swal.fire("Failed", "Enter 10 Digit Contact Number!", "error");
      return;
    }
  }

  if (isEmpty) {
    Swal.fire("Failed", errMsg, "info");
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
        Swal.fire("Success", "Unable to add data", "error");
      }
    },
    error: function () {
      Swal.fire("Error", "Ajax request failed", "error");
    },
  });
};
/*==================== EOF ADD NEW DOCTOR ====================*/

/*==================== DOCTOR DATA VIEW ====================*/
const docViewAndEdit = (docId) => {
  let url = "ajax/doctors.view.ajax.php?docId=" + docId;

  fetch(url)
    .then((response) => {
      if (!response.ok) {
        throw new Error("Network response was not ok " + response.statusText);
      }
      return response.text();
    })
    .then((data) => {
      // Assuming there is an element with the class "docViewAndEditModal"
      let modalElement = document.querySelector(".docViewAndEditModal");
      if (modalElement) {
        modalElement.innerHTML = data;
      } else {
        Swal.fire("Error", "Modal element not found", "error");
      }
    })
    .catch((error) => {
      Swal.fire("Error", "Fetch operation error!", "error");
      console.error(
        "There has been a problem with your fetch operation:",
        error
      );
    });
};
/*==================== EOF DOCTOR DATA VIEW ====================*/

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
  let docId = $("#u-doctor-id").val();
  let docName = $("#u-doctor-name").val();
  let docRegNo = $("#u-doc-reg-no").val();
  let docSpecialization = document.getElementById("u-doc-speclz-id").value;
  let docDegree = $("#u-doc-degree").val();
  let docEmail = $("#u-doc-email").val();
  let docPhno = $("#u-doc-phno").val();
  let docAddress = $("#doc-address").val();
  let docAlsoWith = $("#doc-with").val();

  const fields = [
    "u-doctor-id",
    "u-doctor-name",
    "u-doc-reg-no",
    "u-doc-speclz-id",
    "u-doc-degree",
    "u-doc-email",
    "u-doc-phno",
    "u-doc-address",
    "u-doc-with",
  ];

  const data = {};

  let isEmpty = false;
  var errMsg = "Mandatory Fields Must be Field!";

  fields.forEach((field) => {
    data[field] = document.getElementById(field).value.trim();
    if (
      !data[field] &&
      (field === "u-doctor-name" ||
        field === "u-doc-reg-no" ||
        field === "u-doc-speclz-id" ||
        field === "u-doc-degree")
    ) {
      isEmpty = true;
    }
  });

  if (!data["u-doc-speclz-id"]) {
    Swal.fire("Failed", "Select Specialization From Dropdown!", "error");
    return;
  }

  if (data["u-doc-email"]) {
    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(data["u-doc-email"])) {
      Swal.fire("Failed", "Provide Correct Email Address!", "error");
      return;
    }
  }

  if (data["u-doc-phno"]) {
    if (data["u-doc-phno"].length != 10) {
      Swal.fire("Failed", "Enter 10 Digit Contact Number!", "error");
      return;
    }
  }

  if (isEmpty) {
    Swal.fire("Failed", errMsg, "info");
    return;
  }

  $.ajax({
    url: "ajax/doctors.edit.ajax.php",
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
        Swal.fire("Success", "Updated Successfully!", "success");
      } else {
        Swal.fire("Failed", "Something is Wrong!", "error");
        console.error(data);
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
