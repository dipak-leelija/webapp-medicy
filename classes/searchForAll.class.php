<?php

class SearchForAll extends DatabaseConnection
{
function searchAllFilter($searchData, $adminId)
{
    $resultData = array();
    $appointmentsResultData = array();
    $searchPattern = "%".$searchData."%";

    try {
        // ===== QUERY FOR APPOINTMENTS TABLE =====
        $searchAllForAppointments = "SELECT * FROM appointments WHERE `admin_id` = ? AND `appointment_id` LIKE ?";

        $stmt = $this->conn->prepare($searchAllForAppointments);
        $stmt->bind_param("ss",$adminId, $searchPattern);
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

}
