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
			from('tblmastersale p')->DISTINCT('s.client_id,s.FirstName')
				->join('tblclient s','p.client_id=s.client_id')
				->get();
			if($clientnamelist->num_rows()>0)
			{
				return $clientnamelist->result();
			}
	}
public function clientlist()
{
	$columnnames="client_id, CONCAT(FirstName,' ',LastName) as FirstName,creditdays";
		$tablename='tblclient';
	$this->db->order_by('FirstName','asc');
		$clientdata= $this->db->select($columnnames)->get($tablename);
		if($clientdata->num_rows()>0)
		{
			return $clientdata->result();
		}
}
public function productDatalist()
{
	$productnamelist= $this->db->select('DISTINCT(ProductName),SUM(Qty)as Qty,MRP')->where('Status','A')->from('tblpurchase')->group_by('ProductName,MRP')->get();

		if($productnamelist->num_rows()>0)
		{
			return $productnamelist->result();
		}
		else{
			$this->session->set_flashdata('error', 'No Qty Aavailable For Sale<br/> Please Add Purchase First!!!!');

		}
}
public function checkuser($clientname)
{
	
	$firtname=explode( ' ', $clientname );
	$firt=$firtname[0];
	$lastname=$firtname[1];
	$username=$this->db->select('client_id')->where('FirstName',$firt)->where('LastName',$lastname)->get('tblclient');
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
			return fail;
			
		}
	}
		public function reportprint($startdate, $enddate)
		{
			$clientReport=$this->db->query("SELECT Sale_id,DATE_FORMAT(Billdate,'%d-%m-%Y')as Billdate,DATE_FORMAT(createdate,'%d-%m-%Y') as DueDate,TotalAmt,PaidAmt,OutstandingAmt,CONCAT(c.FirstName,' ',c.LastName)as Name, c.Address,c.client_id FROM `tblmastersale` s join tblclient c on c.client_id=s.client_id where Billdate BETWEEN '".$startdate."' and '".$enddate."'")->result();

			if(isset($clientReport))
			{
				$returnData = (object) ['status' => 'success','clientreport'=>$clientReport];
			}
			return $returnData;
			//return $clientReport ;
		}
		public function  getlastbillno($clientID)
		{
			$billno=$this->db->query("SELECT max(s.Sale_id)as Billno,CONCAT(FirstName,' ',LastName) as Name from tblmastersale s join tblclient c on s.client_id=c.client_id where s.client_id='".$clientID."'")->result();
			if(isset($billno))
			{	
				$data = (object)['Billdetail'=>$billno[0]];
			}
			return $data;
		}
		public function editdate($totalbill)
		{
			$tabledata=$this->db->query("SELECT * from tblsale where Fk_Sale_id='".$totalbill."'")->result();
			$totalAmt =$this->db->query("SELECT DATE_FORMAT(Billdate,'%d-%m-%Y')as Billdate,DATE_FORMAT(createdate,'%d-%m-%Y')as DueDate,client_id,TotalAmt from tblmastersale where sale_id='".$totalbill."'")->result();
			$clientdetail= $this->db->query("SELECT CONCAT(FirstName,' ',LastName) as Name,creditdays,client_id FROM tblclient where client_id='".$totalAmt[0]->client_id."'")->result();
			$Amt=$this->db->query("SELECT max(Srno) as Totalitem,(SUM(Qty)+sum(Free)) as Totalqty FROM `tblsale` WHERE Fk_Sale_id='".$totalbill."'")->result();
			$FullData=(object)['tabledata'=>$tabledata,'totalAmt'=>$totalAmt[0],'clientdetail'=>$clientdetail[0],'amt'=>$Amt[0]];
			return($FullData);
		}

		public function editPurchasedelete($totalbill)
		{
			//print_r($totalbill);

			$fromarray=array();
			$fromarray['Status']='A';
			$fromarray['SaleBillNo']='';
			$this->db->where('SaleBillNo',$totalbill[0]['SaleBillNo']);
			$this->db->where('ProductName',$totalbill[0]['ProductName']);
			$result=$this->db->update('tblpurchase',$fromarray);
			if(!$result)
			{
				return 'fail-from-purchase-update';
			}
			else{
				$deletfromsale=$this->db->where('Fk_Sale_id', $totalbill[0]['SaleBillNo'])
				->where('ProductName',$totalbill[0]['ProductName'])->delete('tblsale');
				if(!$deletfromsale)
				{
					return 'fail-from-sale-Delete';
				}
				else{
					$dataArray=array();
					$dataArray['TotalAmt']=$totalbill[0]['gross'];
					$dataArray['AddBy']=$totalbill[0]['AddBy'];
					$this->db->where('Sale_id',$totalbill[0]['SaleBillNo']);
					$final=$this->db->update('tblmastersale',$dataArray);
					if(!$final)
					{
						return 'fail-after-sale-Delete-not-update';
					}
					else{
						return 'success-full';
					}

				}	
			}
		}
		public function billEdit($saletable,$totalbill)
		{
			for ($x=0; $x <count($saletable) ; $x++)
			{
			if($saletable[$x]['newsrno']!='')
				{
					$saleinsert[]=array(
					'ProductName'=>$saletable[$x]['ProductName'],
					'Qty'=>$saletable[$x]['Qty'],
					'Free'=>$saletable[$x]['Free'],
					'mrp'=>$saletable[$x]['mrp'],
					'productwisegross'=>$saletable[$x]['productwisegross'],
					'Fk_Sale_id'=>$saletable[$x]['Fk_Sale_id'],
				);
				}
			}
			if(!empty($saleinsert))
			{
				$query = $this->db->query("select max(Srno) from  tblsale where Fk_Sale_id='".$saletable[0]['Fk_Sale_id']."'");
				$row = $query->row_array();
				$maxid= $row['max(Srno)'];
				if($maxid)
				{
					$tempsrno=$maxid;
					for ($i=0; $i < count($saleinsert); $i++)
					{
							$saleinsert[$i]['Fk_Sale_id']=$saletable[0]['Fk_Sale_id'];
							$saleinsert[$i]['Srno']=$tempsrno;
							$tempsrno++;
							$finaldone=$this->db->insert('tblsale',$saleinsert[$i]);
							$forupdatesstatus=$saletable[$i]['Qty']+$saletable[$i]['Free'];
							for ($x=0; $x <$forupdatesstatus ; $x++)
							{
								$selectstock=$this->db->query("SELECT BIllno,min(srno)as srno from tblpurchase where ProductName='".$saleinsert[$i]['ProductName']."' and STATUS='A'");
								$rows = $selectstock->row_array();
								$fromarray=array();
								$fromarray['STATUS']='S';
								$fromarray['SaleBillNo']=$saletable[0]['Fk_Sale_id'];
								$this->db->where('Billno',$rows['BIllno']);
								$this->db->where('SrNo',$rows['srno']);
								$this->db->update('tblpurchase',$fromarray);
							}
						
					}
				}
				$dataArray=array();
				$dataArray['TotalAmt']=$totalbill[0]['TotalAmt'];
				$dataArray['AddBy']=$totalbill[0]['AddBy'];
				$this->db->where('Sale_id',$saletable[0]['Fk_Sale_id']);
				$final=$this->db->update('tblmastersale',$dataArray);
				if(!$final)
				{
					return 'fail-after-sale-Delete-not-update';
				}
				else
				{
					return 'success';
				}
			}
			else if(empty($saleinsert))
			{
				return 'success-only-Read';
			}
					
		}
}