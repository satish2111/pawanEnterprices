<?php
date_default_timezone_set('Asia/Calcutta');
class DashboardModel extends CI_Model {

    public function todayTotalSale()
    {
        $date1=date('Y-m-d');
        $end_date=date('Y-m-d',strtotime($date1 . "+1 days"));
         $totalRow=$this->db->select('sum(TotalAmt) as TotalAmt')->where('Billdate BETWEEN "'. date('Y-m-d'). '" and "'. date('Y-m-d', strtotime($end_date)).'"')->get('tblmastersale');
		if($totalRow->num_rows()>0)
		{
            return $totalRow->result();
        }
        else{
            return $totalRow ='0';
        }
    } 

    public function todayTotalPurchase()
    {
        $date1=date('Y-m-d');
        $end_date=date('Y-m-d',strtotime($date1 . "+1 days"));
         $todayTotalPurchase=$this->db->select('sum(Total_Amt) as TotalAmt')->where('Billdate BETWEEN "'. date('Y-m-d'). '" and "'. date('Y-m-d', strtotime($end_date)).'"')->get('tblmasterpurchase');
		if($todayTotalPurchase->num_rows()>0)
		{
            return $todayTotalPurchase->result();
        }
        else{
            return $todayTotalPurchase ='0';
        }
    } 
    

}
    
