<?php
defined('BASEPATH') OR exit('No direct script access allowed'); 

class Read extends CI_Controller{
// for all records
	public function index(){
		if(!$this->session->userdata('logged_in'))
	    	{
	    		 $this->session->set_flashdata('msg', 'Username / Password Invalid');
	            redirect(base_url().'login');  
	    	}
	}
	public function userdata()
	{
		if(!$this->session->userdata('logged_in'))
	    	{
	    		 $this->session->set_flashdata('msg', 'Username / Password Invalid');
	            redirect(base_url().'login');  
	    	}
		$this->load->model('Read_Model');
		$config['base_url'] = base_url('read/userdata');        
			$config['total_rows'] = $this->Read_Model->num_row('tblclient');      
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
		$ColumnNames='FirstName,LastName,EmailId,ContactNumber,Address,PostingDate,client_id,creditdays';
		$tablename='tblclient';
		$results=$this->Read_Model->getdata($ColumnNames,$tablename,$config['per_page'],$this->uri->segment(3));
		 if(isset($_SESSION['error'])){
                    unset($_SESSION['error']);
                }
		$this->load->view('read',['result'=>$results]);
	}

	// for particular recod
	public function getdetails($pagename,$uid)
	{
		if(!$this->session->userdata('logged_in'))
	    	{
	    		 $this->session->set_flashdata('msg', 'Username / Password Invalid');
	            redirect(base_url().'login');  
	    	}
		if($pagename=='Clients')
		{
			$wherecolumnname='client_id';
			$selectcolumnnames='FirstName,LastName,EmailId,ContactNumber,Address,PostingDate,client_id,creditdays';
			$tablename='tblclient';
		}
		else if($pagename=='Suppliers')
		{
			$wherecolumnname='suppler_id';
			$selectcolumnnames='FirstName,LastName,EmailId,ContactNumber,Address,PostingDate,suppler_id';
			$tablename='tblsuppler';
		}
		$this->load->model('Read_Model');
		$reslt=$this->Read_Model->getuserdetail($wherecolumnname,$uid,$selectcolumnnames,$tablename);
		if($pagename=='Clients')
		{
			$this->load->view('update',['row'=>$reslt]);
		}
		else if($pagename=='Suppliers')
		{
			$this->load->view('supplierupdate',['row'=>$reslt]);
		}
	}

		function dbbackup()
			{
				if(!$this->session->userdata('logged_in'))
		    	{
		    		 $this->session->set_flashdata('msg', 'Username / Password Invalid');
		            redirect(base_url().'login');  
		    	}
			    $this->load->dbutil();   
			    $backup =& $this->dbutil->backup();  
			    $this->load->helper('file');
			    write_file('<?php echo base_url();?>/downloads', $backup);
			    $this->load->helper('download');
			    force_download('mybackup.gz', $backup);
			}

			function search($pagename)
			{
				if(!$this->session->userdata('logged_in'))
		    	{
		    		 $this->session->set_flashdata('msg', 'Username / Password Invalid');
		            redirect(base_url().'login');  
		    	}
				$this->form_validation->set_rules('search','Search','required|alpha');	
				if($this->form_validation->run())
				{
					$this->load->model('Read_Model');
					$name=$this->input->post('search');
					$id='0';
					if($pagename=='Clients')
					{
						$wherecolumnname='FirstName';
						$tablename='tblclient';
					}
					else if($pagename=='Suppliers')
					{
						$wherecolumnname='FirstName';
						$tablename='tblsuppler';
					}
				}
						
				$client=$this->Read_Model->searchget($tablename,$wherecolumnname,$name);
				$results=$client;
				if($pagename=='Clients')
				{
					$this->load->view('read',['result'=>$results]);
				}
				else if($pagename=='Suppliers')
				{
					$this->load->view('supplerlist',['result'=>$results]);
				}
				else
				{
					$results='';
					$this->session->set_flashdata('error', 'Please Enter the text into search box');
					if($pagename=='Clients')
					{
						$this->load->view('read',['result'=>$results]);
					}
					else if($pagename=='Suppliers')
					{
						$this->load->view('supplerlist',['result'=>$results]);
					}

				}
			}
			
}