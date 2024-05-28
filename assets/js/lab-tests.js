const addTestAndSubTest = (t) => {
    console.log(t.id);
    if(t.id == 'add-testType'){
        url = `ajax/add-labTestType.ajax.php`; //  updated path for user.

    $(".productViewModal").html(
        '<iframe width="99%" height="500px" frameborder="0" allowtransparency="true" src="' +
        url + '"></iframe>');
    }

}