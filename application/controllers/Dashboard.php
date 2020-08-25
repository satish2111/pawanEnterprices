<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	 public function __construct() {
        parent::__construct();

        //load the Dashboard Model
        $this->load->model('PurchaseModel', 'Purchase');
        $this->load->model('DashboardModel', 'Dashboard');
        $this->load->model('SaleModel', 'Sale');
    }
    public function index() {
    	
			if(!$this->session->userdata('logged_in'))
	    	{
	    		 $this->session->set_flashdata('msg', 'Username / Password Invalid');
	            redirect(base_url().'login');  
	    	}

             $this->load->model('Read_Model');
            $config['base_url'] = base_url('dashboard/index');        
            $config['total_rows'] = $this->Read_Model->num_row('tblmasterpurchase');      
            $config['per_page'] = 10;               
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
            $result=$this->Purchase->getdata($config['per_page'],$this->uri->segment(3));
            if(isset($_SESSION['error'])){
                    unset($_SESSION['error']);
                }
             $resultsale=$this->Sale->getdata($config['per_page'],$this->uri->segment(3));
            $final=array($result,$resultsale);
            $this->load->view('dashboard',['final'=>$final]);

    }

}