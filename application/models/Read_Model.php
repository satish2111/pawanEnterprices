<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Calcutta');
class Read_Model extends CI_Model{
// public function getdata(){
// $query=$this->db->select('FirstName,LastName,EmailId,ContactNumber,Address,PostingDate,user_id')
		// 		              ->get('tblusers');
		// 		              print_r($query);
		// 		        return $query->result();
	// 	}
		function num_row($tablename)
		{
			 $date1=date('Y-m-d');
			 $end_date=date('Y-m-d',strtotime($date1 . "+1 days"));
			 return $totalRow=$this->db->where('PostingDate BETWEEN "'. date('Y-m-d'). '" and "'. date('Y-m-d', strtotime($end_date)).'"')->get($tablename)->num_rows();
			// return $totalRow=$this->db->get($tablename)->num_rows();
		}

		public function getdata($ColumnNames,$tableName,$limit,$offset)
		{
			$date1=date('Y-m-d');
			$end_date=date('Y-m-d',strtotime($date1 . "+1 days"));
				$this->db->limit($limit,$offset);
						$query=$this->db->select($ColumnNames)->where('PostingDate BETWEEN "'. date('Y-m-d'). '" and "'. date('Y-m-d', strtotime($end_date)).'"')
				->get($tableName);
				return $query->result();
			// 	echo "<pre>";
			// 	print_r($query->result());
			// exit();
		}
	
		// public function getuserdetail($uid){
				// 		$ret=$this->db->select('FirstName,LastName,EmailId,ContactNumber,Address,PostingDate,user_id')
				// 		->where('user_id',$uid)
				// 		->get('tblusers');
				// 		return $ret->row();
			// 	}
		// }
		public function getuserdetail($column,$uid,$ColumnNames,$tableName)
		{
			$ret=$this->db->select($ColumnNames)
			->where($column,$uid)
			->get($tableName);
			return $ret->row();
		}
		public function searchget($tablename,$columnname,$search)
		{
			
				$this->db->select('*');
				$this->db->from($tablename);
				$this->db->like($columnname,$search);
				$query = $this->db->get();
				
		    	return $query->result();
		}
		public function supplername($name)
		{
			$username=$this->db->select('suppler_id')->where('FirstName',$name)->get('tblsuppler');
			return $username->result();

		}
	
	}