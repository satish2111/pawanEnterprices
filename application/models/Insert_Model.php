<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Calcutta');
class Insert_Model extends CI_Model {

public function insertdata($TableName, $data){
$sql_query=$this->db->insert($TableName,$data);
if($sql_query){
$this->session->set_flashdata('success', 'Registration successful');
		if($TableName=='tblclient'){redirect('read/userdata');}
		else if($TableName=='tblsuppler'){redirect('suppler');}
		
	}
	else{
		$this->session->set_flashdata('error', 'Somthing went worng. Error!!');
		if($TableName=='tblclient'){redirect('read/userdata');}
		else if($TableName=='tblsuppler'){redirect('suppler');}
	}
	}


public function updatedetails($fname,$lname,$email,$cntno,$adrss,$usid,$creditdays,$columname,$tablename){

$data=array(
			'FirstName'=>$fname,
			'LastName'=>$lname,
			'EmailId'=>$email,
			'ContactNumber'=>$cntno,
			'Address'=>$adrss,
			'AddBy'=> $this->session->id,
			
		);
		if($tablename=='tblclient')
		{
			$data['creditdays']=$creditdays;
		}
		$sql_query=$this->db->where($columname, $usid)
                ->update($tablename, $data); 

           if($sql_query){
			$this->session->set_flashdata('success', 'Record updated successful');

			if($tablename=='tblclient'){redirect('read/userdata');}
			else if($tablename=='tblsuppler'){redirect('Suppler');}
			}
	else{
		$this->session->set_flashdata('error', 'Somthing went worng. Error!!');
		if($tablename=='tblclient'){redirect('read/userdata');}
			else if($tablename=='tblsuppler'){redirect('Suppler');}
	}

}


	







}