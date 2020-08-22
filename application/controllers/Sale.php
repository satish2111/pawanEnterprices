<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Sale extends CI_Controller {

	 public function __construct() {
        parent::__construct();

        //load the Dashboard Model
        $this->load->model('SaleModel', 'sale');
    }
    public function index() {
    	if(!$this->session->userdata('logged_in'))
	    	{
	    		 $this->session->set_flashdata('msg', 'Username / Password Invalid');
	            redirect(base_url().'login');  
	    	}
            $this->load->model('Read_Model');
            $config['base_url'] = base_url('salelist');        
            $config['total_rows'] = $this->Read_Model->num_row('tblmastersale');      
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
            $result=$this->sale->getdata($config['per_page'],$this->uri->segment(3));
            if(isset($_SESSION['error'])){
                    unset($_SESSION['error']);
                }
            $this->load->view('salelist',['result'=>$result]);
    }

    public function search()
    {
          if(!$this->session->userdata('logged_in'))
            {
                 $this->session->set_flashdata('msg', 'Username / Password Invalid');
                redirect(base_url().'login');  
            }
             $this->load->model('Read_Model');
            $config['base_url'] = base_url('salelist');        
            $config['total_rows'] = $this->Read_Model->num_row('tblmastersale');      
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
            $clientname=$this->input->post('search');
            $result=$this->sale->getsearchdata($config['per_page'],$this->uri->segment(3),$clientname);
            $this->load->view('salelist',['result'=>$result]);
            
    }
    public function add()
    {
        if(!$this->session->userdata('logged_in'))
            {
                 $this->session->set_flashdata('msg', 'Username / Password Invalid');
                redirect(base_url().'login');  
            }
        $this->load->view('saleadd');

    }
    function checkuser()
    {
        $textboxvalue=$this->input->post('textboxvalue');
        $status=$this->sale->checkuser($textboxvalue);
        echo json_encode(array('status'  => $status)); 
    }
    function checkproductname()
    {
        $textboxvalue=$this->input->post('textboxvalue');
        $status=$this->sale->checkprodutname($textboxvalue);
        echo json_encode(array('status'  => $status)); 
    }

     public function addsale()
    {
        if(!$this->session->userdata('logged_in'))
            {
                 $this->session->set_flashdata('msg', 'Username / Password Invalid');
                redirect(base_url().'login');  
            }

            $saletable= $this->input->post('saletable');
            $totalbill=$this->input->post('totalbill');

            
            $status= $this->sale->insert($saletable,$totalbill);
            $this->output->set_content_type('application/json');
            echo json_encode(array('status'  => $status)); 
    }

    public function delete($id)
    {
        if(!$this->session->userdata('logged_in'))
            {
                 $this->session->set_flashdata('msg', 'Username / Password Invalid');
                redirect(base_url().'login');  
            }
            $status=$this->sale->delete($id);
            $this->output->set_content_type('application/json');
            echo json_encode(array('status'  => $status));
    }
    public function billprint($billno)
    {
        if(!$this->session->userdata('logged_in'))
            {
                 $this->session->set_flashdata('msg', 'Username / Password Invalid');
                redirect(base_url().'login');  
            }
            $result=$this->sale->billprint($billno);
            $this->load->view('billprint',['result'=>$result]);
    }
}