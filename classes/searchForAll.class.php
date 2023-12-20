<?php

class SearchForAll extends DatabaseConnection{

function searchAllFilterForAppointment($searchData, $adminId){
    $appointmentsResultData = array();
    $searchPattern = "%".$searchData."%";

    try {
        // ===== QUERY FOR APPOINTMENTS TABLE =====
        $searchAllForAppointments = "SELECT * FROM appointments WHERE `admin_id` = ? AND `appointment_id` LIKE ? OR `patient_id` LIKE ? OR `patient_phno` LIKE ?";

        $stmt = $this->conn->prepare($searchAllForAppointments);
        $stmt->bind_param("ssss",$adminId, $searchPattern,$searchPattern,$searchPattern);
        $stmt->execute();
        $appointmentsStatement = $stmt->get_result();

        while ($appointmentsResult = $appointmentsStatement->fetch_assoc()) {
            $appointmentsResultData[] = $appointmentsResult;
        }

        return json_encode(['status' => '1', 'message' => 'Data found', 'data' => $appointmentsResultData]);
    } catch (Exception $e) {
        return json_encode(['status' => '0', 'message' => $e->getMessage(), 'data' => '']);
    }
}

function searchAllFilterForPatient($searchData, $adminId){

    $patientData = array();
    $searchPattern = "%".$searchData."%";

    try {
        // ===== QUERY FOR APPOINTMENTS TABLE =====
        $searchAllForPatients = "SELECT * FROM patient_details WHERE `admin_id` = ? AND `patient_id` LIKE ? OR `phno` LIKE ? OR `name` LIKE ?";

        $stmt = $this->conn->prepare($searchAllForPatients);
        $stmt->bind_param("ssss",$adminId, $searchPattern, $searchPattern,$searchPattern);
        $stmt->execute();
        $patientStatement = $stmt->get_result();

        while ($patientResult = $patientStatement->fetch_assoc()) {
            $patientData[] = $patientResult;
        }

        return json_encode(['status' => '1', 'message' => 'Data found', 'data' => $patientData]);
    } catch (Exception $e) {
        return json_encode(['status' => '0', 'message' => $e->getMessage(), 'data' => '']);
    }
}

function searchAllFilterForStockIn($searchData, $adminId){

    $stockinResultData = array();
    $searchPattern = "%".$searchData."%";

    try {
        // ===== QUERY FOR APPOINTMENTS TABLE =====
        $searchAllForStockIn = "SELECT * FROM stock_in WHERE `admin_id` = ? AND `distributor_bill` LIKE ?";

        $stmt = $this->conn->prepare($searchAllForStockIn);
        $stmt->bind_param("ss",$adminId, $searchPattern);
        $stmt->execute();
        $stockinStatement = $stmt->get_result();

        while ($stockInResult = $stockinStatement->fetch_assoc()) {
             $stockinResultData[] = $stockInResult;
        }

        return json_encode(['status' => '1', 'message' => 'Data found', 'data' =>  $stockinResultData]);
    } catch (Exception $e) {
        return json_encode(['status' => '0', 'message' => $e->getMessage(), 'data' => '']);
    }
}

}