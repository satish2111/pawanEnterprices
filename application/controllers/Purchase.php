<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase extends CI_Controller {

	 public function __construct() {
        parent::__construct();

        //load the Purchase Model
        $this->load->model('PurchaseModel', 'Purchase');
    }
    public function index() {
        if(!$this->session->userdata('logged_in'))
            {
                 $this->session->set_flashdata('msg', 'Username / Password Invalid');
                redirect(base_url().'login');  
            }
            $this->load->model('Read_Model');
            $config['base_url'] = base_url('purchaselist');        
            $config['total_rows'] = $this->Read_Model->num_row('tblmasterpurchase');      
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
            $result=$this->Purchase->getdata($config['per_page'],$this->uri->segment(3));
            if(isset($_SESSION['error'])){
                    unset($_SESSION['error']);
                }
            $this->load->view('purchaselist',['result'=>$result]);
    }
    
    public function PurchaseAdd(){
        if(!$this->session->userdata('logged_in'))
            {
                 $this->session->set_flashdata('msg', 'Username / Password Invalid');
                redirect(base_url().'login');  
            }
    	$this->load->view('purchaseadd');
    }

    function addPurchase()
    {
        if(!$this->session->userdata('logged_in'))
            {
                 $this->session->set_flashdata('msg', 'Username / Password Invalid');
                redirect(base_url().'login');  
            }
            $purchasestockwise= $this->input->post('purchasestockwise');
            $totalbill=$this->input->post('totalbill');

            
            $status= $this->Purchase->insert($purchasestockwise,$totalbill);
            $this->output->set_content_type('application/json');
            echo json_encode(array('status'  => $status));  
    }

    function delete($id)
    {
        if(!$this->session->userdata('logged_in'))
            {
                 $this->session->set_flashdata('msg', 'Username / Password Invalid');
                redirect(base_url().'login');  
            }
            $status=$this->Purchase->delete($id);
            $this->output->set_content_type('application/json');
            echo json_encode(array('status'  => $status));  

    }
    function checkuser()
    {
        $textboxvalue=$this->input->post('textboxvalue');
        $status=$this->Purchase->checkuser($textboxvalue);
        echo json_encode(array('status'  => $status));  
    }
    function getdetails($id,$supplername)
    {
        if(!$this->session->userdata('logged_in'))
            {
                 $this->session->set_flashdata('msg', 'Username / Password Invalid');
                redirect(base_url().'login');  
            }
        $result=$this->Purchase->editdate($id,$supplername);
        $this->load->view('purchaseedit',['result'=>$result]);
    }
    function search()
    {
        if(!$this->session->userdata('logged_in'))
            {
                 $this->session->set_flashdata('msg', 'Username / Password Invalid');
                redirect(base_url().'login');  
            }
        $name=$this->input->post('search');

        if($name){
            $id=$this->Purchase->checkusergetid($name);
              if($id)
              {
                $final=($id[0]->suppler_id);
                $result=$this->Purchase->serachlist($final);
                $this->load->view('purchaselist',['result'=>$result]);
              }
              else{
                $result='';
                $this->session->set_flashdata('error', 'No Purchase Found On The Suppler Name');
                $this->load->view('purchaselist',['result'=>$result]);

              }
        }
    }
    function editPurchasedelete()
    {
        if(!$this->session->userdata('logged_in'))
            {
                 $this->session->set_flashdata('msg', 'Username / Password Invalid');
                redirect(base_url().'login');  
            }
            $totalbill=$this->input->post('totalbill');

            $status= $this->Purchase->editPurchasedelete($totalbill);
            $this->output->set_content_type('application/json');
            echo json_encode(array('status'  => $status)); 
    }
    function Purchasenew()
    {
        if(!$this->session->userdata('logged_in'))
            {
                 $this->session->set_flashdata('msg', 'Username / Password Invalid');
                redirect(base_url().'login');  
            }
         $purchasestockwise= $this->input->post('purchasestockwise');
         $totalbill=$this->input->post('totalbill');

         $status= $this->Purchase->insertandupdate($purchasestockwise,$totalbill);
         $this->output->set_content_type('application/json');
         echo json_encode(array('status'  => $status));  

    }


}