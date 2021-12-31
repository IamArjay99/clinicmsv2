<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stock_in extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model("admin/StockIn_model", "stockin");
    }

    public function index()
    {
        $data['title'] = "Stock In";
        $this->load->view("admin/template/header", $data);
        $this->load->view("admin/stock_in/index");
        $this->load->view("admin/template/footer");
    }

    public function add() 
    {
        $data['title'] = "Stock In";
        $this->load->view("admin/template/header", $data);
        $this->load->view("admin/stock_in/add");
        $this->load->view("admin/template/footer");
    }

    public function saveStockIn()
    {
        $purchase_request_id = $this->input->post("purchase_request_id");
        $medicine      = $this->input->post("medicine");
        $careEquipment = $this->input->post("careEquipment");
        $officeSupply  = $this->input->post("officeSupply");

        $saveStockIn = $this->stockin->saveStockIn($purchase_request_id, $medicine, $careEquipment, $officeSupply);
        echo json_encode($saveStockIn);
    }

}

