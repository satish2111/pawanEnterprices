<?php
defined('BASEPATH') OR exit('No direct script access allowed'); 
class Delete extends CI_Controller{
	public function index($uid)
	{
		if(!$this->session->userdata('logged_in'))
	    	{
	    		 $this->session->set_flashdata('msg', 'Username / Password Invalid');
	            redirect(base_url().'login');  
	    	}
		$this->load->model('Delete_Model');
		$this->Delete_Model->deleterow($uid);
		$this->load->view('read/userdata');
	}
}