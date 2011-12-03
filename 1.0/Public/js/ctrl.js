// JavaScript Document
$(document).ready(function(){
    $(".conteudo").hide().fadeIn("slow")
	
	$("#ajuda").click(function(){$(".ajuda").toggle("slow");})
	$(".ajuda").click(function(){$(".ajuda").toggle("slow");})
	
	$("#ajuda_teste").click(function(){$(".ajuda_teste").toggle("slow");})
	$(".ajuda_teste").click(function(){$(".ajuda_teste").toggle();})
});