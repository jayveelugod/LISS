<?php

class BorrowListModel extends CI_Model {
    

    public $borrowerIDNum;
    public $labID;
    public $compSerialNum;
    public $eqpSerialNum;
    public $teacher;
    public $inCharge;
    
	function __construct(){
        parent::__construct();
    }


    public function addBorrowedEquipments(){
        $return = array();
        foreach ($_POST['equipment'] as $equipment) {
           $this->borrowerIDNum = $_POST['borrowerID']; 
           $this->labID = $_POST['lab'];
           $this->eqpSerialNum = $equipment;
           $this->teacher = $_POST['bteacher'];
           $this->inCharge = $_POST['incharge'];
           $return[] = $this->db->insert('borrowed_list',$this);
        }
        return $return;
    }

    public function getBorrowedEquipments(){
        $result = array();
        $borrower = $_POST['borrower'];
        $labID = $_POST['labID'];

        $this->db->select('studentName');
        $this->db->from('student');
        $this->db->where('studentID', $borrower);
        $result[] = $this->db->get()->result_array();

        $this->db->select('B.borrowerIDNum, B.borrowedDate, E.eqpSerialNum, E.eqpName, S.studentName');
        $this->db->from('borrowed_list B');
        $this->db->join('equipment E', 'E.eqpSerialNum = B.eqpSerialNum', 'left');
        $this->db->join('student S', 'S.studentID = B.borrowerIDNum', 'left');
        $this->db->where('borrowerIDNum', $borrower);
        $this->db->where('E.labID', $labID);
        
        $result[] = $this->db->get()->result_array();

        return $result;   
    }

    public function returnEquipments(){
        $result = array();
        foreach ($_POST['equipment'] as $equipment) {
            $this->db->from('borrowed_list');
            $this->db->where('eqpSerialNum', $equipment);
            $result[] = $this->db->delete();    
        }
        return $result;
    }
}
