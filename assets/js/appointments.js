
        // ======= custom script area =======

        $(document).ready(function() {
            $(document).on("click", ".delete-btn", function() {

                if (confirm("Are You Sure?")) {
                    apntID = $(this).data("id");
                    btn = this;

                    $.ajax({
                        url: "ajax/appointment.delete.ajax.php",
                        type: "POST",
                        data: {
                            id: apntID
                        },
                        success: function(data) {
                            if (data == 1) {
                                $(btn).closest("tr").fadeOut()
                            } else {
                                $("#error-message").html("Deletion Field !!!").slideDown();
                                $("success-message").slideUp();
                            }

                        }
                    });
                }
                return false;
            })

        })

        // =======================================================
        appointmentViewAndEditModal = (appointmentTableID) => {

            let url = "ajax/appointment.view.ajax.php?appointmentTableID=" + appointmentTableID;
            $(".view-and-edit-appointments").html(
                '<iframe width="99%" height="440px" frameborder="0" allowtransparency="true" src="' +
                url + '"></iframe>');

        } // end of LabCategoryEditModal function

        