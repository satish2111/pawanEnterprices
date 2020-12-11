<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Calcutta');
class SaleModel extends CI_Model {

	public function num_row($tablename)
	{
		$date1=date('Y-m-d');
			$end_date=date('Y-m-d',strtotime($date1 . "+1 days"));
			return $totalRow=$this->db->where('PostingDate BETWEEN "'. date('Y-m-d'). '" and "'. date('Y-m-d', strtotime($end_date)).'"')->get($tablename)->num_rows();

	}
	public function num_row_byproductname($productName)
	{
		return $counts = $this->db->select('(Fk_Sale_id) as count')
						 ->where('ProductName',$productName)
						 ->get('tblsale')->num_rows();
						 
	}

	public function num_row_bycientname($clientname)
	{
		return $count = $this->db->where('client_id',$clientname)->get('tblmastersale')->num_rows();
	}
	public function getdata($limit,$offset)
		{
			$date1=date('Y-m-d');
			$end_date=date('Y-m-d',strtotime($date1 . "+1 days"));
			
			$this->db->select('c.client_id,c.FirstName,c.LastName,s.Sale_id,s.Billdate,s.TotalAmt,s.PaidAmt,s.lastpaiddate,s.OutstandingAmt');
			$this->db->from('tblmastersale s');
			$this->db->join('tblclient c','s.client_id = c.client_id');
			$this->db->where('s.Billdate BETWEEN "'. date('Y-m-d'). '" and "'. date('Y-m-d', strtotime($end_date)).'"');
			$this->db->limit($limit,$offset);
			$this->db->order_by('s.Billdate' ,'asc');
			$this->db->order_by('s.Sale_id' ,'asc');
				return $this->db->get()->result();
		}
		public function getsearchdata($clinetname,$limit,$offset)
		{
			$billNumber=array();
			if($clinetname['productnamedata']!='')
			{
				$billnos=$this->db->select('DISTINCT (Fk_Sale_id)')->from('tblsale')
				->where('ProductName',$clinetname['productnamedata'])->get()->result_array();
				
				foreach($billnos as $key=>$val)
				{
						$billNumber[$key]=$val['Fk_Sale_id'];
				}
				$this->db->where_in('Sale_id',$billNumber);
			}
			else if(empty($billNumber) && $clinetname['clientnamedata']!='')
			{
				$firtname=explode( ' ', $clinetname['clientnamedata'] );
				$first=$firtname[0];
				if(isset($firtname[1]))
				{
					$lastname=$firtname[1];
				}
				else{
					return 'failed';
				}
				$this->db->where('c.FirstName',$first);
				$this->db->where('c.LastName',$lastname);
			}
			else if($clinetname['billno']!='')
			{
				$this->db->where('Sale_id',$clinetname['billno']);
				
			}
			$this->db->select('c.client_id,c.FirstName,c.LastName,s.Sale_id,s.Billdate,s.TotalAmt,s.PaidAmt,s.lastpaiddate,s.OutstandingAmt');
			$this->db->from('tblmastersale s');
			$this->db->join('tblclient c','s.client_id = c.client_id');
			$this->db->limit($limit,$offset);
			return $this->db->get()->result();
		}
	public function clientlistpurchase()
	{
		
		$clientnamelist= $this->db->select('s.client_id,CONCAT(FirstName," ",LastName) as FirstName')->
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
	if(isset($firtname[1]))
	{
		$lastname=$firtname[1];
	}
	else{
		return 'failed';
	}
	if($firt!='' && $lastname!='')
	{
			$username=$this->db->select('client_id')->where('FirstName',$firt)->where('LastName',$lastname)->get('tblclient')->result();
			if($username!='')
			{
				$tempid=$username[0]->client_id;
				$clientTotalAmt=$this->db->select('(SUM(TotalAmt)-sum(PaidAmt))as Total')->where('client_id',$tempid)->get('tblmastersale')->result();
				$returnData = (object) ['status' => 'success','clientTotalAmt'=>$clientTotalAmt[0]];
			}
			else
			{
				return 'failed';
				$returnData = (object) ['status' => 'success','clientTotalAmt'=>''];
			}
			
			return  $returnData;
	}
	else{
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
			$query = $this->db->query("select max(Sale_id) from  tblmastersale where Billdate='".$totalbill[0]['BillDate']."' and client_id='".$totalbill[0]['client_id']."' ");
			$row = $query->row_array();
			$maxid= $row['max(Sale_id)'];
			$tempsrno=1;
				if($maxid)
				{
					for ($i=0; $i < count($saleinsert); $i++)
					{
							$saleinsert[$i]['Fk_Sale_id']=$maxid;
							$saleinsert[$i]['Srno']=$tempsrno;
							$tempsrno++;
							$forupdatesstatus=$saletable[$i]['Qty']+$saletable[$i]['Free'];
							/*status updated in purchase table*/
							for ($x=0; $x <$forupdatesstatus ; $x++)
							{
								$productnames=$saleinsert[$i]['ProductName'];
								$billno=$this->db->select('MIN(Billno)as Billno')
												 ->where('ProductName',$productnames)
												 ->where('STATUS',"A")
												 ->get('tblpurchase')->row_array();
								$tempbillno=$billno['Billno'];
								$selectstock=$this->db->query("SELECT min(srno)as srno from tblpurchase where Billno='".$tempbillno."' and STATUS='A' and ProductName='".$productnames."'    ");
								$rows = $selectstock->row_array();
								$fromarray=array();
								$fromarray['STATUS']='S';
								$fromarray['SaleBillNo']=$maxid;
								try
								{
									$test=$this->db->where('Billno',$tempbillno)
											 ->where('SrNo',$rows['srno'])
											 ->where('ProductName',$saleinsert[$i]['ProductName'])
											 ->update('tblpurchase',$fromarray);
								}
								catch(Exception $t)
								{
									echo $t;
									return 'failed';
								}
							}
							$finaldone=$this->db->insert('tblsale',$saleinsert[$i]);
					}
					return 'success';
				}
		}
		catch(Exception $e)
		{
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
				return 'fail';
				
			}
		}

