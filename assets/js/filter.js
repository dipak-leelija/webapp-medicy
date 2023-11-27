const filterAppointment = (t) =>{

    let fieldID = t.id;
    let data = t.value;

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
            // alert(data);
            console.log(response);
        }
    });

    // var xmlhttp = new XMLHttpRequest();

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