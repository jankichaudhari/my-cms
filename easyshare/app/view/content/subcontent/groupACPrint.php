<html>
<head>
<link href="public/css/print.css" rel="stylesheet" type="text/css">
</head>

<body>
<div class="printBody" id="printBody">

<div align="left"><img src="public/images/indulgeon.gif" alt="Easyshare" width="215" height="30"></div>
<div align="left"><h2>Group Account</h2></div>
<div align="right"><span id="smallText">Total Balance of Group is&nbsp;</span><span style="color:#FFF;font-weight:bold;font-size:11px;">&nbsp;&pound;&nbsp;<?php echo number_format($groupBalance, 2, '.', ','); ?></span></div>
<div style="width:650px;">
   <table width="800">
   <?php
   if(count($allMonths)!=0 && count($allInfo)!=0)
   {	
	   for($g=0;$g<count($allMonths);$g++) 
	   {
			
			if(count($allInfo[$g][0])!=0)
			{
				?><tr><td align="left" width="800" colspan="5">---------------------------------------------------------------------------------------------------------------------------</td></tr><?php
				$thisMonth = $allMonths[$g];
				$monthname = date('F Y',$thisMonth);
				?><tr><td align="left" width="800" colspan="5"><h4>Group Account Statement for <?php echo $monthname; ?></h4></td></tr><?php
				$groupACInfo = $allInfo[$g];
				$date = $groupACInfo[0];
				$description = $groupACInfo[1];
				$payment = $groupACInfo[2];
				$receipt = $groupACInfo[3];
				$balance = $groupACInfo[4];
				$groupBalance = $groupACInfo[5][1];
				?>
			   
				<!--Title Row-->
				<tr>
					<td width="100" align="center"><h4>Date</h4></td>
					<td width="250" align="center"><h4>Description</h4></td>
					<td width="150" align="center"><h4>Payments(&pound;)</h4></td>
					<td width="150" align="center"><h4>Receipts(&pound;)</h4></td>
					<td width="150" align="center"><h4>Balance(&pound;)</h4></td>
				</tr>
				<!--end Title Row-->
				
				<!--Data Rows-->
				<?php 
				for($a=0;$a<count($date);$a++) 
				{
					$amt_dt = date('d M',$date[$a]);
					$amt_desc = $description[$a];
					$ac_payment = number_format($payment[$a], 2, '.', ',');
					if($ac_payment==0)
					{
						$ac_payment = '-';
					}
					$ac_receipt = number_format($receipt[$a], 2, '.', ',');
					if($ac_receipt==0)
					{
						$ac_receipt = '-';
					}
					$ac_balance = number_format($balance[$a], 2, '.', ',');
					if($ac_balance==0)
					{
						$ac_balance = '-';
					}
				?>
					 <tr>
					<td width="100" align="center"><?php echo $amt_dt; ?></td>
					<td width="250" align="center" style="font-size:11px;"><?php echo $amt_desc; ?></td>
					<td width="150" align="center"><?php echo $ac_payment; ?></td>
					<td width="150" align="center"><?php echo $ac_receipt; ?></td>
					<td width="150" align="center"><?php echo $ac_balance; ?></td>
					</tr>
		<?php 
				}
			}	
	   }
    }
	else
	{
		?><tr><td>No Transaction Found.</td></tr><?php
	}?>
    <!--end Data Rows-->
    </table>
</div>

</div>
</body>
</html>