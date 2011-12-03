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


$(document).ready(function(){
	
	//Trocando o Tema do site
	$("#changeTheme").change(function(){
		$.ajax({
			type:"POST",
			url:neos_URL+"inicial/changeTheme/",
			data:({theme:$(this).val()}),
			success:function(msg){ 
				if(msg == 'ok'){_refresh()}else{alert('Você precisa estar LOGADO para trocar o tema do site!')}
			}
			})	
	})
	
	//editor para comentários
	if($("#comentario").attr('id')){
		comentario = CKEDITOR.replace( 'comentario', {
							height	: 80,
							width	: '100%',
							skin	: 'v2', 
							toolbar	: [['Save','Bold','Italic','Underline','-','BulletedList','NumberedList','-','Link','Unlink','JustifyLeft','JustifyCenter','JustifyRight','Format','-','Styles','TextColor']]
		});
	}
	
	$("#hinicial").fadeOut(8000,function(){$("#hinicial").css('display','none');$("#hfinal").fadeIn(3000)})

	
	$("#editar").click(function(){
		if(!editor){
		editor = CKEDITOR.replace( 'cconteudo', {
						height	: 600,
						width	: '100%',
						skin	: 'v2', 
						toolbar	: [['Save','Bold','Italic','Underline','-','BulletedList','NumberedList','-','Link','Unlink','JustifyLeft','JustifyCenter','JustifyRight','Format','-','Styles','TextColor']]
		});
		} else { 
		editor.destroy();
		editor = null; }

	})
	
	$("#cancelar").click(function(){_refresh()})
	
	//login / logout no site
	$("#fastlogin").keypress(function(e){if(e.which==13){$("#bt_login").click()}})
	$("#bt_login").click(function(){$.ajax({type: "GET",url: neos_URL+"user/fastlogin/"+$("#fastlogin").val(),success: function(msg){alert(msg);_refresh()}})})
	$("#logout").click(function(){$.ajax({type: "GET",url: neos_URL+"user/logout/",success: function(msg){_refresh()}})})
	
	
	//Tentando fazer download sem concordar com os termos de uso
	$(".download a").click(function(e){
		//O material não está disponível
		if($(this).text() == 'Configurando o NEOS.pdf'){alert('Desculpe!\n\nEste material está em fase de produção e,\nportanto, indisponível no momento!'); return false;}			
		if($("#licenca").attr('checked')){return true}
		else{
			var a = $(this).text()
			if(a=='core.zip' || a=='core.phar' || a=='app.zip' || a=='Manual de Usuário.pdf'){
				alert('É preciso CONCORDAR com os termos da licença de uso!')	
				if($("#seta").attr('id')!='seta'){$(".download").append('<div id="seta" style="margin:-70px 0px 0px 280px;"><img src="'+neos_URL+'img/seta.gif"/></div>')}
				return false;
			}else{return true}
			}
			return false		
		})
	
	//desligando o alerta dos Termos de Uso
	$("#licenca").click(function(){if($("#licenca").attr('checked')){$(".download #seta").remove()}})
	
	//USUARIOS
	function apaga_formularios(a){
		$(".users #fpesquisar").css('margin-top','85px');
		$(".users #fentrar").slideUp();
		$(".users #fcadastrar").slideUp();
		$(".users #fsenha").slideUp();
		$(".users #fpesquisar").slideUp();
		$(".users #fpesq_result").hide();		
		$(".users #"+a).slideDown("slow")}
	
	$(".users #gsenha").click(function(){apaga_formularios('fsenha')})
	$(".users #gcadastrar").click(function(){apaga_formularios('fcadastrar')})
	$(".users #gentrar").click(function(){apaga_formularios('fentrar')})
	$(".users #gpesquisar").click(function(){apaga_formularios('fpesquisar')})
	//USUARIOS::BOTÕES
	$(".users #go_login").click(function(){
		if($(".users #login").val()==''){alert('Digite seu código de acesso ou e-mail!');return false}
		if($(".users #senha").val().length < 6 || $(".users #senha").val().length > 20){alert('Digite a senha corretamente.'); return false}
		$.ajax({type:"POST",url:neos_URL+"user/login/",data:({login:$(".users #login").val(),senha:$(".users #senha").val()}),success:function(msg){if(msg=='ok'){alert('Bem Vindo!');_refresh()}else{alert(msg)}}});
	})
	$(".users #go_logout").click(function(){$("#logout").click()})
	$(".users #go_cadastrar").click(function(){
		if($(".users #cnome").val()==''){alert('Digite seu nome!');return false}
		if($(".users #cmail").val()==''){alert('Digite seu e-mail!');return false}
		if($(".users #csenha").val().length < 6 || $(".users #csenha").val().length > 20){alert('Digite a senha corretamente (6 < senha > 20).'); return false}
		$.ajax({type:"POST",url:neos_URL+"user/add/",data:({nome:$(".users #cnome").val(),senha:$(".users #csenha").val(),mail:$(".users #cmail").val()}),success:function(msg){if(msg=='ok'){alert('Enviamos um link de ativação para o e-mail informado!');_refresh()}else{alert(msg)}}});
	})
	$(".users #go_senha").click(function(){	
		if($(".users #tmail").val() == ''){alert('Digite seu e-mail!');return false}	
		$.ajax({type:"POST",url:neos_URL+"user/trocarSenha/"+$(".users #tmail").val(),success:function(msg){if(msg=='ok'){alert("Enviamos um link de ativação para o e-mail informado!\nVerifique a sua caixa de e-mails.")}else{alert('Este e-mail não está cadastrado em nosso sistema!')}}});	
		$(".users #tmail").val()
	})
	$(".users #go_pesquisar").click(function(){
		$(".users #fpesq_result").slideUp()
		if(jQuery.trim($(".users #pesquisar").val()).length < 3){alert('Você precisa digitar pelo menos 3 caracteres para pesquisa!');return false}
		var temp='';$("input:checked").each(function(){temp+=' '+$(this).val()})
		$.ajax({type:"GET",url:neos_URL+"user/pesquisar/",dataType:"html",data:({pesquisar:$(".users #pesquisar").val(),onde:temp}),
		success:function(msg){$(".users #fpesquisar").css('margin-top','-40px');$(".users #fpesq_result").slideDown("slow");$(".users #fpesq_result_span").html(msg)}
		});
	})
	
	/*SOMENTE neos_AREA PESSOAL*/
	if(neos_AREA=='paginaPessoal'){
		sobre = CKEDITOR.replace( 'sobre', {
						height	: 300,
						width	: '100%',
						skin	: 'v2', 
						toolbar	: [['Save','Bold','Italic','Underline','-','BulletedList','NumberedList','-','Link','Unlink','JustifyLeft','JustifyCenter','JustifyRight'],['Format','-','Styles','TextColor']]
		});
	}
	

	
//fim	
})