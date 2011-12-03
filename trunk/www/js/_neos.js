/*	NEOS PHP Framework
	Todos os direitos reservados - proibida a utilização deste material sem prévia autorização.
	Paulo R. B. Rocha - prbr@ymail.com
	http://neosphp.com*/

var comentario = null
var editor = null
var a = ''

function submitManual(){$("#conteudo").text($("#cconteudo").html())}	
function submitConteudo(){$.ajax({type:"POST",url:neos_URL+"inicial/salvar/"+neos_ID,dataType:"html",data:({conteudo:editor.getData()}),success:function(msg){alert(msg)}})}	
function delComent(id){$.ajax({type: "GET",url: neos_URL+"inicial/delComent/"+id,async:false});_refresh()}	
function _refresh(){document.location.href=document.location.href}

//Cria um elemento que fará o "breakout" do conteudo da tela
function breakout(on){if(on){$("body").append('<div id="neos_567" style="z-index:1000;position:fixed;top:0;left:0;right:0;bottom:0;background:#000">&nbsp;</div>');$("#neos_567").animate({opacity: 0.8}, 0);}else{$("#neos_567").remove();}}

$(document).ready(function(){
	
	//Menu do Manual
	$("#menuSwitch1").click(function(){breakout(true);$("#menux").show(0).animate({top: "30px", opacity: 1},400);})
	$("#menuSwitch").click(function(){$("#menux").animate({top: "-600px"},400).hide(0, function(){breakout(false)});})
	
	//Barra de Status
	$("#neos_status").animate({opacity: 0.5},0).mouseenter(function(){if($(this).css('right') == '-430px') {$(this).animate({right: 0, opacity: 1},400)}})
	$("#neos_status").mouseleave(function(){$(this).animate({right: '-430px', opacity: 0.2},400)})
})