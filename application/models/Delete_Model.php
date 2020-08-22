<?php 

defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Calcutta');
class Delete_Model extends CI_Model {


	public function deleterow($uid)
	{
		$sql_query=$this->db->where('client_id', $uid)
		                ->delete('tblclient'); 
		           if($sql_query){
		$this->session->set_flashdata('success', 'Record delete successfully');
				redirect('read/userdata');
			}
			else{
				$this->session->set_flashdata('error', 'Somthing went worng. Error!!');
				redirect('read/userdata');
			}

	}
}