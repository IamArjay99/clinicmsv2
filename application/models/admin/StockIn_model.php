<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class StockIn_model extends CI_Model {

    public function __construct() 
    {
        parent::__construct();
    }

    public function saveStockIn($purchaseRequestID = 0, $medicine = [], $careEquipment = [], $officeSupply = [])
    {
        $query = $this->db->insert("stock_in", ["purchase_request_id" => $purchaseRequestID]);
        if ($query) 
        {
            $stockInID = $this->db->insert_id();

            if ($medicine && !empty($medicine))
            {
                foreach ($medicine as $key => $m) $medicine[$key]["stock_in_id"] = $stockInID;
                $this->db->insert_batch("stock_in_medicine", $medicine);
            }

            if ($careEquipment && !empty($careEquipment))
            {
                foreach ($careEquipment as $key => $ce) $careEquipment[$key]["stock_in_id"] = $stockInID;
                $this->db->insert_batch("stock_in_care_equipment", $careEquipment);
            }

            if ($officeSupply && !empty($officeSupply))
            {
                foreach ($officeSupply as $key => $os) $officeSupply[$key]["stock_in_id"] = $stockInID;
                $this->db->insert_batch("stock_in_office_supply", $officeSupply);
            }

            return "true|Stock in saved successfully!";
        }
        return "false|There was an error saving stock in.";
    }

}

