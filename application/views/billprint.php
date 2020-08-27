<?php
$title='Bill Print';
include('header.php');
            // echo "<pre>";
            // print_r($result);
                if($result=='fail')
                {
        		    $this->session->set_flashdata('error', 'Somthing went worng. Error!!');
                }
           $billno= $result[0]['Sale_id'];
           $Billdate= date('d-m-yy',strtotime($result[0]['Billdate']));
           $TotalAmt=$result[0]['TotalAmt'];
           $Partyname= $result[1]['Party'];
           $Partyaddress= $result[1]['Address'];
           $finalproductdata=$result[2];
           
            
            /**
            * Created by PhpStorm.
            * User: sakthikarthi
            * Date: 9/22/14
            * Time: 11:26 AM
            * Converting Currency Numbers to words currency format
            */
            $number = $TotalAmt;
            $no = round($number);
            $point = round($number - $no, 2) * 100;
            $hundred = null;
            $digits_1 = strlen($no);
            $i = 0;
            $str = array();
            $words = array('0' => '', '1' => 'one', '2' => 'two',
            '3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
            '7' => 'seven', '8' => 'eight', '9' => 'nine',
            '10' => 'ten', '11' => 'eleven', '12' => 'twelve',
            '13' => 'thirteen', '14' => 'fourteen',
            '15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
            '18' => 'eighteen', '19' =>'nineteen', '20' => 'twenty',
            '30' => 'thirty', '40' => 'forty', '50' => 'fifty',
            '60' => 'sixty', '70' => 'seventy',
            '80' => 'eighty', '90' => 'ninety');
            $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
            while ($i < $digits_1) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += ($divider == 10) ? 1 : 2;
            if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
            $str [] = ($number < 21) ? $words[$number] .
            " " . $digits[$counter] . $plural . " " . $hundred
            :
            $words[floor($number / 10) * 10]
            . " " . $words[$number % 10] . " "
            . $digits[$counter] . $plural . " " . $hundred;
            } else $str[] = null;
            }
            $str = array_reverse($str);
            $result = implode('', $str);
            $points = ($point) ?
            "." . $words[$point / 10] . " " .
            $words[$point = $point % 10] : '';
            $totalamountinword =  $result . "Rupees  " . $points . " Paise";?>
<link rel=stylesheet href="<?php echo base_url('assests/css/billprint.css'); ?> " rel=“stylesheet”>
<style>
td {
    border-right: solid 1px #000;
}
</style>

