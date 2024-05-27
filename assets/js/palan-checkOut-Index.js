const checkBox = document.getElementById('itemCheck');

if (checkBox.hasAttribute('disabled')) {
    document.getElementById('continue-btn').setAttribute('disabled', 'true');
}


function checkData(){
    var userFirstName = document.getElementById('firstname');
    var userLastName = document.getElementById('lastName');
    var userEmail = document.getElementById('email');
    var userContactNo = document.getElementById('mob-no');
    var userCity = document.getElementById('city');
    var userState = document.getElementById('state');
    var userCountry = document.getElementById('country');
    var userPin = document.getElementById('pin-code');

    if(userFirstName.value != '' || userLastName.value != '' || userEmail.value != '' || userContactNo.value != '' || userCity.value != '' || userState.value != '' || userCountry.value != '' || userPin.value != '')
    {

    }
}

