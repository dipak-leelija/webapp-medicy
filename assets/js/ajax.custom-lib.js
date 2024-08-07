//xml http object for internet explorer
if (window.ActiveXObject) {
    try {
        request = new ActiveXObject("Msxml2.XMLHTTP");
    } //end of try, start catch
    catch (e) {
        try {
            request = new ActiveXObject("Microsoft.XMLHTTP");
        } catch (e) {
            request = false;
        }
    } //end of catch
} else {
    try {
        request = new XMLHttpRequest();
    } catch (e) {
        request = false;
    }

    if (!request) {
        alert("Error Create request");
    }
}

// ==============================================================================
//getView function start
// const getView = (url, paramKey, paramValue, placeElementClass) => {
//     let fullURL = `ajax/${url}?${paramKey}=${paramValue}`;
//     $("."+placeElementClass).html(
//         '<iframe width="99%" height="330px" frameborder="0" allowtransparency="true" src="' +
//         fullURL + '"></iframe>');
// } // end of getView function