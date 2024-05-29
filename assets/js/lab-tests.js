const addTestAndSubTest = (t) => {
    console.log(t.id);

    if(t.id == 'add-testType'){
        url = `ajax/add-labTestType.ajax.php`; //  updated path for user.

        document.getElementById('editNicheDetails').innerHTML = 'Add new test type';

    $(".add-new-test-data-modal").html(
        '<iframe width="99%" height="500px" frameborder="0" allowtransparency="true" src="' +
        url + '"></iframe>');
    }


    if(t.id == 'add-subTest'){
        url = `ajax/add-subTest.ajax.php`; //  updated path for user.

        document.getElementById('editNicheDetails').innerHTML = 'Add subtest details';

    $(".add-new-test-data-modal").html(
        '<iframe width="99%" height="500px" frameborder="0" allowtransparency="true" src="' +
        url + '"></iframe>');
    }
}