		public function reportprint($startdate, $enddate,$paidornot)
		{
			//$returnArray = array();
			
			if($paidornot=='Paid')
			{
				$clientReport=$this->db->query("SELECT Sale_id,DATE_FORMAT(Billdate,'%d-%m-%Y')as Billdate,DATE_FORMAT(createdate,'%d-%m-%Y') as DueDate,TotalAmt,PaidAmt,if(OutstandingAmt<=0,TotalAmt,OutstandingAmt) as OutstandingAmt,CONCAT(c.FirstName,' ',c.LastName)as Name, c.Address,c.client_id FROM `tblmastersale` s join tblclient c on c.client_id=s.client_id where PaidAmt!='0.00' and TotalAmt!=PaidAmt and  Billdate BETWEEN '".$startdate."' and '".$enddate."' ORDER BY FirstName,Sale_id asc")->result();
			}
			else if ($paidornot=='Un-Paid'){
				$clientReport=$this->db->query("SELECT Sale_id,DATE_FORMAT(Billdate,'%d-%m-%Y')as Billdate,DATE_FORMAT(createdate,'%d-%m-%Y') as DueDate,TotalAmt,PaidAmt,if(OutstandingAmt<=0,TotalAmt,OutstandingAmt) as OutstandingAmt,CONCAT(c.FirstName,' ',c.LastName)as Name, c.Address,c.client_id FROM `tblmastersale` s join tblclient c on c.client_id=s.client_id where Billdate BETWEEN '".$startdate."' and '".$enddate."' and TotalAmt<>PaidAmt ORDER BY FirstName,Sale_id asc")->result();
			}
			if(isset($clientReport))
			{
				// $returnArray = $clientReport;
				
				// usort($returnArray, 'name_compare_sort');
				// $newarray = $returnArray;
				// usort($returnArray, 'date_compare_sort');
				$returnData = (object) ['status' => 'success','clientreport'=>$clientReport];
			}
			return $returnData;
		}

