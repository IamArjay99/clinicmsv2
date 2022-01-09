<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Record extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model("admin/Record_model", "record");
    }

    public function index()
    {
        $data['title'] = "Record";
        $this->load->view("admin/template/header", $data);
        $this->load->view("admin/record/index");
        $this->load->view("admin/template/footer");
    }

    public function view_medical()
    {
        $checkUpID   = $this->input->get("id");
        $medicalData = $this->record->getMedicalRecord($checkUpID);

        if ($medicalData && !empty($medicalData))
        {
            $data = [
                "title" => "View Medical Record",
                "data"  => $medicalData
            ];
            $this->load->view("admin/template/header", $data);
            $this->load->view("admin/record/view_medicine", $data);
            $this->load->view("admin/template/footer");
        }
        else 
        {
            redirect(base_url('admin/record'),'refresh');
        }

    }

}

