const xmlhttp = new XMLHttpRequest();



function openAddNewTestModal() {
    console.log('opening modal');

    // Initialize the modal
    var addTestDataModel = new bootstrap.Modal(document.getElementById('addTestDataModel'));
    addTestDataModel.show();

    let labTypeId = document.getElementById('lab-type-id-holder').value;
    
    // URL for the content
    var url = `ajax/add-new-labTestData.ajax.php?labTypeId=${labTypeId}`;
    
    // Load the content into the modal body
    $(".add-new-test-data-modal").html(
        '<iframe width="100%" height="500px" frameborder="0" allowtransparency="true" src="' + url + '"></iframe>'
    );
}
