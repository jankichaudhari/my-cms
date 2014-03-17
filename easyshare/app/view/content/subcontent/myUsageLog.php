
<div style="width:650px;">
<!--Choose Month-->
<div style="width:650px;">
<span>Total Usage From <span style="color:#000000;font-weight:bold;"><?php echo date('d M Y',$selectedDt); ?></span> to <span style="color:#000000;font-weight:bold;"><?php echo date('d M Y'); ?></span></span>
</div>

<!--Hours-->
<div style="width:650px;">
<p>Total Hours : <span style="color:#000000;font-weight:bold;"><?php echo $countHours . ".". $countMinutes; ?></span></p>
</div>

<!--Periods-->
<div style="width:650px;">
<p>Total Periods : <span style="color:#000000;font-weight:bold;"><?php echo $countPeriod; ?></span></p>
</div>

</div>