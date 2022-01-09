<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Record_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function getMedicalRecord($checkUpID = 0)
    {
        $data = [];

        $sql = "
        SELECT 
            cu.*, CONCAT(firstname, ' ', middlename, ' ', lastname, ' ', suffix) AS patient_name, c.name AS course_name, age, gender, section, y.name AS year_name, s.name AS service_name
        FROM check_ups AS cu 
            LEFT JOIN services AS s ON cu.service_id = s.service_id
            LEFT JOIN patients AS p USING(patient_id)
            LEFT JOIN courses AS c ON p.course_id = c.course_id
            LEFT JOIN years AS y ON p.year_id = y.year_id
        WHERE check_up_id = $checkUpID";
        $query  = $this->db->query($sql);
        $result = $query ? $query->row() : null;

        if ($result) 
        {
            $sqlMedicine = "
            SELECT 
                cum.*, m.name AS medicine_name, m.brand AS medicine_brand, u.name AS unit_name, m2.name AS measurement_name
            FROM check_up_medicines AS cum 
                LEFT JOIN medicines AS m USING(medicine_id)
                LEFT JOIN category AS c ON m.category_id = c.category_id
                LEFT JOIN units AS u ON m.unit_id = u.unit_id
                LEFT JOIN measurements AS m2 ON m.measurement_id = m2.measurement_id
            WHERE cum.check_up_id = $checkUpID";
            $queryMedicine  = $this->db->query($sqlMedicine);
            $resultMedicine = $queryMedicine ? $queryMedicine->result_array() : [];

            $sqlCareEquipment = "
            SELECT 
                cuce.*, ce.name AS care_equipment_name, u.name AS unit_name
            FROM check_up_care_equipments AS cuce 
                LEFT JOIN care_equipments AS ce USING(care_equipment_id)
                LEFT JOIN units AS u ON ce.unit_id = u.unit_id
            WHERE cuce.check_up_id = $checkUpID";
            $queryCareEquipment  = $this->db->query($sqlCareEquipment);
            $resultCareEquipment = $queryCareEquipment ? $queryCareEquipment->result_array() : [];

            $sqlClinicalCase = "
            SELECT 
                ccr.*
            FROM clinical_case_records AS ccr 
            WHERE ccr.check_up_id = $checkUpID";
            $queryClinicalCase  = $this->db->query($sqlClinicalCase);
            $resultClinicalCase = $queryClinicalCase ? $queryClinicalCase->result_array() : [];

            $data = [
                'service_name'       => $result->service_name,
                'patient_name'       => $result->patient_name,
                'gender'             => $result->gender,
                'age'                => $result->age,
                'course_name'        => $result->course_name,
                'year_name'          => $result->year_name,
                'section'            => $result->section,
                'temperature'        => $result->temperature,
                'pulse_rate'         => $result->pulse_rate,
                'respiratory_rate'   => $result->respiratory_rate,
                'blood_pressure'     => $result->blood_pressure,
                'random_blood_sugar' => $result->random_blood_sugar,
                'others'             => $result->others,
                'recommendation'     => $result->recommendation,
                'medicine'           => $resultMedicine,
                'care_equipment'     => $resultCareEquipment,
                'clinical_case'      => $resultClinicalCase,
            ];
        }
        return $data;
    }

}

