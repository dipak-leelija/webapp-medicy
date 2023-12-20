<?php 
require_once dirname(__DIR__).'/config/constant.php';
require_once ROOT_DIR.'_config/sessionCheck.php'; //check admin loggedin or not

require_once CLASS_DIR.'dbconnect.php';
require_once CLASS_DIR.'searchForAll.class.php';

$SearchForAll = new SearchForAll;

// === sod fixd date data fetch =======
if(isset($_GET['searchKey'])){
    $searchFor = $_GET['searchKey'];
    
    $searchResult = json_decode($SearchForAll->searchAllFilter($searchFor, $adminId));
    // if($searchResult->status){
    //     $searchResult = $searchResult->data;
    // }

    print_r($searchResult);
}

?>