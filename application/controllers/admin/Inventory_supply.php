<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inventory_supply extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model("admin/InventorySupply_model", "inventorysupply");
    }

    public function index()
    {
        $data = [
            'title'           => 'Supply',
            'medicines'       => $this->inventorysupply->getMedicineSupply(),
            'care_equipments' => $this->inventorysupply->getCareEquipmentSupply(),
            'office_supply'   => $this->inventorysupply->getOfficeSupply(),
        ];

        $this->load->view("admin/template/header", $data);
        $this->load->view("admin/inventory_supply/index", $data);
        $this->load->view("admin/template/footer");
        // echo json_encode($data);
    }

}

