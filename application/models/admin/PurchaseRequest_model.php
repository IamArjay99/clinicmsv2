<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PurchaseRequest_model extends CI_Model {

    public function __construct() 
    {
        parent::__construct();
    }

    public function savePurchaseRequest($data = [], $medicine = [], $careEquipment = [], $officeSupply = [])
    {
        $query = $this->db->insert("purchase_request", $data);
        if ($query) 
        {
            $stockInID = $this->db->insert_id();
            $code = generateCode("PR", $stockInID);
            $this->db->update("purchase_request", ["code" => $code], ["purchase_request_id" => $stockInID]);

            if ($medicine && !empty($medicine))
            {
                foreach ($medicine as $key => $m) $medicine[$key]["purchase_request_id"] = $stockInID;
                $this->db->insert_batch("purchase_request_medicine", $medicine);
            }

            if ($careEquipment && !empty($careEquipment))
            {
                foreach ($careEquipment as $key => $ce) $careEquipment[$key]["purchase_request_id"] = $stockInID;
                $this->db->insert_batch("purchase_request_care_equipment", $careEquipment);
            }

            if ($officeSupply && !empty($officeSupply))
            {
                foreach ($officeSupply as $key => $os) $officeSupply[$key]["purchase_request_id"] = $stockInID;
                $this->db->insert_batch("purchase_request_office_supply", $officeSupply);
            }

            return "true|Stock in saved successfully!";
        }
        return "false|There was an error saving stock in.";
    }

    public function getPurchaseRequestMedicine($stockInID = 0)
    {
        $sql = "
        SELECT 
            sim.*, m.name AS medicine_name, m.brand AS medicine_brand, u.name AS unit_name, m2.name AS measurement_name
        FROM purchase_request_medicine AS sim 
            LEFT JOIN medicines AS m USING(medicine_id)
            LEFT JOIN units AS u ON m.unit_id = u.unit_id
            LEFT JOIN measurements AS m2 ON m.measurement_id = m2.measurement_id 
        WHERE sim.purchase_request_id = $stockInID";
        $query = $this->db->query($sql);
        return $query ? $query->result_array() : [];
    }

    public function getPurchaseRequestCareEquipment($stockInID = 0)
    {
        $sql = "
        SELECT 
            sice.*, ce.name AS care_equipment_name, u.name AS unit_name 
        FROM purchase_request_care_equipment AS sice 
            LEFT JOIN care_equipments AS ce USING(care_equipment_id)
            LEFT JOIN units AS u ON ce.unit_id = u.unit_id
        WHERE sice.purchase_request_id = $stockInID";
        $query = $this->db->query($sql);
        return $query ? $query->result_array() : [];
    }

    public function getPurchaseRequestOfficeSupply($stockInID = 0)
    {
        $sql = "
        SELECT 
            sios.*, os.name AS office_supply_name, u.name AS unit_name 
        FROM purchase_request_office_supply AS sios 
            LEFT JOIN office_supply AS os USING(office_supply_id)
            LEFT JOIN units AS u ON os.unit_id = u.unit_id
        WHERE sios.purchase_request_id = $stockInID";
        $query = $this->db->query($sql);
        return $query ? $query->result_array() : [];
    }

    public function getPurchaseRequest($stockInID = 0)
    {
        $data = [];
        
        $sql    = "SELECT * FROM purchase_request WHERE purchase_request_id = $stockInID";
        $query  = $this->db->query($sql);
        $result = $query ? $query->row() : null;
        if ($result) 
        {
            $data = [
                'purchase_request_id' => $result->purchase_request_id,
                'code'                => $result->code,
                'reason'              => $result->reason,
                'medicine'            => $this->getPurchaseRequestMedicine($stockInID),
                'care_equipment'      => $this->getPurchaseRequestCareEquipment($stockInID),
                'office_supply'       => $this->getPurchaseRequestOfficeSupply($stockInID),
            ];
        }
        return $data;
    }

}

