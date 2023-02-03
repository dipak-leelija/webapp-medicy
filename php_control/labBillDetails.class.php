<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'dbconnect.php';



class LabBillDetails extends DatabaseConnection{



    function addLabBillDetails($billId, $billingDate, $testDate, $testId, $testPrice, $percentageOfDiscount, $priceAfterDiscount){
        
        $insertBillDetails = "INSERT INTO  lab_billing_details (`bill_id`, `billing_date`, `test_date`, `test_id`, `test_price`, `percentage_of_discount_on_test`, `price_after_discount`) VALUES ('$billId', '$billingDate', '$testDate', '$testId', '$testPrice', '$percentageOfDiscount', '$priceAfterDiscount')";
        // echo $insertEmp.$this->conn->error;
        // exit;
        $billDetailsQuery = $this->conn->query($insertBillDetails);
        return $billDetailsQuery;

    }//end addLabBill function


    function addUpdatedLabBill($billId, $billingDate, $testDate, $testId, $testPrice, $percentageOfDiscount, $priceAfterDiscount, $addedon){
        
        $insertBillDetails = "INSERT INTO  lab_billing_details (`bill_id`, `billing_date`, `test_date`, `test_id`, `test_price`, `percentage_of_discount_on_test`, `price_after_discount`, `added_on`) VALUES ('$billId', '$billingDate', '$testDate', '$testId', '$testPrice', '$percentageOfDiscount', '$priceAfterDiscount', '$addedon')";
        // echo $insertEmp.$this->conn->error;
        // exit;
        $billDetailsQuery = $this->conn->query($insertBillDetails);
        return $billDetailsQuery;

    }//end addLabBill function





    function billDetailsDisplay(){

        $showBilldetails = "SELECT * FROM lab_billing_details";
        $billdetailsQuery = $this->conn->query($showBilldetails);
        $rows = $billdetailsQuery->num_rows;
        if ($rows > 0 ) {
            while($result = $billdetailsQuery->fetch_array()){                
                $data[]	= $result;
            }   
            return $data;
        }else{
            return 0;
        }

    }//end billDetailsDisplay function




    function billDetailsById($billId){

        $selectBilldetail = "SELECT * FROM lab_billing_details WHERE `lab_billing_details`.`bill_id` = '$billId'";
        $billdetailsQuery = $this->conn->query($selectBilldetail);
        $rows = $billdetailsQuery->num_rows;
        if ($rows > 0 ) {
            while($result = $billdetailsQuery->fetch_array()){                
                $data[]	= $result;
            }   
            return $data;
        }else{
            return 0;
        }

    }//end billDetailsDisplay function






    function testsNum($billId){

        $selectBilldetail = "SELECT * FROM lab_billing_details WHERE `lab_billing_details`.`bill_id` = '$billId'";
        $billdetailsQuery = $this->conn->query($selectBilldetail);
        $rows = $billdetailsQuery->num_rows;
        if ($rows > 0 ) {
            while($result = $billdetailsQuery->fetch_array()){                
                $data[]	= $result;
            }   
            return $data;
        }else{
            return 0;
        }

    }//end billDetailsDisplay function


    function updateBillDetails($billId, $billingDate, $testDate, $testId, $testPrice, $percentageOfDiscount, $priceAfterDiscount){
        
        $query = "UPDATE  lab_billing_details SET  `billing_date` = '$billingDate',  `test_date` = '$testDate', `test_id` = '$testId', `test_price` = '$testPrice', `percentage_of_discount_on_test` = '$percentageOfDiscount', `price_after_discount` = '$priceAfterDiscount' WHERE `lab_billing_details`.`bill_id` = '$billId'";
        // echo $insertEmp.$this->conn->error;
        // exit;
        $res = $this->conn->query($query);
        return $res;

    }//end updateLabBill function

    function deleteBillDetails($billId){
        $delBil = "DELETE FROM `lab_billing_details` WHERE `lab_billing_details`.`bill_id` = '$billId'";
        $delBilQuery = $this->conn->query($delBil);
        return $delBilQuery;
    }// end deleteDocCat function



}//end class

?>