		public function  reportsalepurchase($startdate, $enddate)
		{
			$PurchaseReport=$this->db->query("SELECT SUM(Total_Amt) as PurchaseAmount FROM `tblmasterpurchase` WHERE  Billdate BETWEEN '".$startdate."' and '".$enddate."'")->result();
			$PurchaseStockReport=$this->db->query("SELECT sum(MRP) as MRP , SUM(Cost) as Cost FROM `tblpurchase` WHERE BillDate  BETWEEN '".$startdate."' and '".$enddate."' and Status='A' ")->result();
			$SaleWiseTotal=$this->db->query("SELECT  SUM(Cost) as Cost FROM `tblpurchase` WHERE BillDate  BETWEEN '".$startdate."' and '".$enddate."' and Status='S' ")->result();
			$purchasePaid=$this->db->query("SELECT SUM(Amt_paid) as PPaid from tblmasterpurchase where `BillDate` BETWEEN '".$startdate."' and '".$enddate."'")->result();
			$purchaseOutstanding=$this->db->query("SELECT SUM(Total_amt) as TotalAmt from tblmasterpurchase where `BillDate` BETWEEN '".$startdate."' and '".$enddate."'")->result();

			$saleGet=$this->db->query("SELECT SUM(PaidAmt) as SPaid  FROM tblmastersale where Billdate BETWEEN '".$startdate."' and '".$enddate."'")->result();
			
			$saleReport=$this->db->query("SELECT SUM(TotalAmt) as SaleAmount   FROM tblmastersale where Billdate BETWEEN '".$startdate."' and '".$enddate."'")->result();
			if(isset($purchasePaid) && isset($PurchaseReport) && isset($PurchaseStockReport) && isset($purchaseOutstanding) &&
			isset($saleReport) && isset($saleGet) && isset($SaleWiseTotal) )
			{
				$returnData= (object)['status' => 'success',
									
									'PurchaseReport'=>$PurchaseReport[0],
									'PurchaseStockReport'=>$PurchaseStockReport[0],
									'purchasePaid'=>$purchasePaid[0],
									'purchaseOutstanding'=>$purchaseOutstanding[0],
									'saleGet'=>$saleGet[0],
									'SaleWiseTotal'=>$SaleWiseTotal[0],
									//'saleOutstanding'=>$saleOutstanding[0],
									'saleReport'=>$saleReport[0],
								];
			}
			return $returnData;
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
				$maxid++;
				if($maxid)
				{
					$tempsrno=$maxid;
					for ($i=0; $i < count($saleinsert); $i++)
					{
							$saleinsert[$i]['Fk_Sale_id']=$saletable[0]['Fk_Sale_id'];
							$saleinsert[$i]['Srno']=$tempsrno;
							$tempsrno++;
							
							$forupdatesstatus=$saleinsert[$i]['Qty']+$saleinsert[$i]['Free'];
							
							for ($x=0; $x <$forupdatesstatus ; $x++)
							{
								$productnames=$saleinsert[$i]['ProductName'];
								$billno=$this->db->select('MIN(Billno)as Billno')
												 ->where('ProductName',$productnames)
												 ->where('STATUS',"A")
												 ->get('tblpurchase')->row_array();
								$tempbillno=$billno['Billno'];
								$selectstock=$this->db->query("SELECT min(srno)as srno from tblpurchase where ProductName='".$saleinsert[$i]['ProductName']."' and STATUS='A'  and Billno='".$tempbillno."'");
								$rows = $selectstock->row_array();
								$fromarray=array();
								$fromarray['STATUS']='S';
								$fromarray['SaleBillNo']=$saletable[0]['Fk_Sale_id'];
								$this->db->where('Billno',$tempbillno);
								$this->db->where('SrNo',$rows['srno']);
								$this->db->update('tblpurchase',$fromarray);
							}
							$finaldone=$this->db->insert('tblsale',$saleinsert[$i]);
						
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
			}
			else if(empty($saleinsert))
			{
				return 'success-only-Read';
			}
					
		}
		public function payment($paymentdetail)
		{
			$fromarray=array();
			$fromarray['PaidAmt']=$paymentdetail['PaidAmt'];
			$fromarray['OutstandingAmt']=$paymentdetail['OutstandingAmt'];
			$fromarray['lastpaiddate']=$paymentdetail['lastpaiddate'];
			$this->db->where('Sale_id',$paymentdetail['Sale_id']);
			$this->db->where('TotalAmt',$paymentdetail['TotalAmt']);
			$this->db->update('tblmastersale',$fromarray);

			$Detailinsert=array();
			$Detailinsert['Fk_Sale_id']=$paymentdetail['Sale_id'];
			$Detailinsert['DateOfPaid']=$paymentdetail['lastpaiddate'];
			$Detailinsert['Amt']=$paymentdetail['Amt'];
			$Detailinsert['Remark']=$paymentdetail['Remark'];
			$this->db->insert('tblsalepayment',$Detailinsert);
			return 'success';
		}

		public function clientlistPayment()
		{
			$columnnames="DISTINCT(c.client_id), CONCAT(FirstName,' ',LastName) as FirstName";
				
			$this->db->order_by('FirstName','asc');
				$clientdata= $this->db->select($columnnames)
							->from('tblmastersale s')->join('tblclient c','s.client_id=c.client_id')
							->join('tblsalepayment p','Fk_Sale_id=s.client_id')->get();
				if($clientdata->num_rows()>0)
				{
					return $clientdata->result();
				}
		}

		public function partyDetailReport($startdate,$enddate,$client)
		{
				$firtname=explode( ' ', $client );
				$first=$firtname[0];
				if(isset($firtname[1]))
				{
					$lastname=$firtname[1];
				}
				else{
					return 'failed';
				}
				if(isset($first) && isset($lastname))
				{
					$columnnames='Concat(c.FirstName," ",c.LastName) as Name,p.Fk_Sale_id,p.DateOfPaid,p.Amt,p.Remark,s.TotalAmt,s.OutstandingAmt';
					$detailData=$this->db->select($columnnames)->from('tblclient c')
									 ->join('tblmastersale s','s.client_id = c.client_id')
									 ->join('tblsalepayment  p','s.Sale_id=p.Fk_Sale_id')
									 ->where('c.FirstName',$first)
									 ->where('c.LastName',$lastname)
									 ->where('DateOfPaid BETWEEN "'.$startdate. '" and "'. $enddate.'"')->get();
									 if($detailData->num_rows()>0)
									 {
										 
										 return $detailData->result();
									 }
				}
				else{
					
				}
		}

		public function reportSaleFromTo($startdate,$enddate,$client)
		{
			if($client!='')
			{
				$this->db->where('s.client_id',$client);
			}
			$dataresults=$this->db->select('Sale_id as BillNo, Billdate,s.client_id,CONCAT(FirstName," ",LastName) as Name,TotalAmt,lastpaiddate,PaidAmt,OutstandingAmt')
						  ->from('tblmastersale s')
						  ->join('tblclient c','s.client_id=c.client_id')
						   ->where('s.BillDate BETWEEN "'.date('Y-m-d', strtotime($startdate)). '" and "'. date('Y-m-d', strtotime($enddate)).'"')
						   ->get()->result();
						   return $dataresults;

		}

		public function billdetail($billno)
		{	
			$dataresult=$this->db->select('Srno,ProductName,Qty,Free,mrp,productwisegross')
								 ->from('tblsale')->where('Fk_Sale_id',$billno)
								 ->get()->result();
								return $dataresult;
		}

		public function billNumberMissing()
		{					 
			$dataresult=$this->db->query("SELECT a.Sale_id as Missing_before_this from tblmastersale a LEFT join tblmastersale b on b.Sale_id=a.Sale_id-1 WHERE b.Sale_id IS Null ORDER by a.Sale_id ASC")->result();
			return $dataresult;
		}

		public function stocksalecompared()
		{
			$productandqty=$this->db->select('DISTINCT(ProductName), SUM(Qty) as purchaseQty')
							//->where('ProductName','BH-390 HEADPHONE')
							->from('tblpurchase')->group_by('ProductName')
							->ORDER_by('ProductName','asc')->get()->result_array();


							$billNumber=null;
							$purchasebillno=null;
							
							foreach($productandqty as $key=>$val)
							{
								$Stockavailable=$this->db->select('DISTINCT(ProductName), IFNULL(SUM(Qty),0) as purchaseavailableQty')
								->from('tblpurchase')->group_by('ProductName')->where('ProductName',$val['ProductName'])->where('Status','A')
								->ORDER_by('ProductName','asc')->get()->result_array();
								
								if(!empty($Stockavailable))
								{
									if($Stockavailable[0]['purchaseavailableQty']>0)
									{
										$productandqty[$key]['PurchaseAvailableQty']=$Stockavailable[0]['purchaseavailableQty'];
									}
									else if($Stockavailable[0]['purchaseavailableQty']<=0){
										$productandqty[$key]['PurchaseAvailableQty']=0;
									}
								}
								else{
									$productandqty[$key]['PurchaseAvailableQty']=0;
								}

								$saletotal= $this->db->select('DISTINCT(ProductName),IFNULL(sum(Qty),0) as TotalSale')->
								from('tblsale')->group_by('ProductName')->where('ProductName',$val['ProductName'])
								->ORDER_by('ProductName','asc')->get()->result_array();

								if(!empty($saletotal))
								{
									if($saletotal[0]['TotalSale']>0)
									{
										$productandqty[$key]['TotalSale']=$saletotal[0]['TotalSale'];
									}
									else if($saletotal[0]['TotalSale']<=0)
									{
										$productandqty[$key]['TotalSale']=0;
									}
								}
								else
								{
									$productandqty[$key]['TotalSale']=0;
								}
							

								$billnos=$this->db->select('DISTINCT IFNULL(Fk_Sale_id, 0) as Fk_Sale_id ')->from('tblsale')
								->where('ProductName',$val['ProductName'])->get()->result_array();

								foreach($billnos as $keyvalue=>$values)
								{
									if($values!='')
									{
										$billNumber.=$values['Fk_Sale_id'].' , ';
									}
								}
								
								//$productandqty[$key]['saleBillNo']=trim($billNumber,',');
								if($billNumber!=null)
								{
									$productandqty[$key]['SaleBillNo']=trim($billNumber,' , ');
								}
								else if($billNumber==null)
								{
									$productandqty[$key]['SaleBillNo']=0;
								}

								$billNumber='';

								$purchasbill=$this->db->select('DISTINCT IFNULL(Billno, 0) as Billno')->from('tblpurchase')
								->where('ProductName',$val['ProductName'])->get()->result_array();


								foreach($purchasbill as $keyitem=>$purBill)
								{
									$purchasebillno.=$purBill['Billno'].' , ';
								}
								//$purchaseBillNoAsPerProduct[$val['ProductName']]=trim($purchasebillno,',');
								if($purchasebillno!=null)
								{
									$productandqty[$key]['PurchaseBillNo']=trim($purchasebillno,' , ');
								}
								else if($purchasebillno==null)
								{
									$productandqty[$key]['PurchaseBillNo']=0;
								}
								$purchasebillno='';

							}
							
							//$fulldata=(object)['PurchaseQty' => $productandqty,'PurchaseAvailable'=>$Stockavailable,'Sale'=>$saletotal,'saleBillNoAsPerProduct'=>$saleBillNoAsPerProduct,'purchaseBillNoAsPerProduct'=>$purchaseBillNoAsPerProduct];
							return $productandqty;
							
							
			

		}

}