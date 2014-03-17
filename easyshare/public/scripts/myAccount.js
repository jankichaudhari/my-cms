// JavaScript Document
function editProfile(userId)
{
	window.open('index.php?process=create_user_page&param1='+userId,'Popup','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=950,height=1000,left=430,top=350');
	return false;
}
function changePassword()
{
	window.open("index.php?process=change_pwd_page",'Popup','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=950,height=650,left=430,top=350');
	return false;
}
function myTaggedListing_itemLoadCallback(carousel, state)
{
    // Check if the requested items already exist
    if (carousel.has(carousel.first, carousel.last)) {
        return;
    }

	$.post("index.php?process=myTaggedListing",{  first: carousel.first,  last: carousel.last },function(xml)
	{
		if(xml==0)
		{
			$('#myTaggedListing').html('(You Do not have any Tagged Listing)');
		}
		else
		{
			mycarousel_itemAddCallback(carousel, carousel.first, carousel.last, xml,'t');
		}
	} );
};

function myListing_itemLoadCallback(carousel, state)
{
    // Check if the requested items already exist
    if (carousel.has(carousel.first, carousel.last)) {
        return;
    }
	$.post("index.php?process=myListing",{  first: carousel.first,  last: carousel.last },function(xml)
	{
		if(xml==0)
		{
			$('#myListing').html('(You Do not have any Listing)');
		}
		else
		{
			mycarousel_itemAddCallback(carousel, carousel.first, carousel.last, xml,'l');
		}
	} );
};

function mycarousel_itemAddCallback(carousel, first, last, xml,listType)
{
    // Set the size of the carousel
	
    carousel.size(parseInt(jQuery('total', xml).text()));
	$(xml).find("id").each(function(i)
	  {
		 var childElements= []; 
		 childElements.push( $(this).find("image").text() );
		 childElements.push( $(this).find("height").text() );
		 childElements.push( $(this).find("width").text() );
		 childElements.push( $(this).find("popHeight").text() );
		 childElements.push( $(this).find("popWidth").text() );
		 childElements.push( $(this).find("imdId").text() );
		 childElements.push( $(this).find("name").text() );
		
		 if(listType=='t')
		 {
			 childElements.push( $(this).find("tagId").text() );
			 childElements.push( $(this).find("cost").text() );
			 childElements.push( $(this).find("location").text() );
		 }
		 else
		 {
			 childElements.push( $(this).find("listId").text() );
			 childElements.push( $(this).find("status").text() );
			 childElements.push( $(this).find("step").text() );
		 }
		 carousel.add(first + i, mycarousel_getItemHTML(childElements,listType));
	  });
};

/**
 * Item html creation helper.
 */
