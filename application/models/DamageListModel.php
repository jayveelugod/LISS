<?php

class DamageListModel extends CI_Model {
    

    public $damagerIDNum;
    public $labID;
    public $compSerialNum;
    public $eqpSerialNum;
    public $teacher;
    
	function __construct(){
        parent::__construct();
    }


    public function addDamageEquipments(){
        $return = array();
        foreach ($_POST['equipment'] as $equipment) {
           $this->damagerIDNum = $_POST['damagerID']; 
           $this->labID = $_POST['lab'];
           $this->eqpSerialNum = $equipment;
           $this->teacher = $_POST['damagerTeacher'];
           $return[] = $this->db->insert('damaged_list',$this);
        }
        return $return;
    }
}
