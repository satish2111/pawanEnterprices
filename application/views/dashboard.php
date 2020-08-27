<?php
$title='Dashboard';
include('header.php'); ?>
<style type="text/css" media="screen">
table 
{
	font-family: arial, sans-serif;
	border-collapse: collapse;
	width: 100%;
}
td, th {
	border: 1px solid #dddddd;
	text-align: left;
	padding: 8px;
}
tr:nth-child(even) {
	background-color: #dddddd;
}
.red{
	color:red;
}
.green{
	color: green;
}
</style>
<div class="container mt-5">
	<div class="row">
		<div class="col-lg-12 col-sm-12 mb-3">
			<h4><?php echo $title; ?></h4>
			<hr/>
		</div>
		<div class="col-lg-6 col-md-12">
			<h1 class="red">Today Purchase</h1>
			<table>
				<thead>
					<tr>
						<th>BIll No.</th>
						<th>Suppler</th>
						<th>Total Amt</th>
						<th>Amt Paid</th>
					</tr>
				</thead>
				<tbody>
					<?php
					if(!empty($final[0]))
					{
						$result=$final[0];
						foreach($result as $row){
						?> 
						<tr id="<?php echo htmlentities($row->pur_id); ?>">
							<td><?php echo htmlentities($row->billno);?></td>
							<td><?php echo htmlentities($row->FirstName);?></td>
							<td><?php echo htmlentities($row->total_amt);?></td>
							<td><?php echo htmlentities($row->amt_paid);?></td>
						</tr>
						<?php
					}
				}
				else {
					?><tr>
						<td colspan="12" style="color:red">Today's Records not found</td>
					</tr>
					<?php
					}
					?>
					<?php
					?>
				</tbody>
			</table>
		</div>
		<div class="col-lg-6 col-md-12">
			<h1 class="green">Today Sales</h1>
			<table>
				<thead>
					<tr>
						<th>BIll No.</th>
						<th>Suppler</th>
						<th>Total Amt</th>
						<th>Paid Amt</th>
					</tr>
				</thead>
				<tbody>
					<?php
					if(!empty($final[1]))
					{
						$resultsale=$final[1];
						foreach($resultsale as $rows){
						?> 
						<tr id="<?php echo htmlentities($rows->Sale_id); ?>">
							<td><?php echo htmlentities($rows->Sale_id);?></td>
							<td><?php echo htmlentities($rows->FirstName);?></td>
							<td><?php echo htmlentities($rows->TotalAmt);?></td>
							<td><?php echo htmlentities($rows->PaidAmt);?></td>
						</tr>
						<?php
					}
				}
				else {
					?><tr>
						<td colspan="12" style="color:red">Today's Records not found</td>
					</tr>
					<?php
					}
					?>
					<?php
					?> 
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php include('footer.php'); ?>