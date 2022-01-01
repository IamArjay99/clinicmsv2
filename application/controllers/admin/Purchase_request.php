<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase_request extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model("admin/PurchaseRequest_model", "purchaserequest");
    }

    public function index()
    {
        $data['title'] = "Purchase Request";
        $this->load->view("admin/template/header", $data);
        $this->load->view("admin/purchase_request/index");
        $this->load->view("admin/template/footer");
    }

    public function add() 
    {
        $data['title'] = "Add Purchase Request";
        $this->load->view("admin/template/header", $data);
        $this->load->view("admin/purchase_request/add");
        $this->load->view("admin/template/footer");
    }

    public function view() 
    {
        $stockInID = $this->input->get("id");
        $data = [
            "title" => "View Purchase Request",
            "data"  => $this->purchaserequest->getPurchaseRequest($stockInID),
        ];
        $this->load->view("admin/template/header", $data);
        $this->load->view("admin/purchase_request/view", $data);
        $this->load->view("admin/template/footer");
    }

    public function savePurchaseRequest()
    {
        $purchase_request_id = $this->input->post("purchase_request_id");
        $reason              = $this->input->post("reason");
        $medicine            = $this->input->post("medicine");
        $careEquipment       = $this->input->post("careEquipment");
        $officeSupply        = $this->input->post("officeSupply");

        $data = [
            "purchase_request_id" => $purchase_request_id,
            "reason"              => $reason,
        ];

        $savePurchaseRequest = $this->purchaserequest->savePurchaseRequest($data, $medicine, $careEquipment, $officeSupply);
        echo json_encode($savePurchaseRequest);
    }

}

