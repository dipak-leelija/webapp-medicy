<?php

class SearchForAll extends DatabaseConnection
{
    function searchAllFilter($data, $adminId)
    {
        try {
            $searchPattern = "%" . $data . "%";

            // ===== QUERY FOR APPOINTMENTS TABLE =====
            $searchAllFromAppointments = "SELECT * FROM appointments WHERE (appointment_id LIKE ? OR patient_id LIKE ? OR patient_name LIKE ? OR patient_phno LIKE ?) AND admin_id = ? ORDER BY id ASC";

            $appointmentsStatement = $this->conn->prepare($searchAllFromAppointments);
            $appointmentsStatement->bind_param("sssss", $searchPattern, $searchPattern, $searchPattern, $searchPattern, $adminId);

            $appointmentsStatement->execute();

            $appointmentResult = $appointmentsStatement->get_result();

            if ($appointmentResult->num_rows > 0) {
                $appointmentData = array();
                while ($row = $appointmentResult->fetch_object()) {
                    $appointmentData[] = $row;
                }

                return json_encode(['status' => '1', 'message' => 'Data found', 'data' => $appointmentData]);
            } else {
                return json_encode(['status' => '0', 'message' => 'No data found', 'data' => []]);
            }

        } catch (Exception $e) {
            return json_encode(['status' => '0', 'message' => $e->getMessage(), 'data' => '']);
        }
    }
}




 // // ===== QUERY FOR PATIENT DETAILS TABLE =====
            // $searAllFromPatients = "SELECT * FROM patient_details WHERE (patient_id LIKE ? OR patient_details.name LIKE ? OR phno LIKE ?) AND admin_id = ? ORDER BY id DESC";

            // $patientsStmt = $this->conn->prepare($searAllFromPatients);
            // $patientsStmt->bind_param("ssss", $searchPattern, $searchPattern, $searchPattern, $adminId);
            // $patientsStmt->execute();
            // $patientResult = $patientsStmt->get_result();

            // if($patientResult->num_rows > 0){
            //     $patientData = array();
            //     while($row = $patientResult->fetch_object()){
            //         $patientData[] = $row;
            //     }
            // }else{
            //     $patientData = array();
            // }
            