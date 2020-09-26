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
            $offset = $this->input->get('per_page');
            if($offset == '' || !$offset) {
                $offset = 0;
            }
            $this->load->model('Read_Model');
            $baseurlpagination = base_url().'purchase/index';
            $config['base_url'] = $baseurlpagination;
            $config['total_rows'] = $this->Purchase->num_row('tblmasterpurchase');  
            $config['page_query_string'] = TRUE;  
            $config['reuse_query_string'] = true;    
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
            $result=$this->Purchase->getdata($config['per_page'],$offset);
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
        $name=$this->input->get('search');
        $offset = $this->input->get('per_page');
        if($offset == '' || !$offset) {
            $offset = 0;
        }

        if($name){
            $id=$this->Purchase->checkusergetid($name);
              if($id)
              {
                $final=($id[0]->suppler_id);
               
                if(isset($_SESSION['error'])){
                    unset($_SESSION['error']);
                }
                $baseurlpagination = base_url().'purchase/search';
                $config['base_url'] = $baseurlpagination;        
                $config['total_rows'] = $this->Purchase->num_row_search('tblmasterpurchase',$final);      
                $config['per_page'] = 10;      
                $config['page_query_string'] = TRUE;  
                $config['reuse_query_string'] = true;       
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
                $result=$this->Purchase->serachlist($final,$config['per_page'],$offset);
                $this->load->view('purchaselist',['result'=>$result]);
              }
              else{
                $result='';
                $this->session->set_flashdata('error', 'No Purchase Found On The Suppler Name');
                $this->load->view('purchaselist');

              }
        }
        else{
            $result='';
                $this->session->set_flashdata('error', 'Pls Enter Suppler Name');
                $this->load->view('purchaselist');
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
    public function stockreport()
    {
             if(!$this->session->userdata('logged_in'))
            {
                 $this->session->set_flashdata('msg', 'Username / Password Invalid');
                redirect(base_url().'login');  
            }
            $result=$this->Purchase->currentStock();
            //$this->load->view('stockreport');
             $this->load->view('stockreport',['result'=>$result]);
            
    }

    public function billPayment()
    {
        if(!$this->session->userdata('logged_in'))
            {
                 $this->session->set_flashdata('msg', 'Username / Password Invalid');
                redirect(base_url().'login');  
            }
            $totalbill=$this->input->post('billdetail'); 
            $status= $this->Purchase->payment($totalbill);
            $this->output->set_content_type('application/json');
            echo json_encode(array('status'  => $status)); 
    }
    public function purchasereport()
    {
        if(isset($_SESSION['error'])){
            unset($_SESSION['error']);
        }
        $this->load->view('purchasereport');
    }

    public function purchaseReportData()
    {
        $startdate=$this->input->post('startdate');
		$enddate=$this->input->post('enddate');
        $paidornot=$this->input->post('PaidUnPaid');
        if($startdate==$enddate)
        {
            $this->session->set_flashdata('error', "Start Date And End Date Should't Same ");
            $this->load->view('purchasereport');
        }
        else if($paidornot=='')
        {
            $this->session->set_flashdata('error', "Please select the Paid / Un-Paid Wise");
            $this->load->view('purchasereport');
        }
        else{
            if(isset($_SESSION['error'])){
                unset($_SESSION['error']);
            }
            $result=$this->Purchase->supplerReport($startdate,$enddate,$paidornot);
            $returnData =  ['paidornot' => $paidornot,'startdate'=>$startdate,'enddate'=>$enddate,'data'=>$result];
			
            $this->load->view('purchasereport',['returnData'=>$returnData]);
        }
    }

    public function purchaseFromToReport()
    {
        $this->load->view('PurchaseFormto');
    }
    public function reportpurchasefromto()
    {
        if(!$this->session->userdata('logged_in'))
        {
            $this->session->set_flashdata('msg', 'Username / Password Invalid');
            redirect(base_url().'login');  
        }
       $startdate=$this->input->post('startdate');
       $enddate=$this->input->post('enddate');
       if($startdate!=$enddate)
       {
            if(($this->input->post('supplername'))!='')
            {
                $supplername=$this->input->post('supplername');
                $result=$this->Purchase->reportPurchaseFromTo($startdate, $enddate,$supplername);
            }
            else{
                $supplername='';
                $result=$this->Purchase->reportPurchaseFromTo($startdate, $enddate,$supplername);
            }
            $returnData =  ['supplername' => $supplername,'startdate'=>$startdate,'enddate'=>$enddate,'result'=>$result];
            
            $this->load->view('PurchaseFormto',['returnData'=>$returnData]);
        }
        else{
            $this->session->set_flashdata('error', 'Please select from and to Date');
            $this->load->view('PurchaseFormto');
        }
    }

}