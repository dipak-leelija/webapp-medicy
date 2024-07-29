
document.addEventListener('DOMContentLoaded', function () {
    var sidebarToggle = document.getElementById('sidebarToggle');
    var expandbtn = document.getElementById('sidebarExp');
    var buttons = ['sidebarExp1', 'sidebarExp2', 'sidebarExp3', 'sidebarExp4'];
    var sidebar = document.getElementById('accordionSidebar');

    sidebarToggle.addEventListener('click', function () {
        sidebarToggle.classList.toggle('active');
    });

    expandbtn.addEventListener('click', function () {
        console.log('click');
        sidebar.classList.toggle('sidebar');
        sidebarToggle.classList.toggle('expanded');
    });

    function toggleSidebar() {
        sidebar.classList.toggle('sidebar');
        sidebarToggle.classList.toggle('expanded');
    }

    buttons.forEach(function(buttonId) {
        var button = document.getElementById(buttonId);
        button.addEventListener('click', toggleSidebar);
    });

});


// document.addEventListener('DOMContentLoaded', function() {
//     var sidebar = document.getElementById('accordionSidebar');
//     var buttons = ['sidebarExp1', 'sidebarExp2', 'sidebarExp3', 'sidebarExp4'];

//     function toggleSidebar() {
//         sidebar.classList.toggle('sidebar');
//         sidebarToggle.classList.toggle('expanded');
//     }

//     buttons.forEach(function(buttonId) {
//         var button = document.getElementById(buttonId);
//         button.addEventListener('click', toggleSidebar);
//     });
// });