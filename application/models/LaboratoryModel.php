<?php

class LaboratoryModel extends CI_Model {
    public $labID;
	public $labName;
	public $labLocation;

	function __construct(){
        parent::__construct();
    }

    public function getLaboratoryList(){
    	$list = $this->db->get('laboratory');

    	return $list->result();
    }

    public function addLaboratory(){
    	$this->labName = $_POST['labName'];
    	$this->labLocation = $_POST['labLocation'];

    	return $this->db->insert('laboratory',$this);
    }

    public function deleteLab(){
        $labID = $_POST['labID'];

        $this->db->where('labID', $labID);

        return $this->db->delete('laboratory');
    }
}