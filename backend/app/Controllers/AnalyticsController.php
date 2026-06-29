<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Controller;

class AnalyticsController extends Controller
{
    use ResponseTrait;

    public function getParticipants($year)
    {
        $db = \Config\Database::connect();
        
        $sql = "
            SELECT 
                MONTH(start_date) as month,
                SUM(male) as total_male,
                SUM(female) as total_female
            FROM archived_accomplishment_reports
            WHERE YEAR(start_date) = ?
            GROUP BY MONTH(start_date)
            ORDER BY MONTH(start_date) ASC
        ";

        $results = $db->query($sql, [$year])->getResultArray();

        $monthsData = array_fill(1, 12, ['male' => 0, 'female' => 0]);

        foreach ($results as $row) {
            if ($row['month']) {
                $monthsData[(int)$row['month']] = [
                    'male' => (int)$row['total_male'],
                    'female' => (int)$row['total_female']
                ];
            }
        }

        // Fetch by office
        $officeSql = "
            SELECT 
                COALESCE(office_units.office_name, 'Unknown') as office_name,
                SUM(archived_accomplishment_reports.male) as total_male,
                SUM(archived_accomplishment_reports.female) as total_female
            FROM archived_accomplishment_reports
            LEFT JOIN users ON users.id = archived_accomplishment_reports.user_id
            LEFT JOIN office_units ON office_units.office_id = users.office_id
            WHERE YEAR(archived_accomplishment_reports.start_date) = ?
            GROUP BY office_units.office_id, office_units.office_name
        ";
        $officeResults = $db->query($officeSql, [$year])->getResultArray();

        $officeData = [];
        foreach ($officeResults as $row) {
            $officeData[] = [
                'office' => $row['office_name'],
                'male' => (int)$row['total_male'],
                'female' => (int)$row['total_female']
            ];
        }

        return $this->respond([
            'success' => true,
            'year' => $year,
            'data' => array_values($monthsData),
            'officeData' => $officeData
        ]);
    }

    public function getParticipantsByUser($year, $userId)
    {
        $db = \Config\Database::connect();
        
        $sql = "
            SELECT 
                MONTH(start_date) as month,
                SUM(male) as total_male,
                SUM(female) as total_female
            FROM archived_accomplishment_reports
            WHERE YEAR(start_date) = ? AND user_id = ?
            GROUP BY MONTH(start_date)
            ORDER BY MONTH(start_date) ASC
        ";

        $results = $db->query($sql, [$year, $userId])->getResultArray();

        $monthsData = array_fill(1, 12, ['male' => 0, 'female' => 0]);

        foreach ($results as $row) {
            if ($row['month']) {
                $monthsData[(int)$row['month']] = [
                    'male' => (int)$row['total_male'],
                    'female' => (int)$row['total_female']
                ];
            }
        }

        return $this->respond([
            'success' => true,
            'year' => $year,
            'data' => array_values($monthsData)
        ]);
    }
}