function mycarousel_getItemHTML(allValues,listType)
{	//alert(allValues);
	var url = allValues[0];
	var height = allValues[1];
	var width = allValues[2];
	var popHeight = allValues[3];
	var popWidth = allValues[4];
	var thisId = allValues[5];
	var name = allValues[6];
	var id = allValues[7];
	
	var topMargin = (90-height)/2;
	if(listType=='t')
	{
		var cost = allValues[8];
		var location = allValues[9];
		return '<div id="other_'+listType+thisId+'" align="center" style="position: absolute;z-index:0;"><a onmouseover="javascript:showListing('+thisId+','+popHeight+','+popWidth+',\''+listType+'\')" onmouseout="javascript:hideListing('+thisId+','+height+','+width+',\''+listType+'\')"><div  class="imgDiv" id="img_'+listType+thisId+'"><img src="' + url + '" width="' + width + '" height="' + height + '" id="image_'+listType+thisId+'" alt="Indulgeon Group" border="0" style="margin-top:'+topMargin+'px;"/></div><div class="imgBorder" id="imgBorder_'+listType+thisId+'"></div></a></div><div id="info_'+listType+thisId+'" class="imgLinks" align="center"><div class="details" id="detail_'+listType+thisId+'" ><div style="font-size:11px;color:#FCCF12;padding-bottom:3px;border-bottom:1px solid #333">'+name+'</div><div style="padding-top:3px;" id="smallText"><div>Cost of Share&nbsp;:&nbsp;<span id="orangeText">&pound;'+cost+'</span></div><div>Location&nbsp;:&nbsp;<span id="orangeText">'+location+'</span></div></div></div><div id="noDetail_'+listType+thisId+'"><div class="myACLinks"><a href="#">Contact Group</a></div><div class="myACLinks"><a href="#">View Full Details</a></div><div id="smallText"><a href="javascript:void(0);" onClick="javascript:unTagListing('+id+','+thisId+',\''+listType+'\');">Un-tag Listing</a></div></div></div>';
	}
	else
	{
		var status = allValues[8];
		var step = allValues[9];
 		return '<div id="other_'+listType+thisId+'" align="center" style="position: absolute;z-index:0;"><a onmouseover="javascript:showListing('+thisId+','+popHeight+','+popWidth+',\''+listType+'\')" onmouseout="javascript:hideListing('+thisId+','+height+','+width+',\''+listType+'\')"><div  class="imgDiv" id="img_'+listType+thisId+'"><img src="' + url + '" width="' + width + '" height="' + height + '" id="image_'+listType+thisId+'" alt="Indulgeon Group" border="0" style="margin-top:'+topMargin+'px;"/></div></a></div><div id="info_'+listType+thisId+'" class="imgLinks" align="center"><div class="details" id="detail_'+listType+thisId+'" ><div style="padding-bottom:3px;border-bottom:1px solid #333">'+name+'</div><div style="font-size:11px;padding-top:3px;">Status&nbsp;:&nbsp;<span style="color:#FCCF12;">'+status+'</span></div></div><div id="noDetail_'+listType+thisId+'"><div class="myACLinks"><span style="font-size:11px;text-transform:capitalize">'+name+'</span><br/><a href="javascript:show_step'+step+'('+id+');">Edit</a></div><div id="smallText"><a href="javascript:void(0);" onClick="javascript:unTagListing('+id+','+thisId+',\''+listType+'\');">Delete</a></div></div></div>';
	}
}
function unTagAllListing()
{
	response = confirm("Are you sure to delete this Record(s)?");
	if(response==true)
	{	
		$.post("index.php?process=unTagAllListing",{ },function(data)
		{
			  jQuery('#'+mainId).jcarousel({	
				itemLoadCallback: mycarousel_itemLoadCallback
			});
		});
	}
}
function unTagListing(id,thisId,listType)
{
	var clsNo = thisId + 1;
	var firstClsNm = 'jcarousel-item-'+clsNo;
	var process = null;
	var response = false;
	if(listType=='t')
	{
		process='unTagListing';
		mainId='myTaggedListing';
		response = true;
	}
	else
	{
		response = confirm("Are you sure to delete this Record(s)?");
		process='deleteListing';
		mainId='myListing';
	}
	
	if(response==true)
	{	
		$.post("index.php?process="+process,{  id: id },function(data)
		{
			$('#'+mainId+' .'+firstClsNm).remove();
			  jQuery('#'+mainId).jcarousel({	
				itemLoadCallback: mycarousel_itemLoadCallback
			});
		});
	}
}
function showListing(thisId,popHeight,popWidth,listType)
{
	var mainId = null;
	if(listType=='t')
	{
		mainId='myTaggedListing';
	}
	else if(listType=='l')
	{
		mainId='myListing';
	}
	
	$('#'+mainId).removeClass('jcarousel-container-horizontal');
	$('#'+mainId).addClass('jcarousel-container-horizontal-hover');
	
	$('#'+mainId+' .jcarousel-clip-horizontal').attr('id',listType+thisId);
	//jcarousel-clip
	$('#'+listType+thisId).removeClass('jcarousel-clip-horizontal');
	$('#'+listType+thisId).addClass('jcarousel-clip-horizontal-hover');
	
	var clsNo = thisId + 1;
	var firstClsNm = 'jcarousel-item-'+clsNo;
	var secClsNm = firstClsNm+'-horizontal';
	var thirdClsNm = 'jcarousel-item';
	var fourClsNm = 'jcarousel-item-horizontal';
	var newThirdClsNm  = 'jcarousel-item-hover';
	var newFourClsNm  = 'jcarousel-item-horizontal-hover';
	
	$('#'+mainId+' .'+firstClsNm).attr('id','listingItem_'+listType+clsNo);
	$('#listingItem_'+listType+clsNo).removeClass();
	$('#listingItem_'+listType+clsNo).addClass(newThirdClsNm);
	$('#listingItem_'+listType+clsNo).addClass(newFourClsNm);
	$('#listingItem_'+listType+clsNo).addClass(firstClsNm);
	$('#listingItem_'+listType+clsNo).addClass(secClsNm);
	
	$('#other_'+listType+thisId).css('z-index','4');
	$('#info_'+listType+thisId).removeClass();
	$('#info_'+listType+thisId).addClass('imgLinksHover');
	$('#img_'+listType+thisId).removeClass();
	$('#img_'+listType+thisId).addClass('imgDivHover');
	//var thisHeight = $('#image_'+listType+thisId).attr('height');
	$('#image_'+listType+thisId).attr('height',popHeight);
	//var thisWidth = $('#image_'+listType+thisId).attr('width');
	$('#image_'+listType+thisId).attr('width',popWidth);
	$('#detail_'+listType+thisId).show();
	$('#noDetail_'+listType+thisId).hide();
	$('#imgBorder_'+listType+thisId).hide();
}
function hideListing(thisId,h,w,listType)
{
	var mainId = null;
	if(listType=='t')
	{
		mainId='myTaggedListing';
	}
	else if(listType=='l')
	{
		mainId='myListing';
	}
	
	var clsNo = thisId + 1;
	var firstClsNm = 'jcarousel-item-'+clsNo;
	var secClsNm = firstClsNm+'-horizontal';
	var thirdClsNm = 'jcarousel-item';
	var fourClsNm = 'jcarousel-item-horizontal';
	var newThirdClsNm  = 'jcarousel-item-hover';
	var newFourClsNm  = 'jcarousel-item-horizontal-hover';
	
	 $('#listingItem_'+listType+clsNo).removeClass();
	 $('#listingItem_'+listType+clsNo).addClass(thirdClsNm);
	 $('#listingItem_'+listType+clsNo).addClass(fourClsNm);
	 $('#listingItem_'+listType+clsNo).addClass(firstClsNm);
	 $('#listingItem_'+listType+clsNo).addClass(secClsNm);
	 //$('.'+thirdClsNm+" "+fourClsNm+" "+firstClsNm+" "+secClsNm).removeAttr('id');
	 
	 $('#'+mainId).removeClass('jcarousel-container-horizontal-hover');
	 $('#'+mainId).addClass('jcarousel-container-horizontal');
	 
	 $('#'+listType+thisId).removeClass('jcarousel-clip-horizontal-hover');
	 $('#'+listType+thisId).addClass('jcarousel-clip-horizontal');
	 //$('.jcarousel-clip-horizontal').removeAttr('id');
	 
	$('#other_'+listType+thisId).css('z-index','0');
	$('#info_'+listType+thisId).removeClass();
	$('#info_'+listType+thisId).addClass('imgLinks');
	$('#img_'+listType+thisId).removeClass();
	$('#img_'+listType+thisId).addClass('imgDiv');
	//var thisHeight = $('#image_'+listType+thisId).attr('height');
	$('#image_'+listType+thisId).attr('height',h);
	//var thisWidth = $('#image_'+listType+thisId).attr('width');
	$('#image_'+listType+thisId).attr('width',w);
	$('#detail_'+listType+thisId).hide();
	$('#noDetail_'+listType+thisId).show();
	$('#imgBorder_'+listType+thisId).show();
}

