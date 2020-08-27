<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Calcutta');
class PurchaseModel extends CI_Model {
 
    
	public function Supplerlist($columnnames,$tablename)
	{
		$this->db->order_by('FirstName','asc');
		$supplerdata= $this->db->select($columnnames)->get($tablename);
		if($supplerdata->num_rows()>0)
		{
			return $supplerdata->result();
		}
	}

	public function Supplerlistpurchase()
	{
		$supplernamelist= $this->db->select('DISTINCT(s.suppler_id),s.FirstName ')->
		from('tblmasterpurchase p')
			->join('tblsuppler s','p.suppler_id=s.suppler_id')
			->get();
		if($supplernamelist->num_rows()>0)
		{
			// print_r( $supplernamelist->result());
			return $supplernamelist->result();
		}
	}

		public function getdata($limit,$offset)
		{
			$date1=date('Y-m-d');
			$end_date=date('Y-m-d',strtotime($date1 . "+1 days"));
			
			$this->db->select('s.suppler_id,s.FirstName,p.pur_id,p.billno,p.billdate,p.total_amt,p.amt_paid,p.paiddate,p.paymentmode');
			$this->db->from('tblmasterpurchase p');
			$this->db->join('tblsuppler s','p.suppler_id=s.suppler_id');
			$this->db->where('p.PostingDate BETWEEN "'. date('Y-m-d'). '" and "'. date('Y-m-d', strtotime($end_date)).'"');
			$this->db->limit($limit,$offset);
			return $this->db->get()->result();	
		}
		public function num_row($tablename)
		{
			$date1=date('Y-m-d');
				$end_date=date('Y-m-d',strtotime($date1 . "+1 days"));
			return $totalRow=$this->db->where('Billdate BETWEEN "'. date('Y-m-d'). '" and "'. date('Y-m-d', strtotime($end_date)).'"')->get($tablename)->num_rows();

		}

