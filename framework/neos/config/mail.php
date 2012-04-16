<?php
/** 
 * Configuração Para Envio de Emails 
 * @copyright	NEOS PHP Framework - http://neosphp.org
 * @license		http://neosphp.org/license 
 * @author		Paulo R. B. Rocha - prbr@ymail.com 
 * @version		CAN : B4BC
 * @package		Neos\Config
 */

//EMAIL
$cfg->mail->host			= 'mx.host.com';  //veja 'neos/library/mail.php' para mais detalhes
$cfg->mail->mailer			= 'mail';   //pode ser: mail, sendmail ou smtp (opcional: $cfg->mail->Sendmail = /usr/sbin/sendmail (default))
$cfg->mail->subject			= 'Erro na aplicação...';
$cfg->mail->from			= 'contato@site.com';    //email e usuario cadastrado no 'host'
$cfg->mail->fromname		= 'Nome do Usuário';
$cfg->mail->to				= array('meu@email.com' => 'Meu Nome'); // opcional: $cfg->mail->cc=array() / $cfg->mail->bcc=array()