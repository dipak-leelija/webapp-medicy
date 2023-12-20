<?php

class SearchForAll extends DatabaseConnection
{
    function searchAllFilter($searchData, $adminId)
    {
        $resultData = array(); 
        $appointmentsResultData = array();
        $patientsResultData = array();

        try {
            // ===== QUERY FOR APPOINTMENTS TABLE =====
            $searchAllForAppointments = "SELECT * FROM appointments WHERE 
                                            `appointment_id` LIKE CONCAT('%', ?, '%') OR
                                            `patient_id` LIKE CONCAT('%', ?, '%') OR
                                            `patient_phno` LIKE CONCAT('%', ?, '%')";

            $appointmentsStatement = $this->conn->prepare($searchAllForAppointments);
            
            $appointmentsStatement->bind_param("sss", $searchData, $searchData, $searchData);
            $appointmentsStatement->execute();

            $appointmentsQueryResult = $appointmentsStatement->get_result();

            while ($appointmentsResult = $appointmentsQueryResult->fetch_assoc()) {
                $appointmentsResultData[] = $appointmentsResult;
            }




            // ===== QUERY FOR APPOINTMENTS TABLE =====
            $searchAllForPatients = "SELECT * FROM patient_details WHERE 
                                            `patient_id` LIKE CONCAT('%', ?, '%') OR
                                            `phno` LIKE CONCAT('%', ?, '%')";

            $patientsStatement = $this->conn->prepare($searchAllForPatients);
            
            $patientsStatement->bind_param("ss", $searchData, $searchData);
            $patientsStatement->execute();

            $patientsQueryResult = $patientsStatement->get_result();

            while ($patientsResult = $patientsQueryResult->fetch_assoc()) {
                $patientsResultData[] = $patientsResult;
            }

            $result = array_merge($appointmentsResultData, $patientsResultData);

            return json_encode(['status' => '1', 'message' => 'Data found', 'data' => $result]);

        } catch (Exception $e) {
            return json_encode(['status' => '0', 'message' => $e->getMessage(), 'data' => '']);
        }
    }
}
