//for login page //
function showToggleBtn() {
    var inputPassword = document.getElementById('password');
    var toggleBtn = document.getElementById('toggleBtn');

    if (inputPassword.value.trim() !== '') {
        toggleBtn.style.display = 'inline-block';
    } else {
        toggleBtn.style.display = 'none';
    }
}
function togglePassword() {
    var inputPassword = document.getElementById('password');
    var toggleBtn = document.getElementById('toggleBtn');

    if (inputPassword.type === 'password') {
        inputPassword.type = 'text';
        toggleBtn.innerHTML = '<i class="fas fa-eye-slash"></i>' ;
    } else {
        inputPassword.type = 'password';
        toggleBtn.innerHTML = '<i class="fas fa-eye"></i>';
    }
}
// end for login page //

////
function showToggleBtn1() {
    var oldPassword = document.getElementById('old-password');
    var toggleBtn1  = document.getElementById('toggleBtn1');

    if (oldPassword.value.trim() !== '') {
        toggleBtn1.style.display = 'inline-block';
    } else {
        toggleBtn1.style.display = 'none';
    }
}
function togglePassword1() {
    var oldPassword = document.getElementById('old-password');
    var toggleBtn1 = document.getElementById('toggleBtn1');

    if (oldPassword.type === 'password') {
        oldPassword.type = 'text';
        toggleBtn1.innerHTML = '<i class="fas fa-eye-slash"></i>';
    } else {
        oldPassword.type = 'password';
        toggleBtn1.innerHTML = '<i class="fas fa-eye"></i>';
    }
}

////
function showToggleBtn2(){
    var newPassword  = document.getElementById('new-password');
    var toggleBtn2   = document.getElementById('toggleBtn2');

    if (newPassword.value.trim() !== '') {
        toggleBtn2.style.display = 'inline-block';
    } else {
        toggleBtn2.style.display = 'none';
    }
}
function togglePassword2(){
    var newPassword  = document.getElementById('new-password');
    var toggleBtn2   = document.getElementById('toggleBtn2');

    if(newPassword.type === 'password'){
        newPassword.type       = 'text';
        toggleBtn2.innerHTML = '<i class="fas fa-eye-slash"></i>';
    }else{
        newPassword.type       = 'password';
        toggleBtn2.innerHTML = '<i class="fas fa-eye"></i>';
    }
}
////
function showToggleBtn3(){
    var cnfPassword = document.getElementById('cnf-password');
    var toggleBtn3  = document.getElementById('toggleBtn3');

    if(cnfPassword.value.trim() !== ''){
        toggleBtn3.style.display = 'inline-block';
    }else{
        toggleBtn3.style.display = 'none';
    }
}
function togglePassword3(){
    var cnfPassword = document.getElementById('cnf-password');
    var toggleBtn3  = document.getElementById('toggleBtn3');

    if(cnfPassword.type === 'password'){
        cnfPassword.type       = 'text';
        toggleBtn3.innerHTML = '<i class="fas fa-eye-slash"></i>';
    }else{
        cnfPassword.type       = 'password';
        toggleBtn3.innerHTML = '<i class="fas fa-eye"></i>';
    }
}