	function insert($purchasestockwise,$totalbill)
	{
		
		for($i=0; $i<count($totalbill);$i++)
		{
			$billtotal[]=array(
				'suppler_id'=>$totalbill[$i]['suppler_id'],
				'BillNo'=>$totalbill[$i]['BillNo'],
				'BillDate'=>$totalbill[$i]['BillDate'],
				'Total_Amt'=>$totalbill[$i]['Total_Amt'],
				'AddBy'=>$totalbill[$i]['AddBy'],

			);
			$tempsuppler_id=$totalbill[$i]['suppler_id'];
			$tempbillno=$totalbill[$i]['BillNo'];
			$tempbilldate=$totalbill[$i]['BillDate'];
		}
		for ($x=0; $x <count($purchasestockwise) ; $x++) 
		{
		 $purchas[]=array(
		 	'BillDate'=>$purchasestockwise[$x]['BillDate'],
		 	'BillNo'=>$purchasestockwise[$x]['BillNo'],
		 	'SrNo'=>$purchasestockwise[$x]['srno'],
		 	'suppler_id'=>$purchasestockwise[$x]['suppler_id'],
		 	'ProductName'=>$purchasestockwise[$x]['ProductName'],
		 	'Qty'=>$purchasestockwise[$x]['Qty'],
		 	'Cost'=>$purchasestockwise[$x]['Cost'],
		 	'MRP'=>$purchasestockwise[$x]['MRP'],
		 	'Status'=>$purchasestockwise[$x]['Status'],
		 	'AddBy'=>$purchasestockwise[$x]['AddBy'],
		 );
		}

		try{
			$firstinsert=$this->db->insert('tblmasterpurchase',$billtotal[0]);
			if($firstinsert){
				$query = $this->db->query("select max(Pur_Id) from  tblmasterpurchase where  suppler_id='".$tempsuppler_id."' and BillNo='".$tempbillno."' and BillDate='".$tempbilldate."' ");
				$row = $query->row_array();
				$maxid= $row['max(Pur_Id)'];
				 if($maxid)
				 {
				 	$tempsrno=1;
				 	if($purchas)
				 	{
					 	for ($fullloop=0; $fullloop <count($purchas) ; $fullloop++) 
					 	{ 
					 		$tempfullrow=$purchasestockwise[$fullloop];

					 		for ($half=0; $half < $purchasestockwise[$fullloop]['Qty']; $half++) 
					 		{
					 			$tempfullrow['Pur_fk_Id']=$maxid;
					 			$tempfullrow['Qty']=1;
					 			$tempfullrow['srno']=$tempsrno;
					 			$tempsrno++;
					 		 	$finaldone=$this->db->insert('tblpurchase',$tempfullrow);
					 		}
					 	}
				 	}
				 	else
					{
								return 'success-only-delete';
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

	function delete($id)
	{
$checkstatus=$this->db->select('Pur_fk_Id')->where('Status','S')->where('Pur_fk_Id',$id)->get('tblpurchase');
if($checkstatus->num_rows()>0)
{
	return 'sales';
}
else{
		$sql_query=$this->db->where('Pur_fk_Id', $id)
		                ->delete('tblpurchase'); 
		           if($sql_query)
		           {
			           	$sql_querynew=$this->db->where('Pur_Id', $id)
			                ->delete('tblmasterpurchase'); 
						if($sql_querynew)
						{
							$this->session->set_flashdata('success', 'Record delete successfully');
						 	return 'success';
						}
					}	
			else{
					$this->session->set_flashdata('error', 'Somthing went worng. Error!!');
				 	return 'failed';
				}
			}
	}

	function checkuser($supplername)
	{
		$username=$this->db->select('suppler_id')->where('FirstName',$supplername)->get('tblsuppler');
			if($username->result())
			{
				return 'success';
			}
			else
			{
			 	return 'failed';
			}
	}
	function checkusergetid($supplername)
	{
		$username=$this->db->select('suppler_id')->where('FirstName',$supplername)->get('tblsuppler');
			return $username->result();
	}

	function editdate($id,$supplername)
	{

		$this->db->select('s.suppler_id,s.FirstName,p.pur_id,p.billno,p.billdate,p.total_amt,p.amt_paid,p.paiddate,p.paymentmode');
			$this->db->from('tblmasterpurchase p');
			$this->db->join('tblsuppler s','p.suppler_id=s.suppler_id');
			$this->db->where('p.pur_id',$id);
			
			$first=$this->db->get()->result();	
			$this->db->select('DISTINCT (productname),sum(Qty) as Qty,Cost,MRP,(Sum(qty)*cost)as gross,Pur_fk_Id ,Status')->from('tblpurchase')->where('Pur_fk_Id',$id)->group_by('productname,Cost,MRP,Pur_fk_Id,Status');
			//->where('Status',"A") Status
			$sceond=$this->db->get()->result();
			
			
			$full=array($first,$sceond);
			//var_dump($full);
			return $full;	
	}
	function serachlist($id,$limit,$offset)
	{
		$this->db->select('s.suppler_id,s.FirstName,p.pur_id,p.billno,p.billdate,p.total_amt,p.amt_paid,p.paiddate,p.paymentmode');
			$this->db->from('tblmasterpurchase p');
			$this->db->join('tblsuppler s','p.suppler_id=s.suppler_id');
			$this->db->where('p.suppler_id',$id);
			$this->db->limit($limit,$offset);
			return $this->db->get()->result();	
	}
	function editPurchasedelete($totalbill)
	{
		//$totalbill[0]['AddBy']

			$sql_query=$this->db->where('suppler_id', $totalbill[0]['suppler_id'])->where('Billno',$totalbill[0]['BillNo'])->where('BillDate',$totalbill[0]['BillDate'])->where('ProductName',$totalbill[0]['productname'])->where('Pur_fk_Id',$totalbill[0]['Pur_fk_Id'])->where('Status','A')->delete('tblpurchase'); 

		  if($sql_query)
			{
				$sumAmt=$this->db->select('sum(Cost) as gross,count(qty)as qty')
						->where('Pur_fk_Id',$totalbill[0]['Pur_fk_Id'])
						->where('suppler_id',$totalbill[0]['suppler_id'])
						 ->where('BillNo',$totalbill[0]['BillNo'])
						->where('BillDate',$totalbill[0]['BillDate'])
						->from('tblpurchase')
						->get()->result();
				
				if($sumAmt)
				{
					$finalsum=$sumAmt[0]->gross;
					$sumqty=$sumAmt[0]->qty;
					if($finalsum==0 && $sumqty==0)
					{
						$tblmasterpurchase=$this->db->where('suppler_id', $totalbill[0]['suppler_id'])->where('Billno',$totalbill[0]['BillNo'])->where('BillDate',$totalbill[0]['BillDate'])->where('Pur_Id',$totalbill[0]['Pur_fk_Id'])->delete('tblmasterpurchase');
						return 'success-full';
					}
					else
					{
						$fromarray=array();
						$fromarray['AddBy']=$totalbill[0]['AddBy'];
						$fromarray['Total_Amt']=$sumAmt[0]->gross;
						$this->db->where('Pur_Id',$totalbill[0]['Pur_fk_Id']);
						$this->db->update('tblmasterpurchase',$fromarray);
						return 'success-full';
					}
				}
			}
			else{
				return 'failed';
			}
	}

	function insertandupdate($purchasestockwise,$totalbill)
	{
		//print_r($totalbill);
		
		for($i=0; $i<count($totalbill);$i++)
		{
			$billtotal[]=array(
				'suppler_id'=>$totalbill[$i]['suppler_id'],
				'BillNo'=>$totalbill[$i]['BillNo'],
				'BillDate'=>$totalbill[$i]['BillDate'],
				'Total_Amt'=>$totalbill[$i]['Total_Amt'],
				'AddBy'=>$totalbill[$i]['AddBy'],

			);
			$tempsuppler_id=$totalbill[$i]['suppler_id'];
			$tempbillno=$totalbill[$i]['BillNo'];
			$tempbilldate=$totalbill[$i]['BillDate'];
			
			
		}
		$temppk=($totalbill[0]['Pur_Id']);
		for ($x=0; $x <count($purchasestockwise) ; $x++) 
		{

			if($purchasestockwise[$x]['newsrno']!='')
			{
			    $purchas[]=array(
			 	'BillDate'=>$purchasestockwise[$x]['BillDate'],
			 	'BillNo'=>$purchasestockwise[$x]['BillNo'],
			 	//'SrNo'=>$purchasestockwise[$x]['srno'],
			 	'suppler_id'=>$purchasestockwise[$x]['suppler_id'],
			 	'ProductName'=>$purchasestockwise[$x]['ProductName'],
			 	'Qty'=>$purchasestockwise[$x]['Qty'],
			 	'Cost'=>$purchasestockwise[$x]['Cost'],
			 	'MRP'=>$purchasestockwise[$x]['MRP'],
			 	'Status'=>$purchasestockwise[$x]['Status'],
			 	'AddBy'=>$purchasestockwise[$x]['AddBy'],
			 );
			    
			}
		}
		if(!empty($purchas)){
				try{
					$fromarray = array();
					$fromarray['AddBy']=$totalbill[0]['AddBy'];
					$fromarray['Total_Amt']=$totalbill[0]['Total_Amt'];
					$this->db->where('Pur_Id',$temppk);
					$updatelast=$this->db->update('tblmasterpurchase',$fromarray);
					if($updatelast){
						$query = $this->db->query("select max(srno) from  tblpurchase where Pur_fk_Id='".$temppk."'");
							$row = $query->row_array();
							$maxid= $row['max(srno)'];
							$maxid=$maxid+1;
						if($maxid)
						{
							if($purchas)
							{
							 	for ($fullloop=0; $fullloop <count($purchas) ; $fullloop++) 
							 	{ 
							 		$tempfullrow=$purchas[$fullloop];
							 		$fullqty=$tempfullrow['Qty'];
							 		for ($half=0; $half < $fullqty; $half++) 
							 		{
							 			$tempfullrow['Pur_fk_Id']=$temppk;
							 			$tempfullrow['Qty']=1;
							 			$tempfullrow['srno']=$maxid;
							 			$maxid++;
							 		 	$finaldone=$this->db->insert('tblpurchase',$tempfullrow);
							 		}
							 	}
						 	}
						 	else
						 	{
								return 'success-only-delete';
						 	}
					 	}
					 	else{
				 			return 'failed';
				 			}
						return 'success';
				 }
			}
			catch(Exception $e)
			{

			}
		}
		else if(empty($purchas))
		{
				return 'success-only-Read';
		}
		// print_r($billtotal);
		// print_r($purchas);
		// exit();
	}

	public function currentStock()
	{
		//from  where  GROUP BY  ORDER BY  
		$Finalresport=$this->db->select('DISTINCT(ProductName), SUM(Qty) as Qty')
						 ->where('Status','A')->from('tblpurchase')
						 ->group_by('ProductName')->order_by('ProductName','ASC')->get()->result();
						 return $Finalresport;

	}

	public function num_row_search($tablename,$id)
	{
			return $totalRow=$this->db->where('suppler_id',$id)->get($tablename)->num_rows();
	}
}