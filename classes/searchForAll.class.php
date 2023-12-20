<?php

class SearchForAll extends DatabaseConnection
{
    function searchAllFilter($searchData, $adminId)
    {
        $resultData = array(); 
        $appointmentsResultData = array();
        $patientsResultData = array();
        $searchPattern = "%".$searchData."%";

        try {
            // ===== QUERY FOR APPOINTMENTS TABLE =====
            $searchAllForAppointments = "SELECT * FROM appointments WHERE `admin_id` = '$adminId' AND `appointment_id` LIKE '%$searchData%'";

            $appointmentsStatement = $this->conn->query($searchAllForAppointments);

            var_dump($appointmentsStatement);

            while ($appointmentsResult = $appointmentsStatement->fetch_assoc()) {
                $appointmentsResultData[] = $appointmentsResult;
            }




            // // ===== QUERY FOR APPOINTMENTS TABLE =====
            // $searchAllForPatients = "SELECT * FROM patient_details WHERE `admin_id`= ? AND
            //                                 `patient_id` LIKE CONCAT('%', ?, '%') OR
            //                                 `phno` LIKE CONCAT('%', ?, '%')";

            // $patientsStatement = $this->conn->prepare($searchAllForPatients);
            
            // $patientsStatement->bind_param("sss", $adminId, $searchData, $searchData);
            // $patientsStatement->execute();

            // $patientsQueryResult = $patientsStatement->get_result();

            // while ($patientsResult = $patientsQueryResult->fetch_assoc()) {
            //     $patientsResultData[] = $patientsResult;
            // }

            // $resultData = array_merge($appointmentsResultData, $patientsResultData);

            return json_encode(['status' => '1', 'message' => 'Data found', 'data' => $appointmentsResultData]);

        } catch (Exception $e) {
            return json_encode(['status' => '0', 'message' => $e->getMessage(), 'data' => '']);
        }
    }
}
