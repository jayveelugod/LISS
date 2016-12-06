<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends CI_Controller {

	// Added by JV
	public function __construct(){
        parent::__construct();
        $this->load->helper('url');     
    }   

	public function index(){
		$this->load->model('LaboratoryModel');
		$data['laboratories'] = $this->LaboratoryModel->getLaboratoryList();
		$this->load->view('index', $data);
	}

	public function loadIframe($frame = null, $labID = null){
		$this->load->model('EquipmentModel');

		switch ($frame){
			case 'all': 
				$data['view'] = 'allLaboratories';
				$data['equipList'] = $this->EquipmentModel->getAllEquipments();
				$this->load->view('allLaboratories', $data); break;
			case 'lab': 
				$data['view'] = 'lab';
				$data['equipList'] = $this->EquipmentModel->getEquipmentList($labID);
				$this->load->view('lab', $data); break;
		}
	}

	public function addLaboratory(){
		$this->load->model('LaboratoryModel');
		$result = $this->LaboratoryModel->addLaboratory();

		header('Content-Type: application/json');
		echo json_encode($result);
	}

	public function deleteLab(){
		$this->load->model('LaboratoryModel');
		$result = $this->LaboratoryModel->deleteLab();

		header('Content-Type: application/json');
		echo json_encode($result);
	}

	// end
}
