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
            //$this->load->model('Read_Model');
            $config['base_url'] = base_url('sale/index');        
            $config['total_rows'] = $this->sale->num_row('tblmastersale');      
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
                $findon='';
                $count='';
                $finalvalue='';
                $productnamedata='';
                $clientnamedata='';
                $billno='';
                if(($this->input->get('search'))!='')
                {
                    $clientnamedata=$this->input->get('search');
                    $clientnameid=$this->input->get('selectclientid');
                    $findon='byclient';
                    $finalvalue=$clientnamedata;
                    $count=$this->sale->num_row_bycientname($clientnameid);      
                }
                else if(($this->input->get('productname'))!='')
                {
                    $productnamedata=$this->input->get('productname');
                    $findon='productname';
                    $finalvalue=$productnamedata;
                    $count=$this->sale->num_row_byproductname($productnamedata); 
                }
                else if(($this->input->get('billno'))!='')
                {
                    $billno=$this->input->get('billno');
                    $findon='Sale_id';
                    $finalvalue=$billno;
                }
                else {
                    $result='';
                    $this->session->set_flashdata('error', 'Something went worng. Try again with valid details !!!!');
                    $this->load->view('salelist',['result'=>$result]);
                    return;
                }
                $offset = $this->input->get('per_page');
                if($offset == '' || !$offset) {
                    $offset = 0;
                }
                
            // $this->load->model('Read_Model');
            $baseurlpagination = base_url().'sale/search';
            $config['base_url'] = $baseurlpagination;        
            $config['total_rows'] = $count;      
            $config['per_page'] = 5;
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
           
            if(isset($clientnamedata) || isset($productnamedata) || isset($billno) )
            {
                $clientname=array(
                    'clientnamedata'=> $clientnamedata,
                    'productnamedata'=> $productnamedata,
                    'billno'=>$billno
                );
                $result=$this->sale->getsearchdata($clientname,$config['per_page'],$offset);
                $this->load->view('salelist',['result'=>$result]);
            }
            else{
                $result='';
                $this->session->set_flashdata('error', 'Something went worng. Try again with valid details !!!!');
                $this->load->view('salelist',['result'=>$result]);
                }   
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
        $this->output->set_content_type('application/json');
        echo json_encode($status); 
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
    public function report()
    {
        
        if(!$this->session->userdata('logged_in'))
            {
                 $this->session->set_flashdata('msg', 'Username / Password Invalid');
                redirect(base_url().'login');  
            }
      
        $this->load->view('report');
    }

    public function reportdata()
    {
        if(!$this->session->userdata('logged_in'))

        {
            $this->session->set_flashdata('msg', 'Username / Password Invalid');
            redirect(base_url().'login');  
        }
       $startdate=$this->input->post('startdate');
       $enddate=$this->input->post('enddate');
       $paidornot=$this->input->post('paidornot');
       $returnData=$this->sale->reportprint($startdate, $enddate,$paidornot);

       $this->output->set_content_type('application/json');
        echo json_encode($returnData); 
    }
    public function getmaxbillno()
    {
        if(!$this->session->userdata('logged_in'))
            {
                 $this->session->set_flashdata('msg', 'Username / Password Invalid');
                redirect(base_url().'login');  
            }
            $clientID= $this->input->post('clientID');
            $billno=$this->sale->getlastbillno($clientID);
            $this->output->set_content_type('application/json');
            echo json_encode($billno);  


    }
    public function getdetails($billno)
    {
        if(!$this->session->userdata('logged_in'))
        {
             $this->session->set_flashdata('msg', 'Username / Password Invalid');
            redirect(base_url().'login');  
        }
    $result=$this->sale->editdate($billno);
    $this->load->view('saleedit',['result'=>$result]);
    }

    public function editPurchasedelete()
    {
           if(!$this->session->userdata('logged_in'))
            {
                 $this->session->set_flashdata('msg', 'Username / Password Invalid');
                redirect(base_url().'login');  
            }
            $totalbill=$this->input->post('totalbill');

            $status= $this->sale->editPurchasedelete($totalbill);
            $this->output->set_content_type('application/json');
            echo json_encode(array('status'  => $status)); 
    }

    public function saleedit()
    {
            if(!$this->session->userdata('logged_in'))
            {
                $this->session->set_flashdata('msg', 'Username / Password Invalid');
                redirect(base_url().'login');  
            }
            $saletable= $this->input->post('saletable');
            $totalbill=$this->input->post('totalbill');

            $status= $this->sale->billEdit($saletable,$totalbill);
            $this->output->set_content_type('application/json');
            echo json_encode(array('status'  => $status));
    }
    public function reportsalepurchase()
    {
        if(!$this->session->userdata('logged_in'))

        {
            $this->session->set_flashdata('msg', 'Username / Password Invalid');
            redirect(base_url().'login');  
        }
       $startdate=$this->input->post('startdate');
       $enddate=$this->input->post('enddate');
      
       $returnData=$this->sale->reportsalepurchase($startdate, $enddate);

       $this->output->set_content_type('application/json');
        echo json_encode($returnData);  
    }

    public function billPayment()
    {
        if(!$this->session->userdata('logged_in'))
        {
             $this->session->set_flashdata('msg', 'Username / Password Invalid');
            redirect(base_url().'login');  
        }
        $totalbill=$this->input->post('billdetail'); 
        $status= $this->sale->payment($totalbill);
        $this->output->set_content_type('application/json');
        echo json_encode(array('status'  => $status)); 
    }

    public function Details()
    {
        if(!$this->session->userdata('logged_in'))
        {
            $this->session->set_flashdata('msg', 'Username / Password Invalid');
            redirect(base_url().'login');  
        }
        $this->load->view('partydetailwisereport');
    }

    public function partyWiseDetail()
    {
        if(!$this->session->userdata('logged_in'))
        {
            $this->session->set_flashdata('msg', 'Username / Password Invalid');
            redirect(base_url().'login');  
        }
        $startdate=$this->input->post('stardate');
		$enddate=$this->input->post('enddate');
        $client=$this->input->post('Client');
        if($startdate==$enddate)
        {   
            $this->session->set_flashdata('error', "Start Date And End Date Should't Same ");
            $this->load->view('partydetailwisereport');
        }
        else if($client=='')
        {   
            $this->session->set_flashdata('error', "Please select the Client Name");
            $this->load->view('partydetailwisereport');
        }
        else{

            if(isset($_SESSION['error'])){
                unset($_SESSION['error']);
            }
            $result=$this->sale->partyDetailReport($startdate,$enddate,$client);
            $returnData =  ['client' => $client,'startdate'=>$startdate,'enddate'=>$enddate,'data'=>$result];
			
            $this->load->view('partydetailwisereport',['returnData'=>$returnData]);
        }
    }
    public function saleFromTOReport()
    {
        if(!$this->session->userdata('logged_in'))
        {
            $this->session->set_flashdata('msg', 'Username / Password Invalid');
            redirect(base_url().'login');  
        }
        $this->load->view('saleFromTo');

    }

    public function reportsaleFromTo()
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
            if(($this->input->post('clientid'))!='')
            {
                $clientname=$this->input->post('tempclientname');
                $clientid=$this->input->post('clientid');
                $result=$this->sale->reportSaleFromTo($startdate, $enddate,$clientid);
            }
            else{
                $clientname='';
                $clientid='';
                $result=$this->sale->reportSaleFromTo($startdate, $enddate,$clientid);
            }
            if(isset($_SESSION['error'])){
                unset($_SESSION['error']);
            }
            $returnData =  ['clientname' => $clientname,'startdate'=>$startdate,'enddate'=>$enddate,'result'=>$result];
           // print_r($returnData);
            $this->load->view('saleFromTo',['returnData'=>$returnData]);
        }
        else{
            $this->session->set_flashdata('error', 'Please select from and to Date');
            $this->load->view('saleFromTo');
        }
    }
    public function billdetail($client_id,$billno,$billdate)
    {    
        if(!$this->session->userdata('logged_in'))
        {
            $this->session->set_flashdata('msg', 'Username / Password Invalid');
            redirect(base_url().'login');  
        }
        $dataresult=$this->sale->billdetail($client_id,$billno,$billdate);
        $this->output->set_content_type('application/json');
        echo json_encode(array('status'  => $dataresult)); 
        //$this->load->view('saleFromTo',$dataresult);

    }
    public function missingbill()
    {
        if(!$this->session->userdata('logged_in'))
        {
            $this->session->set_flashdata('msg', 'Username / Password Invalid');
            redirect(base_url().'login');  
        }
        $result=$this->sale->billNumberMissing();
        $this->load->view('missingbillno',['result'=>$result]);
    }

    public function compared()
    {
        if(!$this->session->userdata('logged_in'))
        {
            $this->session->set_flashdata('msg', 'Username / Password Invalid');
            redirect(base_url().'login');  
        }
        $result=$this->sale->stocksalecompared();
        $this->load->view('stockcompared',['result'=>$result]);
    }
}