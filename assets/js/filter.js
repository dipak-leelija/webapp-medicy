var xmlhttp = new XMLHttpRequest();
const filterAppointment = (t) =>{

    let fieldID = t.id;
    let data = t.value;

    console.log(fieldID);
    console.log(data);
    // alert(fieldID);
    // alert(data);

    $.ajax({
        url: "ajax/filter.ajax.php",
        type: "POST",
        data: {
            searchFor: fieldID,
            search: data
        },
        success: function(response) {
            // alert(response);
            console.log(response);
            // $('#appointments-dataTable').html(response)

            $('#appointments-dataTable').empty();

            
            $.each(response, function (index, item) {
                $('#appointments-dataTable tbody').append('<tr><td>' + item.appointment_id + '</td><td>' + item.patient_name + '</td><td>' + item.patient_age + '</td></tr>');
            });
            
            
        }
    });

    

    // if (table == 'added_on' && data == 'CR') {
    //     // window.alert(table);
    //     // window.alert(data);
    //     showHiddenDiv1();
    // }

    // if (table == 'added_on' && data != 'CR') {
    //     showHiddenDiv2();
    //     let frmDate = 'fdate';
    //     let toDate = 'tdate';
    //     filterUrl = `ajax/return.filter.ajax.php?table=${table}&value=${data}&fromDate=${frmDate}&toDate=${toDate}`;
    //     xmlhttp.open("GET", filterUrl, false);
    //     xmlhttp.send(null);
    //     document.getElementById("filter-table").innerHTML = xmlhttp.responseText;
    // }

    // if (table != 'added_on') {
    //     let frmDate = 'fdate';
    //     let toDate = 'tdate';
    //     filterUrl2 = `ajax/return.filter.ajax.php?table=${table}&value=${data}&fromDate=${frmDate}&toDate=${toDate}`;
    //     xmlhttp.open("GET", filterUrl2, false);
    //     xmlhttp.send(null);
    //     document.getElementById("filter-table").innerHTML = xmlhttp.responseText;
    // }
}

const filterPatients = (t) =>{
    let fieldID = t.id;
    let data = t.value;
    // console.log(fieldID);
    console.log(data);


    $.ajax({
        url: "ajax/filter.ajax.php",
        type: "POST",
        data: {
            searchFor: fieldID,
            search: data
        },
        success: function(response) {
            // alert(data);
            // console.log(response);
            $('#dataTable').html(response);
            
        }
    });
}
