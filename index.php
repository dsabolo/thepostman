<?php 
	$deco= GetRandomImageURL('Postage stamp',0,7);
	$logo = '<img src="'.$deco.'">';

	if($_REQUEST['checktoken']){
		
		if(empty($_REQUEST['postman_nombreRemitente'])){
			$error='<p>Por favor indicar el nombre del remitente.</p>';
		}
		if(empty($_REQUEST['postman_mailRemitente'])){
			$error.='<p>Por favor indicar el email del remitente.</p>';
		}
		else {
			if(!filter_var($_REQUEST['postman_mailRemitente'], FILTER_VALIDATE_EMAIL)){
				$error.='<p>Introducir un email válido.</p>';
			}
		}
		if(empty($_REQUEST['postman_destinatarios'])){
			$error.='<p>Por favor indicar uno o más destinatarios.</p>';
		}
		else {
			$destinatarios = explode(',',$_REQUEST['postman_destinatarios']);
			foreach($destinatarios as $email){
				$mails[]=trim($email);
				if(!filter_var(trim($email), FILTER_VALIDATE_EMAIL)){
					$errorAlgunMail.='<p>El destinatario '.$email.' es inválido.</p>';
				}
			}
			$error.=$errorAlgunMail;
		}
		if(empty($_REQUEST['postman_asunto'])){
			$error.='<p>Por favor indicar el asunto.</p>';
		}
		if(empty($_REQUEST['postman_mensaje'])){
			$error.='<p>Por favor indicar el mensaje.</p>';
		}



		//Envio los correos
		if(empty($error)){
			if($_REQUEST['postman_smtphost']){
				ini_set("SMTP",$_REQUEST['postman_smtphost']);
			}
			
			ini_set('sendmail_from', $_REQUEST['postman_mailRemitente']); 
			foreach($mails as $validMail){
				$Name = $_REQUEST['postman_nombreRemitente']; 
				$email = $_REQUEST['postman_mailRemitente']; 
				$recipient = $validMail; 
				$mail_body = $_REQUEST['postman_mensaje'];
				$subject = $_REQUEST['postman_asunto']; 
				$header = "From: ". $Name . " <" . $email . ">\r\n"; 
				if(mail($recipient, $subject, $mail_body, $header)){
					print "<p>Enviando email a ".$validMail.'</p>';
					sleep(0.5);
				} 
				else {
					print '<p class="error">Error enviado email a '.$validMail.'</p>';
				}

				

			}
			print '<p>Operación terminada</p>';
			die();
		}
		else {
			print '<div class="error">'.$error.'</div>';
			die();
		}
	}
 ?>
<!DOCTYPE html> 
<html>
<head>
	<title>The PostMan Mail</title>
	<link rel="stylesheet" type="text/css" href="postman.css">
	<meta charset="UTF-8">
	
	<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.10.2.min.js"></script>
	<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.js"></script>
	<script type="text/javascript" src="postman.js"></script>
</head>
<body>
	<div id="postman">	
		<?php echo $logo; ?>
		<h1>The PostMan Mail Service</h1>
		<p>Envio de emails anónimo a multiples destinatarios</p>
		<div id="msg"></div>
		<form id="postman_form">
		
			<input type="hidden" name="checktoken" value="<?php echo time(); ?>"/>
			
			<input placeholder="Nombre del Remintente" type="text" id="postman_nombreRemitente" name="postman_nombreRemitente" required/>
			<input placeholder="Host" type="text" id="postman_smtphost" name="postman_smtphost"/>
			<input placeholder="Email Remitente" type="email" id="postman_mailRemitente" name="postman_mailRemitente" required/>
			
			<textarea placeholder="Destinatarios" type="text" id="postman_destinatarios" name="postman_destinatarios" required/></textarea>
			<p class="help">Puede ingresar más de un destinatario separando cada email con una coma (,).</p>

			<input placeholder="Asunto" type="text" id="postman_asunto" name="postman_asunto" required/>

			<textarea placeholder="Mensaje" type="text" id="postman_mensaje" name="postman_mensaje" required></textarea>
			<select name="postman_posttype">
				<option value="plano">Texto Plano</option>
				<option value="html">Código HTML</option>
			</select>
			<input type="submit"  value="enviar" id="postman_submit"/>

		</form>
		<script>
		$("#postman_form").validate();
		</script>
	</div>
</body>
</html>
<?php 





    function GetRandomImageURL($topic='', $min=0, $max=100)
    {
      // get random image from Google
      if ($topic=='') $topic='image';
      $ofs=mt_rand($min, $max);
      $geturl='http://www.google.ca/images?q=' . str_replace(' ','+',$topic) . '&start=' . $ofs . '&gbv=1';
      $data=file_get_contents($geturl);
     
      $f1='<div id="center_col">';
      $f2='<a href="/imgres?imgurl=';
      $f3='&amp;imgrefurl=';
     
      $pos1=strpos($data, $f1)+strlen($f1);
      if ($pos1==FALSE) return FALSE;
      $pos2=strpos($data, $f2, $pos1)+strlen($f2);
      if ($pos2==FALSE) return FALSE;
      $pos3=strpos($data, $f3, $pos2);
      if ($pos3==FALSE) return FALSE;
      return substr($data, $pos2, $pos3-$pos2);
    }
     



?>