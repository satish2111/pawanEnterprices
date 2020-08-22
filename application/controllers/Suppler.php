<?php
defined('BASEPATH') OR exit('No direct script access allowed'); 

class Suppler extends CI_Controller{
// for all records
	public function index(){
		if(!$this->session->userdata('logged_in'))
	    	{
	    		 $this->session->set_flashdata('msg', 'Username / Password Invalid');
	            redirect(base_url().'login');  
	    	}
			$this->load->model('Read_Model');
			
			$config['base_url'] = base_url('read/userdata');        
			$config['total_rows'] = $this->Read_Model->num_row('tblsuppler');      
			$config['per_page'] = 5;               
			$config['full_tag_open'] = '<ul class="pagination">';        
			$config['full_tag_close'] = '</ul>';  
			$config['attributes'] = array('class' => 'page-link');   
			$config['first_link'] = 'First';        
			$config['last_link'] = 'Last';   
			$config['first_tag_open'] = '<li>';        
			$config['first_tag_close'] = '</li>';        
			$config['prev_link'] = '&laquo';        
			$config['prev_tag_open'] = '<li class="prev">';        
			$config['prev_tag_close'] = '</li>';        
			$config['next_link'] = '&raquo';        
			$config['next_tag_open'] = '<li>';        
			$config['next_tag_close'] = '</li>';        
			$config['last_tag_open'] = '<li>';        
			$config['last_tag_close'] = '</li>';        
			$config['cur_tag_open'] = '<li class="page-item active"><a href="#" class="page-link">';
			$config['cur_tag_close'] = '<span class="sr-only">(current)</span></a></li>';        
			$config['num_tag_open'] = '<li>';
			$config['num_tag_close'] = '</li>';
			$this->pagination->initialize($config);
			$ColumnNames='FirstName,LastName,EmailId,ContactNumber,Address,PostingDate,suppler_id';
			$tablename='tblsuppler';
			$results=$this->Read_Model->getdata($ColumnNames,$tablename,$config['per_page'],$this->uri->segment(3));
			 if(isset($_SESSION['error'])){
                    unset($_SESSION['error']);
                }
			$this->load->view('supplerlist',['result'=>$results]);
		// $data=array();
		// $data['client']=$client;
		
		
		//$this->load->view('client/list',$data);

		// $ColumnNames='FirstName,LastName,EmailId,ContactNumber,Address,PostingDate,suppler_id';
		// $tablename='tblsuppler';
		// $results=$this->Read_Model->getdata($ColumnNames,$tablename);

		// $this->load->view('supplerlist',['result'=>$results]);
	}

	public function delete($uid)
	{
		if(!$this->session->userdata('logged_in'))
	    	{
	    		 $this->session->set_flashdata('msg', 'Username / Password Invalid');
	            redirect(base_url().'login');  
	    	}
		$this->load->model('Suppler_Delete_Model');
		$this->Suppler_Delete_Model->deleterow($uid);
		$this->load->view('supplerlist');
	}

	
}