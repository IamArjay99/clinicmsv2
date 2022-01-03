<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class InventorySupply_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function getMedicineSupply()
    {
        $data = [];

        $sql = "
        SELECT 
            m.*, u.name AS unit_name, m2.name AS measurement_name 
        FROM medicines AS m
            LEFT JOIN units AS u USING(unit_id)
            LEFT JOIN measurements AS m2 USING(measurement_id) 
        WHERE m.is_deleted = 0";
        $query     = $this->db->query($sql);
        $medicines = $query ? $query->result_array() : [];

        if ($medicines && !empty($medicines))
        {
            foreach ($medicines as $key => $med) 
            {
                $remainingStocks  = 0;
                $medicine_id      = $med['medicine_id'];
                $name             = $med['name'];
                $brand            = $med['brand'];
                $unit_name        = $med['unit_name'];
                $measurement_name = $med['measurement_name'];

                $sql    = "SELECT quantity, remaining, batch, expiration FROM stock_in_medicine WHERE medicine_id = $medicine_id AND is_deleted = 0 AND remaining > 0";
                $query  = $this->db->query($sql);
                $items = $query ? $query->result_array() : [];

                $data[] = [
                    'medicine_id'      => $medicine_id,     
                    'name'             => $name,            
                    'brand'            => $brand,           
                    'unit_name'        => $unit_name,       
                    'measurement_name' => $measurement_name,
                    'items'            => $items
                ];
            }
        }

        return $data;
    }

    public function getCareEquipmentSupply()
    {
        $data = [];

        $sql = "
        SELECT 
            ce.*, u.name AS unit_name
        FROM care_equipments AS ce
            LEFT JOIN units AS u USING(unit_id)
        WHERE ce.is_deleted = 0";
        $query     = $this->db->query($sql);
        $care_equipments = $query ? $query->result_array() : [];

        if ($care_equipments && !empty($care_equipments))
        {
            foreach ($care_equipments as $key => $ce) 
            {
                $remainingStocks   = 0;
                $care_equipment_id = $ce['care_equipment_id'];
                $name              = $ce['name'];
                $unit_name         = $ce['unit_name'];

                $sql    = "SELECT quantity, remaining, batch, expiration FROM stock_in_care_equipment WHERE care_equipment_id = $care_equipment_id AND is_deleted = 0 AND remaining > 0";
                $query  = $this->db->query($sql);
                $items = $query ? $query->result_array() : [];

                $data[] = [
                    'care_equipment_id' => $care_equipment_id,     
                    'name'              => $name,            
                    'unit_name'         => $unit_name,      
                    'items'             => $items
                ];
            }
        }

        return $data;
    }

    public function getOfficeSupply()
    {
        $data = [];

        $sql = "
        SELECT 
            os.*, u.name AS unit_name
        FROM office_supply AS os
            LEFT JOIN units AS u USING(unit_id)
        WHERE os.is_deleted = 0";
        $query     = $this->db->query($sql);
        $office_supply = $query ? $query->result_array() : [];

        if ($office_supply && !empty($office_supply))
        {
            foreach ($office_supply as $key => $os) 
            {
                $office_supply_id = $os['office_supply_id'];
                $name             = $os['name'];
                $unit_name        = $os['unit_name'];

                $sql    = "SELECT quantity, remaining, batch, expiration FROM stock_in_office_supply WHERE office_supply_id = $office_supply_id AND is_deleted = 0 AND remaining > 0";
                $query  = $this->db->query($sql);
                $items  = $query ? $query->result_array() : [];

                $data[] = [
                    'office_supply_id' => $office_supply_id,     
                    'name'             => $name,            
                    'unit_name'        => $unit_name,       
                    'items'            => $items
                ];
            }
        }

        return $data;
    }

}

