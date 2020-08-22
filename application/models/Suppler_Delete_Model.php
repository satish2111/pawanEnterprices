<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Calcutta');
class Suppler_Delete_Model extends CI_Model {

	public function deleterow($uid)
	{
		$sql_query=$this->db->where('suppler_id', $uid)
		                ->delete('tblsuppler'); 
		           if($sql_query){
		$this->session->set_flashdata('success', 'Record delete successfully');
				redirect('suppler');
			}
			else{
				$this->session->set_flashdata('error', 'Somthing went worng. Error!!');
				redirect('suppler');
			}
	}
	
}