// JavaScript Document

function tipo(e){
	var ttipo = e.value
	var i=0;
	var y = '';
	if(ttipo==''){ttipo = 'nenhum'}
	var x=Array('bd_dsn','bd_host','bd_user','bd_pass','bd_database','bd_charset','bd_driver')
	//limpando os inputs
	for(i=0;i <= (x.length - 1);i++){document.getElementById( x[i] ).value=''}
	//carregando os valores do banco de dados atual (selecionado)
	for(i=0;i <= (bdtipo[ttipo].length - 1);i++){if((y=document.getElementById( x[i] ))){y.value=bdtipo[ttipo][i]}}
	//mostrando o item DSN
	var pdo=document.getElementById( 'bd_driver' )
	db_config(pdo.value)
}
function db_config(drive){
	var dsn=document.getElementById( 'bd_dsn_span' )
	var host=document.getElementById( 'bd_host_span' )
	var user=document.getElementById( 'bd_user_span' )
	var pass=document.getElementById( 'bd_pass_span' )
	var database=document.getElementById( 'bd_database_span' )
	var charset=document.getElementById( 'bd_charset_span' )
		dsn.style.display='none'
		host.style.display='none'
		user.style.display='none'
		pass.style.display='none'
		database.style.display='none'
		charset.style.display='none'
	switch(drive){
		case 'mysql':
		host.style.display='block'
		user.style.display='block'
		pass.style.display='block'
		database.style.display='block'
		charset.style.display='block'
		break
		case 'pdo':
		dsn.style.display='block'
		user.style.display='block'
		pass.style.display='block'
		break
		case 'sqlite':
		database.style.display='block'
		break;
		case 'oracle':
		host.style.display='block'
		user.style.display='block'
		pass.style.display='block'
		database.style.display='block'
		charset.style.display='block'
		break
	}
}
function driver(e){
	var i=0;
	var x=Array('bd_dsn','bd_host','bd_user','bd_pass','bd_database','bd_charset','bd_driver')
	//limpando os inputs
	for(i=0;i <= (x.length - 1);i++){document.getElementById( x[i] ).value=''}
	db_config(e.value)	
}
function bancodados(e){
	var a = document.getElementById('bancodados')
	if(e.checked==false){a.style.display='none'}else{a.style.display='block'}
}
$(document).ready(function(){
	$(".conteudo").hide().fadeIn("slow") 
	var URL = $("#URL").val();
	
	//checando o banco de dados
	$("#bdteste").click(function(){
		var dom = URL+'bd_teste'
		$.ajax({
				  url: dom,
				  type: "POST",
				  data: "bd_dsn="+$("#bd_dsn").val()+"&bd_user="+$("#bd_user").val()+"&bd_pass="+$("#bd_pass").val()+"&bd_driver="+$("#bd_driver").val()+"&bd_host="+$("#bd_host").val()+"&bd_charset="+$("#bd_charset").val()+"&bd_database="+$("#bd_database").val(),
				  dataType: "script",
				  success: function(msg){alert(msg);}
			   })	
		
	})  	
	
	$("form").submit(function() {
		var msg = ''
		var form = $(":input")
		form.each(function(a,n){
			if(n.name=='local' && n.value==''){msg='Indique o LOCAL da instalação!'}
			if(n.name=='url' && n.value==''){msg='Indique a URL de acesso a aplicação (site)!'}
		})
		if(msg!=''){alert(msg); return false;}else{
		alert("ATENÇÃO!\n\nDurante a instalação podem ocorrer erros inesperados, travando o script...\n\nCaso ocorra algum travamento apague os arquivos e diretórios criados e comece do zero!");return true}
		return false
	});
	
	$("#ajuda").click(function(){$(".ajuda").toggle();})
	$(".ajuda").click(function(){$(".ajuda").toggle();})
	
	$("#ajuda_teste").click(function(){$(".ajuda_teste").toggle();})
	$(".ajuda_teste").click(function(){$(".ajuda_teste").toggle();})
});