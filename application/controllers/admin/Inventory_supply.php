<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inventory_supply extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data['title'] = "Supply";
        $this->load->view("admin/template/header", $data);
        $this->load->view("admin/inventory_supply/index");
        $this->load->view("admin/template/footer");
    }

}

