<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Insert extends CI_Controller {
// For data insertion	
public function index(){
	if(!$this->session->userdata('logged_in'))
	    	{
	    		 $this->session->set_flashdata('msg', 'Username / Password Invalid');
	            redirect(base_url().'login');  
	    	}
	$this->load->view('insert');

}
public function suppler()
{
	$this->load->view('suppler');

}

/*-------------Userdata---------------------*/
public function InsertTblusers()
{
	if(!$this->session->userdata('logged_in'))
	{
	 $this->session->set_flashdata('msg', 'Username / Password Invalid');
	 redirect(base_url().'login');  
	}
	$this->form_validation->set_rules('firstname','First Name','required|alpha');	
	$this->form_validation->set_rules('lastname','Last Name','required|alpha');	
	$this->form_validation->set_rules('emailid','Email id','required|valid_email');
	$this->form_validation->set_rules('contactno','Contact Number','required|numeric|exact_length[10]');
	$this->form_validation->set_rules('creditdays','Credit days','required|numeric|min_length[1]|max_length[2]');
	$this->form_validation->set_rules('address','Address','required');	

	if($this->form_validation->run()){
	$fname=$this->input->post('firstname');
	$lname=$this->input->post('lastname');
	$email=$this->input->post('emailid');
	$cntno=$this->input->post('contactno');
	$adrss=$this->input->post('address');
	$creditdays=$this->input->post('creditdays');
	$data=array('FirstName'=>$fname,
				'LastName'=>$lname,
				'EmailId'=>$email,
				'ContactNumber'=>$cntno,
				'Address'=>$adrss,
				'creditdays'=>$creditdays,
				'AddBy'=> $this->session->id
			);
	$this->load->model('Insert_Model');
	$this->Insert_Model->insertdata('tblclient',$data);
	$this->load->view('insert');
	} else {
	$this->load->view('insert');
	}
}
/*-------------Userdata---------------------*/

/*-------------suppler---------------------*/

public function InsertSuppler()
{
	if(!$this->session->userdata('logged_in'))
	    	{
	    		 $this->session->set_flashdata('msg', 'Username / Password Invalid');
	            redirect(base_url().'login');  
	    	}
	$this->form_validation->set_rules('firstname','First Name','required|alpha');	
	$this->form_validation->set_rules('lastname','Last Name','required|alpha');	
	$this->form_validation->set_rules('emailid','Email id','valid_email');
	$this->form_validation->set_rules('contactno','Contact Number','numeric|exact_length[10]');

	$this->form_validation->set_rules('address','Address','required');	

	if($this->form_validation->run()){
	$fname=$this->input->post('firstname');
	$lname=$this->input->post('lastname');
	$email=$this->input->post('emailid');
	$cntno=$this->input->post('contactno');
	$adrss=$this->input->post('address');
	
	$data=array('FirstName'=>$fname,
				'LastName'=>$lname,
				'EmailId'=>$email,
				'ContactNumber'=>$cntno,
				'Address'=>$adrss,
				'AddBy'=> $this->session->id);
	$this->load->model('Insert_Model');
	$this->Insert_Model->insertdata('tblsuppler',$data);
	$this->load->view('supplerlist');
	} else {
	$this->load->view('suppler');
	}
}

/*-------------suppler---------------------*/


	// For data updation
	public function updatedetails($pagename){
		if(!$this->session->userdata('logged_in'))
	    	{
	    		 $this->session->set_flashdata('msg', 'Username / Password Invalid');
	            redirect(base_url().'login');  
	    	}
		$this->form_validation->set_rules('firstname','First Name','required|alpha');	
		$this->form_validation->set_rules('lastname','Last Name','required|alpha');	
		if($pagename=='Clients'){
			$this->form_validation->set_rules('emailid','Email id','required|valid_email');
			$this->form_validation->set_rules('contactno','Contact Number','required|numeric|exact_length[10]');
		}
		else if($pagename=='Suppliers'){
			$this->form_validation->set_rules('emailid','Email id','valid_email');
			$this->form_validation->set_rules('contactno','Contact Number','numeric|exact_length[10]');
		}
		if($pagename=='Clients'){
		$this->form_validation->set_rules('creditdays','Credit days','required|numeric|min_length[1]|max_length[2]');
		}
		$this->form_validation->set_rules('address','Address','required');	

		if($this->form_validation->run()){
		$fname=$this->input->post('firstname');
		$lname=$this->input->post('lastname');
		$email=$this->input->post('emailid');
		$cntno=$this->input->post('contactno');
		$adrss=$this->input->post('address');

		if($pagename=='Clients')
		{
			$creditdays=$this->input->post('creditdays');	
			$usid=$this->input->post('client_id');
			$columname='client_id';
			$tablename='tblclient';
		}
		else if($pagename=='Suppliers')
		{

			$usid=$this->input->post('suppler_id');
			$columname='suppler_id';
			$tablename='tblsuppler';
		}
		$this->load->model('Insert_Model');
		$this->Insert_Model->updatedetails($fname,$lname,$email,$cntno,$adrss,$usid,$creditdays,$columname,$tablename);
		} else {
		$this->session->set_flashdata('error', 'Somthing went worng. Try again with valid details !!!!');
			if($pagename=='Clients')
			{
				redirect('read/userdata');
			}
			else if($pagename=='Suppliers')
			{
				redirect('Suppler');
			}
		}
	}

}