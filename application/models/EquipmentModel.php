<?php

class EquipmentModel extends CI_Model {

	public $eqpName;
	public $eqpSerialNum;
	public $price;
	function __construct(){
        parent::__construct();
    }

    // Added by JV
    public function getAllEquipments(){
        $this->db->select('count(*) as "quantity", eqpName');
        $this->db->from('equipment');
        $this->db->group_by('eqpName'); 

        return $this->db->get()->result_array();
    }

    public function loadAllEquipments(){
        $this->db->select('count(*) as "quantity", eqpName');
        $this->db->from('equipment');
        $this->db->group_by('eqpName'); 

        $list = $this->db->get()->result_array();

        $equipmentList = array();
        foreach ($list as $key) {
            $equipmentList[] = $key['eqpName'];
        }
        return $equipmentList;
    }

    public function addEquipment(){
    	$this->eqpName = $_POST['eqpName'];
    	$this->eqpSerialNum = $_POST['eqpSerialNum'];
    	$this->price = $_POST['eqpPrice'];

    	return $this->db->insert('equipment',$this);
    }

    public function getEquipmentList($labID){
    	$result = array();

        $this->db->select('*');
        $this->db->from('laboratory');
        $this->db->where('labID', $labID);
        $result[] = $this->db->get()->result_array();

        $this->db->select('eqp.*');
        $this->db->from('laboratory lab');
        $this->db->join('equipment eqp', 'eqp.labID = lab.labID', 'left');
        $this->db->where('eqp.labID', $labID);

        $result[] = $this->db->get()->result_array();

    	return $result;
    }

    public function getEquipments(){
        $filter = $_POST['search'];
        $labID = $_POST['labID'];

        $this->db->select('eqpSerialNum, eqpName');
        $this->db->from('equipment');

        if($filter == 'undamagedEquipments' || $filter == 'unborrowedEquipments'){
            $this->db->where('eqpSerialNum NOT IN (SELECT eqpSerialNum FROM borrowed_list) and eqpSerialNum NOT IN (SELECT eqpSerialNum FROM damaged_list)', NULL, FALSE);
        }

        $this->db->where('labID', $labID);
    	$list = $this->db->get()->result_array();
    	$equipmentList = array();
    	foreach ($list as $key) {
    		$equipmentList[] = $key['eqpSerialNum']." - ".$key['eqpName'];
    	}
    	return $equipmentList;
    }

    public function searchEquipment(){ 
        if($_POST['equipmentSerialNum'] != 'NULL'){
            $this->db->select('*');
            $searchThis = array('eqpSerialNum' => $_POST['equipmentSerialNum'], 'eqpName' => $_POST['equipmentName']);
        }else{
            $this->db->select('count(*) as "quantity", eqpName');
            $searchThis = array('eqpName' => $_POST['equipmentName']);
        }

        $this->db->from('equipment');     	
     	$this->db->where($searchThis);
    	$result = $this->db->get()->result_array();

		return $result;
    }

    public function getDamageEquipments(){
        $labID = $_POST['labID'];

        $this->db->select('*');
        $this->db->from('equipment');
        $this->db->where('labID', $labID);
        $this->db->where('eqpSerialNum NOT IN (SELECT eqpSerialNum FROM damaged_list) AND eqpSerialNum NOT IN (SELECT eqpSerialNum FROM borrowed_list)', NULL, FALSE);
        
        $result = $this->db->get()->result_array();

        return $result;
    }

    public function getEquipmentDetails(){
        $equipment = $_POST['equipmentSerialNum'];

        $this->db->select('*');
        $this->db->from('equipment');
        $this->db->where('eqpSerialNum', $equipment);
        
        $result = $this->db->get()->result_array();

        return $result;
    }

    public function updateEquipment(){
        $this->eqpName = $_POST['eqpName'];
        $this->eqpSerialNum = $_POST['eqpSerialNum'];
        $this->price = $_POST['eqpPrice'];

        $this->db->where('eqpSerialNum', $_POST['eqpSerialNum']);

        return $this->db->update('equipment', $this);
    }

    public function getBorrowEquipments(){
        $labID = $_POST['labID'];

        $this->db->select('*');
        $this->db->from('equipment');
        $this->db->where('labID', $labID);
        $this->db->where('eqpSerialNum NOT IN (SELECT eqpSerialNum FROM borrowed_list) AND eqpSerialNum NOT IN (SELECT eqpSerialNum FROM damaged_list)', NULL, FALSE);
        
        $result = $this->db->get()->result_array();

        return $result;
    }

    public function getEquipmentHistory(){
        $eqpSerial = $_POST['equipmentSerialNum'];
        $result = array();

        $this->db->select('D.dateReported, S.studentID, S.studentName');
        $this->db->from('damaged_list D');
        $this->db->join('student S', 'S.studentID = D.damagerIDNum', 'left');
        $this->db->where('D.eqpSerialNum', $eqpSerial);
        $result[] = $this->db->get()->result_array();

        $this->db->select('B.borrowedDate, S.studentID, S.studentName');
        $this->db->from('borrowed_list B');
        $this->db->join('student S', 'S.studentID = B.borrowerIDNum', 'left');
        $this->db->where('B.eqpSerialNum', $eqpSerial);
        $result[] = $this->db->get()->result_array();

        return $result;
    }
    // end
}