function endGroup(groupId)
{
	var response = confirm("Are you sure to End this Group??");
	if(response==true)
	{
		$.post("index.php?process=endGroup",{ groupId : groupId } ,function(msg)
		{
			if(msg==1)
			{	
				location.reload();
			}
			else
			{
				alert("the group will continue to run until the next payment date..");
				$('#endResumeGroup_'+groupId).html('');
				$('#endResumeGroup_'+groupId).html('<a href="javascript:void(0);" onclick="javascript:resumeGroup('+groupId+')">Resume Group</a>');
			}
		});
	}
}
function resumeGroup(groupId)
{
	var response = confirm("Are you sure to Resume this Group??");
	if(response==true)
	{
		$.post("index.php?process=resumeGroup",{ groupId : groupId } ,function(msg)
		{
			if(msg==1)
			{	
				$('#endResumeGroup_'+groupId).html('');
				$('#endResumeGroup_'+groupId).html('<a href="javascript:void(0);" onclick="javascript:endGroup('+groupId+')">End Group</a>');
			}
			else
			{
				$('#msgMyAccount').html('Error!! Group could not resume.');
			}
		});
	}
}

jQuery(document).ready(function() {
    jQuery('#myTaggedListing').jcarousel({	
        itemLoadCallback: myTaggedListing_itemLoadCallback
    });
	
	jQuery('#myListing').jcarousel({	
        itemLoadCallback: myListing_itemLoadCallback
    });
}); 