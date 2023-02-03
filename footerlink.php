 <!-- auto scroll to top -->
 <a href="#top" data-scroll class="dmtop global-radius"><i class="fa fa-angle-up"></i></a>
    <!-- Required Javascript -->
    <!-- all js files -->
    <script src="js/all.js"></script>
    <!-- all plugins -->
    <script src="js/custom.js"></script>
    <!-- Map -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCNUPWkb4Cjd7Wxo-T4uoUldFjoiUA1fJc&callback=myMap"></script>
    <!-- Search JQuery -->
    <script>
    let searchReult = document.getElementById('searchReult');
    function imu(x) {
        if (x.length == 0) {
            searchReult.innerHTML = ''
        } else {
            var XML = new XMLHttpRequest();
            XML.onreadystatechange = function() {
                if (XML.readyState == 4 && XML.status == 200) {
                    searchReult.innerHTML = XML.responseText;
                }
            };
            XML.open('GET', 'search.php?data=' + x, true);
            XML.send();
        }
    }
    </script>
    