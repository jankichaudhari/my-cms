// JavaScript Document
$(document).ready(function () {
							
	/*$('.faqInner .question').click(  function() {
				var thisId = $(this).attr('id');
				$('.faqInner #answer'+thisId).slideToggle("slow");
		 });
	
	$('.newGroupInner .topic').click(  function() {
				var thisId = $(this).attr('id');
				$('.newGroupInner #topicInfo'+thisId).slideToggle("slow");
		 });
	
	$('.splitUseInner .methodName').click(  function() {
				var thisId = $(this).attr('id');
				$('.splitUseInner #methodDesc'+thisId).slideToggle("slow");
		 });
	
	$('.sampleContract .sampleName').click(  function() {
				var thisId = $(this).attr('id');
				$('.sampleContract #sampleDesc'+thisId).slideToggle("slow");
		 });
	
	$('.expBlockBg .joinGroupName').click(  function() {
				var thisId = $(this).attr('id');
				$('.expBlockBg #joinGroupDesc'+thisId).slideToggle("slow");
		 });*/
	$('.expBlockBg .expBlockTitle').click(  function() {
				var thisId = $(this).attr('id');
				$('.expBlockBg #expBlockDesc'+thisId).slideToggle("slow");
		 });
});