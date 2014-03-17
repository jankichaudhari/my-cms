<div class='mainInfo'>
<div id="subContainer">

<!--basic my group info section start -->
<div style="width:650px;border-bottom:thin solid #9D9D9D;padding-bottom:20px;">

    <div class="divRow" style="width:365px;padding-right:5px;">
    	<div style="width:365px;">
            <div class="divRow" style="width:110px;padding-top:11px;">
                <div style="background:#000;display:table;width:105px; height:75px;border: solid thin #999;vertical-align:middle;">
                    <div style="display:table-cell;width:100px; height:70px;vertical-align:middle;" align="center">
                          <img src="<?php echo $photoSrc; ?>" id="preview" width="<?php echo $photoWidth; ?>px" height="<?php echo $photoHeight; ?>px" alt="Easyshare Group"/>
                     </div>
                 </div>
             </div>
             <div class="divRow" style="width:250px;">
                 <h3><p><?php echo $title; ?></p></h3>
                 <p><span style="font-weight:bold;">Your Status&nbsp;:&nbsp;</span><?php echo $memberStatus; ?></p>
                 <p><span style="font-weight:bold;">Joining Date&nbsp;:&nbsp;</span><?php echo date('d M Y',$memJoinDt); ?></p>
                  <p><span style="font-weight:bold;">Group Established&nbsp;:&nbsp;</span><?php echo date('d M Y',$groupCreatedDt); ?></p>
             </div>
          </div>
          
          <?php if(count($memberBookInfo)!=0) 
		  			{ 
					?>
                    <div style="width:325px;">
                       <h3><p>Action Required</p></h3>
                       <div style="width:325px;border:1px solid #CCC;min-height:100px;">
                            <p>You need to enter Usage Data!<br/>From booking date : 
								<?php    
                                for($b=0;$b<count($memberBookInfo);$b++) 
                                { 
                                        $bookingId=$memberBookInfo[$b]['id'];
                                        $startBookDt=strtotime($memberBookInfo[$b]['startDate']."  ".$memberBookInfo[$b]['startTime']);
                                        $bookingDate=date('d M Y  h a',$startBookDt);
										echo  $bookingDate ."<br/>";
                                }
                                ?>
                                <span style="font-size:11px;">Please See&nbsp;<a href="#myUsage">My Usage</a></span>
               			 	</p>
                          </div>
                      </div>
			   	<?php } ?>
    </div>
    
    <div class="divRow" style="width:280px;">
    </div>
    
    <div class="clear"></div>
    
</div>
<!-- basic my group info section end -->

<!-- my booking section start -->
<h1><a name="myBooking">My Booking</a></h1>
<div><?php $this->myBooking($groupId,NULL); ?></div>
<!-- my booking section end -->

<!-- usage section start -->
<div style="width:650px;border-bottom:thin solid #FFF;padding-bottom:20px;">
    <h1><a name="myUsage">My Usage</a></h1><br/>
    <div id="myUsagePage">
        <div id="myUsageLog"><?php $this->myUsageLog($groupId); ?></div>
        <?php $this->myUsage($groupId); ?>
    </div>
 </div>
 <!-- usage section end -->
 
 <!-- my invoices section start -->
 <h1><a name="myInvoices">My Invoices</a></h1>
 <div><?php $this->myInvoices($groupId); ?></div>
 <!-- my invoices section end -->
 
 <!-- financial summary section start -->
 <h1><a name="financialSummary">Financial Summary</a></h1>
 <div><?php $this->financialSummery($groupId); ?></div>
 <!-- financial summary section end -->
</div>
</div>
