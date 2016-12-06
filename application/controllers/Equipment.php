<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Equipment extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	function __construct() {
		parent::__construct();
		$this->load->model('EquipmentModel');
	}
	
	// Added by JV
	public function addEquipment(){
		$result = $this->EquipmentModel->addEquipment();

		header('Content-Type: application/json');
		echo json_encode($result);
	}

	public function getEquipments(){
		$result = $this->EquipmentModel->getEquipments();

		header('Content-Type: application/json');
		echo json_encode($result);
	}

	public function loadAllEquipments(){
		$result = $this->EquipmentModel->loadAllEquipments();

		header('Content-Type: application/json');
		echo json_encode($result);
	}

	public function searchEquipment(){
		$result = $this->EquipmentModel->searchEquipment();

		header('Content-Type: application/json');
		echo json_encode($result);
	}

	public function getAllEquipmentsList(){
		$result = $this->EquipmentModel->getAllEquipments();

		header('Content-Type: application/json');
		echo json_encode($result);	
	}

	public function getAllEquipments($id){
		$result = $this->EquipmentModel->getEquipmentList($id);

		header('Content-Type: application/json');
		echo json_encode($result);	
	}

	public function getDamageEquipments(){
		$result = $this->EquipmentModel->getDamageEquipments();

		header('Content-Type: application/json');
		echo json_encode($result);	
	}

	public function getEquipmentHistory(){
		$result = $this->EquipmentModel->getEquipmentHistory();

		header('Content-Type: application/json');
		echo json_encode($result);
	}

	public function getEquipmentDetails(){
		$result = $this->EquipmentModel->getEquipmentDetails();

		header('Content-Type: application/json');
		echo json_encode($result);	
	}

	public function updateEquipment(){
		$result = $this->EquipmentModel->updateEquipment();

		header('Content-Type: application/json');
		echo json_encode($result);	
	}

	public function getBorrowEquipments(){
		$result = $this->EquipmentModel->getBorrowEquipments();

		header('Content-Type: application/json');
		echo json_encode($result);	
	}
	// end
}
