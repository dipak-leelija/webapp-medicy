<?php
class LabBillDetails
{

    use DatabaseConnection;

    function addLabBillDetails($billId, $billingDate, $testDate, $testId, $testPrice, $percentageOfDiscount, $priceAfterDiscount)
    {
        try {
            $insertBillDetails = "INSERT INTO lab_billing_details (`bill_id`, `billing_date`, `test_date`, `test_id`, `test_price`, `percentage_of_discount_on_test`, `price_after_discount`) VALUES (?, ?, ?, ?, ?, ?, ?)";

            $stmt = $this->conn->prepare($insertBillDetails);

            if (!$stmt) {
                throw new Exception("Preparation failed: " . $this->conn->error);
            }

            $stmt->bind_param("issssss", $billId, $billingDate, $testDate, $testId, $testPrice, $percentageOfDiscount, $priceAfterDiscount);

            if ($stmt->execute()) {
                return json_encode(['status' => true, 'insertId' => $stmt->insert_id]);
            } else {
                throw new Exception("Execution failed: " . $stmt->error);
            }

            $stmt->close();
        } catch (Exception $e) {
            // error_log($e->getMessage());
            return $e->getMessage();
        }
    } //end addLabBillDetails function





    function addUpdatedLabBill($billId, $billingDate, $testDate, $testId, $testPrice, $percentageOfDiscount, $priceAfterDiscount, $addedon)
    {

        $insertBillDetails = "INSERT INTO  lab_billing_details (`bill_id`, `billing_date`, `test_date`, `test_id`, `test_price`, `percentage_of_discount_on_test`, `price_after_discount`, `added_on`) VALUES ('$billId', '$billingDate', '$testDate', '$testId', '$testPrice', '$percentageOfDiscount', '$priceAfterDiscount', '$addedon')";
        // echo $insertEmp.$this->conn->error;
        // exit;
        $billDetailsQuery = $this->conn->query($insertBillDetails);
        return $billDetailsQuery;
    } //end addLabBill function





    function billDetailsDisplay()
    {

        $showBilldetails = "SELECT * FROM lab_billing_details";
        $billdetailsQuery = $this->conn->query($showBilldetails);
        $rows = $billdetailsQuery->num_rows;
        if ($rows > 0) {
            while ($result = $billdetailsQuery->fetch_array()) {
                $data[]    = $result;
            }
            return $data;
        } else {
            return 0;
        }
    } //end billDetailsDisplay function




    function billDetailsById($billId){
        try {
            $selectBilldetail = "SELECT * FROM lab_billing_details WHERE `lab_billing_details`.`bill_id` = '$billId'";

            $stmt = $this->conn->prepare($selectBilldetail);

            if ($stmt) {
                $stmt->execute();
                $result = $stmt->get_result();
    
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_object()) {
                        $labBillDetails[] = $row;
                    }
                    $returnData = json_encode(['status' => true, 'data' => $labBillDetails]);
                } else {
                    $returnData = json_encode(['status' => false, 'message' => 'No data found']);
                }
                $stmt->close();
                
                return $returnData;
            } else {
                throw new Exception('Statement prepare exception');
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    } 

    function billDetailsByMultiId($billIds)
    {
        try{
            $billIds = implode("','", $billIds); // Convert array to comma-separated string

            $selectBilldetail = "SELECT * FROM lab_billing_details WHERE `lab_billing_details`.`bill_id` IN ('$billIds')";

            $stmt = $this->conn->prepare($selectBilldetail);

            if($stmt){
                $stmt->execute();
                $result = $stmt->get_result();
    
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_object()) {
                        $labBillDetails[] = $row;
                    }
                    $returnData = json_encode(['status' => true, 'data' => $labBillDetails]);
                } else {
                    $returnData = json_encode(['status' => false, 'message' => 'No data found']);
                }
                $stmt->close();
                
                return $returnData;
            } else {
                throw new Exception('Statement prepare exception');
            }
        }catch(Exception $e){
            return $e->getMessage();
        }

        // $billIds = implode("','", $billIds); // Convert array to comma-separated string

        // $selectBilldetail = "SELECT * FROM lab_billing_details WHERE `lab_billing_details`.`bill_id` IN ('$billIds')";
        // $billdetailsQuery = $this->conn->query($selectBilldetail);
        // $rows = $billdetailsQuery->num_rows;
        // if ($rows > 0) {
        //     while ($result = $billdetailsQuery->fetch_array()) {
        //         $data[] = $result;
        //     }
        //     return $data;
        // } else {
        //     return 0;
        // }
    }




    function testsNum($billId)
    {

        $selectBilldetail = "SELECT * FROM lab_billing_details WHERE `lab_billing_details`.`bill_id` = '$billId'";
        $billdetailsQuery = $this->conn->query($selectBilldetail);
        $rows = $billdetailsQuery->num_rows;
        if ($rows > 0) {
            while ($result = $billdetailsQuery->fetch_array()) {
                $data[]    = $result;
            }
            return $data;
        } else {
            return 0;
        }
    } //end billDetailsDisplay function


    function updateBillDetails($billId, $billingDate, $testDate, $testId, $testPrice, $percentageOfDiscount, $priceAfterDiscount)
    {

        $query = "UPDATE  lab_billing_details SET  `billing_date` = '$billingDate',  `test_date` = '$testDate', `test_id` = '$testId', `test_price` = '$testPrice', `percentage_of_discount_on_test` = '$percentageOfDiscount', `price_after_discount` = '$priceAfterDiscount' WHERE `lab_billing_details`.`bill_id` = '$billId'";
        // echo $insertEmp.$this->conn->error;
        // exit;
        $res = $this->conn->query($query);
        return $res;
    } //end updateLabBill function

    function deleteBillDetails($billId)
    {
        $delBil = "DELETE FROM `lab_billing_details` WHERE `lab_billing_details`.`bill_id` = '$billId'";
        $delBilQuery = $this->conn->query($delBil);
        return $delBilQuery;
    } // end deleteDocCat function



} //end class
