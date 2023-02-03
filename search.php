<?php



if(isset($_GET['data'])){

    $data = $_GET['data'];

}

include 'employee/config/dbconnect.php';

$searchSql ="Select * From `doctor_category` WHERE `doctor_category`.`category_name` LIKE '%$data%'";

$searchResult = mysqli_query($conn, $searchSql) or die ("Connection Error") ;



if($searchResult){

while($searchResultRow = mysqli_fetch_assoc($searchResult)){

    $serchId = $searchResultRow['doctor_category_id'];
    $serchR = $searchResultRow['category_name'];

    echo "<h5 style='padding-left: 12px ; padding-top: 5px ;'><a>".$serchR."</a></h5>";

}

}

else{

    echo "Result Not Found";

}



?>