<div class="container">
    <!--- Success Message --->
    <?php if ($this->session->flashdata('success')) { ?>
    <p style="font-size: 20px; color:green"><?php echo $this->session->flashdata('success'); ?></p>
    <?php }?>
    <!---- Error Message ---->
    <?php if ($this->session->flashdata('error')) { ?>
    <p style="font-size: 20px; color:red"><?php echo $this->session->flashdata('error'); ?></p>
    <?php } ?>
    <div class="main mt-5" id="print" style=' border: 1px solid #000;'>
        <div class="row">
            <div class="col-md-6 left" style='float: left;text-align: left;padding-left: 2rem;'>
                <h5 style='font-size:1rem;'><?php  echo "Bill No : ". $billno;?></h5>
            </div>
            <div class="col-md-6 right" style='float: right;text-align: right;padding-right: 2rem;'>
                <h5 style='font-size:1rem;'>Dated : <?php echo $Billdate; ?> </h5>
            </div>
        </div>
        <div style="clear:both;"></div>
        <div class="row">
            <div class="col-lg-4" style='float: left;text-align:left;padding-left: 2rem;'>
                <h3 style='font-size:1.2rem;'>PAWAN ENTERPRIESE</h3>
                <address style='f'>
                    Rosalie Complex A Wing 403 <br />Near ACP office Godrej Hill<br /> 
                    Kalyan West 421301<br/>Mobile No. <b>9699115552</b><br/><span style='margin-left:4.6rem;'><b>9822115552</b></span>
                </address>
            </div>
            <div class="col-lg-4" style='float: left;width: 34%;padding-left: 2rem;text-align:center'>
                <h2>Invoice</h2>
            </div>
            <div class="col-lg-4" style='float:right;text-align:right;padding-right:2rem;'>
                <h3>Party : <?php echo $Partyname; ?>
                </h3>
                <p><?php echo $Partyaddress; ?></p>
            </div>
        </div>
        <div style="clear:both;margin-bottom:2rem;"></div>
        <table style='margin-left: 1%;margin-right: 1%;border: 1px solid #000;width: 98%;' cellpadding="0"
            cellspacing="0">
            <thead>
                <tr>
                    <td class='srno'
                        style='border-right: solid 1px #000;text-align: center;border-bottom: 1px solid #000;line-height: 3em;font-weight: 800;'>
                        Srno</td>
                    <td class='description'
                        style='border-right: solid 1px #000;text-align: center;border-bottom: 1px solid #000;line-height: 3em;font-weight: 800;'>
                        Description of Goods</td>
                    <td class='quantity'
                        style='width: 90px;;border-right: solid 1px #000;text-align: center;border-bottom: 1px solid #000;line-height: 3em;font-weight: 800;'>
                        Quantity</td>
                    <td class='free'
                        style='border-right: solid 1px #000;text-align: center;border-bottom: 1px solid #000;line-height: 3em;font-weight: 800;'>
                        Free</td>
                    <td class='rate'
                        style='border-right: solid 1px #000;text-align: center;border-bottom: 1px solid #000;line-height: 3em;font-weight: 800;'>
                        Rate</td>
                    <td class='per'
                        style='border-right: solid 1px #000;text-align: center;border-bottom: 1px solid #000;line-height: 3em;font-weight: 800;'>
                        Per</td>
                    <td class='amount'
                        style='width: 100px;text-align: center;border-bottom: 1px solid #000;line-height: 3em;font-weight: 800;'>
                        Amount</td>
                </tr>
            </thead>
            <tbody>
                <?php
                $srno=1;
                $TotalQty=0;
                foreach($finalproductdata as $row) {?>
                <tr class='noBorder'>
                    <td style='border-right: solid 1px #000;text-align: center;line-height: 1.5em;'><?php echo $srno; ?>
                    </td>
                    <td style='border-right: solid 1px #000;padding-left:0.5rem;line-height: 1.5em;'>
                        <?php echo $row->ProductName; ?></td>
                    <td style='border-right: solid 1px #000;text-align: right;padding-right: 1rem;line-height: 1.5em;'>
                        <?php echo $row->Qty; ?></td>
                    <td style='border-right: solid 1px #000;text-align: right;padding-right: 1rem;line-height: 1.5em;'>
                        <?php echo $row->Free; ?></td>
                    <td style='border-right: solid 1px #000;text-align: right;padding-right: 1rem;line-height: 1.5em;'>
                        <?php echo $row->mrp; ?></td>
                    <td style='border-right: solid 1px #000;text-align: center;'>per</td>
                    <td style='text-align: right;padding-right: 1rem;'> <?php echo $row->productwisegross; ?></td>
                </tr>
                <?php $srno++; $TotalQty+= $row->Qty; }
                 ?>
                <div style="clear:both;"></div>
                <tr class='noBorder' style='border-top: solid 2px #000;'>
                    <td
                        style='border-right: solid 1px #000;border-top: solid 2px #000;text-align: center;line-height: 1.5em;'>
                    </td>
                    <td
                        style='border-right: solid 1px #000;border-top: solid 2px #000;padding-left:0.5rem;line-height: 1.5em;'>
                        Total</td>
                    <td
                        style='border-right: solid 1px #000;border-top: solid 2px #000;text-align: right;padding-right: 1rem;line-height: 1.5em;'>
                        <?php echo $TotalQty;?> PCS
                    </td>
                    <td
                        style='border-right: solid 1px #000;border-top: solid 2px #000;text-align: right;padding-right: 1rem;line-height: 1.5em;'>
                    </td>
                    <td
                        style='border-right: solid 1px #000;border-top: solid 2px #000;text-align: right;padding-right: 1rem;line-height: 1.5em;'>
                    </td>
                    <td style='border-right: solid 1px #000;border-top: solid 2px #000;text-align: center;'></td>
                    <td style='text-align: right;padding-right: 1rem;border-top: solid 2px #000;'>
                        <?php echo $TotalAmt;?></td>
                </tr>
            </tbody>
        </table>
        <div style="clear:both;margin-top: 2rem;"></div>
        <div class="row">
            <div class="col-md-6 left" style='float: left; '>
                <h6
                    style='margin: 5px;font-size: 1rem;font-weight: 100;font-size:1.2rem;text-align: left;padding-left: 2rem;'>
                    Amount Chargeable (in words)</h6>
                <h5
                    style='text-transform: capitalize;margin-block-start: 0em;margin-left: 9%;font-size: 1.2rem;width: 100%;'>
                    <?php echo $totalamountinword?></h5>
            </div>
            <div class="col-md-6 right" style='float: right;text-align: right;padding-right: 2rem;width: 10%;'>
                <h6 style='font-size: 0.9rem;margin: 0;margin-right: 17%;'><i>E & O.E</i></h6>
            </div>
        </div>
        <div style="clear:both;margin-top: 2rem;"></div>
        <div class="row">
            <div class="col-md-6 left" style='float: left; '>
                <h6
                    style='margin: 5px;font-size: 1rem;font-weight: 100;font-size:1.2rem;text-align: left;padding-left: 2rem;'>
                    Vijay Jethanand Tharwani<br />
                    Indusind Bank<br />
                    Account Number : 159822115552<br />
                    IFSC : INDB0000154</h6>
            </div>
            <div class="col-md-6 right"
                style='float: right;text-align: right;padding-right: 2rem;margin-top: 0.6rem;padding-right: 2rem;'>
                <h6 style='font-size: 1rem;margin: 0;margin-bottom: 3rem;'>For PAWAN ENTERPRIESE </h6>
                <h6 style='font-size: 0.9rem;margin: 0;margin-right: 17%;'>authorised signatory<h6>
            </div>
        </div>
        <div style="clear:both;"></div>
    </div>
</div>



<?php include('footer.php'); ?>

<script type="text/javascript">
var prtContent = document.getElementById("print");
var html = `<html>
					<head>
					<link rel="stylesheet" href="${window.location.origin}\\pawanenterprises\\assests\\css\\billprint.css" type="text/css" media="print"></link>
					<link rel="stylesheet" href="${window.location.origin}\\pawanenterprises\\assests\\css\\bootstrap.min.css" type="text/css" media="print"></link>
					</head>
					<body>
					${prtContent.innerHTML}
					</body>
				</html>`;
console.log(html);
var WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
WinPrint.document.write(html);
//WinPrint.document.close();
WinPrint.focus();
//WinPrint.print();
//WinPrint.close();
//location.href = "/pawanenterprises/index.php/sale/index";
</script>