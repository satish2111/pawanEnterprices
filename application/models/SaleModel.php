<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Calcutta');
class SaleModel extends CI_Model {
	public function getdata($limit,$offset)
		{
			$date1=date('Y-m-d');
			$end_date=date('Y-m-d',strtotime($date1 . "+1 days"));
			
			$this->db->select('c.client_id,c.FirstName,c.LastName,s.Sale_id,s.Billdate,s.TotalAmt,s.PaidAmt,s.lastpaiddate');
			$this->db->from('tblmastersale s');
			$this->db->join('tblclient c','s.client_id = c.client_id');
			$this->db->where('s.Billdate BETWEEN "'. date('Y-m-d'). '" and "'. date('Y-m-d', strtotime($end_date)).'"');
			$this->db->limit($limit,$offset);
				//print_r( $this->db->get()->result());
				return $this->db->get()->result();
		}
		public function getsearchdata($limit,$offset,$clinetname)
		{
			$this->db->select('c.client_id,c.FirstName,c.LastName,s.Sale_id,s.Billdate,s.TotalAmt,s.PaidAmt,s.lastpaiddate');
			$this->db->from('tblmastersale s');
			$this->db->join('tblclient c','s.client_id = c.client_id');
			$this->db->where('c.FirstName',$clinetname);
			$this->db->limit($limit,$offset);
				return $this->db->get()->result();
		}
	public function clientlistpurchase()
	{
		
		$clientnamelist= $this->db->select('s.client_id,s.FirstName')->
			from('tblmastersale p')
				->join('tblclient s','p.client_id=s.client_id')
				->get();
			if($clientnamelist->num_rows()>0)
			{
				return $clientnamelist->result();
			}
	}
public function clientlist()
{
	$columnnames='client_id,FirstName,creditdays';
		$tablename='tblclient';
	$this->db->order_by('FirstName','asc');
		$clientdata= $this->db->select($columnnames)->get($tablename);
		if($clientdata->num_rows()>0)
		{
			return $clientdata->result();
		}
}
public function productlist()
{
	$productnamelist= $this->db->select('DISTINCT(ProductName),SUM(Qty)as Qty,MRP')->where('Status','A')->from('tblpurchase')->group_by('ProductName')->get();
		if($productnamelist->num_rows()>0)
		{
			
			return $productnamelist->result();
		}
}
public function checkuser($clientname)
{
	$username=$this->db->select('client_id')->where('FirstName',$clientname)->get('tblclient');
			if($username->result())
			{
				return 'success';
			}
			else
			{
				return 'failed';
			}
}
public function checkprodutname($productName)
{
	$productname=$this->db->select('ProductName')->where('ProductName',$productName)->get('tblpurchase');
	if($productname->result())
			{
				return 'success';
			}
			else
			{
				return 'failed';
			}
}
public function insert($saletable,$totalbill)
{
	
	for ($x=0; $x <count($saletable) ; $x++)
		{
		$saleinsert[]=array(
			'ProductName'=>$saletable[$x]['ProductName'],
			'Qty'=>$saletable[$x]['Qty'],
			'Free'=>$saletable[$x]['Free'],
			'mrp'=>$saletable[$x]['mrp'],
			'productwisegross'=>$saletable[$x]['productwisegross'],
		);
		}
	try{
			$firstinsert=$this->db->insert('tblmastersale',$totalbill[0]);
			
			if($firstinsert)
			{
				
				$query = $this->db->query("select max(Sale_id) from  tblmastersale where Billdate='".$totalbill[0]['BillDate']."' and client_id='".$totalbill[0]['client_id']."' ");
				$row = $query->row_array();
				$maxid= $row['max(Sale_id)'];
				if($maxid)
				{
					$tempsrno=1;
					for ($i=0; $i < count($saleinsert); $i++)
					{
							$saleinsert[$i]['Fk_Sale_id']=$maxid;
							$saleinsert[$i]['Srno']=$tempsrno;
							$tempsrno++;
							$finaldone=$this->db->insert('tblsale',$saleinsert[$i]);
							$forupdatesstatus=$saletable[$i]['Qty']+$saletable[$i]['Free'];
							for ($x=0; $x <$forupdatesstatus ; $x++)
							{
								$selectstock=$this->db->query("SELECT BIllno,min(srno)as srno from tblpurchase where ProductName='".$saleinsert[$i]['ProductName']."' and STATUS='A'");
								$rows = $selectstock->row_array();
								// UPDATE tblpurchase SET STATUS='S',SaleBillNo='' where Billno='' and SrNo=''
								$fromarray=array();
								$fromarray['STATUS']='S';
								$fromarray['SaleBillNo']=$maxid;
								$this->db->where('Billno',$rows['BIllno']);
								$this->db->where('SrNo',$rows['srno']);
								$this->db->update('tblpurchase',$fromarray);
							}
					}
				}
				else{
					return 'failed';
				}
				return 'success';
			}
		}
		catch(Exception $e){
				return 'failed';
			}
}
	public function delete($id)
	{
		$sql_query=$this->db->where('Fk_Sale_id', $id) ->delete('tblsalepayment');
		if($sql_query)
		{
			$sql_querynew=$this->db->where('Fk_Sale_id', $id)->delete('tblsale');
			if($sql_querynew)
			{
				$sql_queryfinal=$this->db->where('Sale_id', $id)->delete('tblmastersale');
				if($sql_queryfinal)
				{
						$fromarray=array();
						$fromarray['STATUS']='A';
						$fromarray['SaleBillNo']=' ';
						$this->db->where('STATUS','S');
						$this->db->where('SaleBillNo',$id);
						$this->db->update('tblpurchase',$fromarray);
						return 'success';
				}
			}
		}
		else
		{
			$this->session->set_flashdata('error', 'Somthing went worng. Error!!');
			return 'failed';
		}
	}

	function billprint($billno)
	{
		$salemater=$this->db->query("SELECT Sale_id,client_id,TotalAmt,Billdate from tblmastersale where Sale_id='".$billno."'")->row_array();
		if(isset($salemater))
		{
			$userdetail=$this->db->query("SELECT CONCAT(FirstName,' ',LastName) as Party,Address FROM tblclient where client_id='".$salemater['client_id']."';")->row_array();
			if(isset($userdetail))
			{	
				$billdetail=$this->db->query("SELECT ProductName,Qty,Free,mrp,productwisegross FROM `tblsale` WHERE Fk_Sale_id='".$billno."'")->result();
				$finalbill=array($salemater,$userdetail,$billdetail);
				return $finalbill;
			}
		}
		else{
			print_r('no bill');
			exit();
		}

		
	}
}


