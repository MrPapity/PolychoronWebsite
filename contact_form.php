<?php 
$email ='polychoron@krieger-electronics.com';
session_start();
$errors = '';
$name = '';
$visitor_email = '';
$visitor_subject = '';
$user_message = '';

if(isset($_POST['submit'])){
	$name = $_POST['name'];
	$visitor_email = $_POST['email'];
	$user_message = $_POST['message'];
	$visitor_subject = $_POST['subject'];

	if(empty($name)||empty($visitor_email)||empty($visitor_subject)){
		$errors .= "\n Todos los campos son requeridos. ";	
	}
	if(IsInjected($visitor_email)){
		$errors .= "\n Correo inválido.";
	}
	if(empty($_SESSION['6_letters_code'] ) ||
	  strcasecmp($_SESSION['6_letters_code'], $_POST['6_letters_code']) != 0){
		$errors .= "\n Captcha incorrecto.";
	}
	
	if(empty($errors)){
		$to = $email;
		$subject="New Form Submission: $visitor_subject";
		$from = $email;
		
		$body = "Se ha recibido un nuevo mensaje desde la página web de la persona: $name con el correo electrónico $visitor_email.\n".
    	"A continuación se muestra el mensaje:\n $user_message";
		
		$headers = "From: $from \r\n";
		$headers .= "Reply-To: $visitor_email \r\n";
		
		mail($to, $subject, $body,$headers);
		
		header('Location: thankyou.html');
	}
}

function IsInjected($str){
  $injections = array('(\n+)',
              '(\r+)',
              '(\t+)',
              '(%0A+)',
              '(%0D+)',
              '(%08+)',
              '(%09+)'
              );
  $inject = join('|', $injections);
  $inject = "/$inject/i";
  if(preg_match($inject,$str)){
    return true;
  }
  else{
    return false;
  }
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <link rel="stylesheet" type="text/css" href="CSS/main_style.css" />
        <title>Polychoron</title>
        <script language="JavaScript" src="scripts/gen_validatorv31.js" type="text/javascript"></script>
    </head>
    <body>
    	<a href="INDEX.HTML">
	    <img src="Polychoron.png"/>
	    </a>
	    <div class="AboutUs"><a href="AboutUs.html"><p class="font">Acerca de</p></a></div>
	    <div class="Services"><a href="http:www.hulur.com"><p class="font">Servicios</p></a></div>
	    <div class="Contact"><a href="contact_form.php"><p class="font">Contacto</p></a></div>
	    <div class="SingIn"><a href ="SignIn.html"><p class="font">Ingreso</p></a></div>
    	<div id="container">	
    		<form method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">			        
		        <table>
					<tr>
						<td>
							<div id='contact_form_errorloc' class='err'>
								<?php
									if(!empty($errors)){
										echo "<p class='err'>".nl2br($errors)."</p>";
									}
								?>
							</div>
						</td>
					<tr>
						<td>Nombre:</td>
						<td><input type="text" name="name" value='<?php echo htmlentities($name) ?>'></td>
					</tr>
					<tr>
						<td>Correo Electrónico:</td>
						<td><input type="text" name="email" value='<?php echo htmlentities($visitor_email) ?>'></td>
					</tr>
					<tr>
						<td>Asunto:</td>
						<td><input type="text" name="subject" value='<?php echo htmlentities($visitor_subject) ?>'></td>
					</tr>
					<tr>
						<td>Mensaje:</td>
						<td><textarea name="message" rows=8 cols=30><?php echo htmlentities($user_message) ?></textarea></td>
					</tr>
					<tr>
						<td><img class="Captcha" src="captcha_code_file.php?rand=<?php echo rand(); ?>" id='captchaimg'></td>
						<td>Por favor, introduzca el Captcha mostrado:<br><input id="6_letters_code" name="6_letters_code" type="text"><br>
							<small>¿No ve la imagen? Haga click <a href='javascript: refreshCaptcha();'>aquí</a>.</small><br>
							<input type="submit" value="Enviar" name='submit'>
							<input type="reset" value="Borrar">
						</td>
					</tr>
				</table>
			</form>
			<table class="Footer">
				<tr>
					<td width="25%">Mapa del Sitio</td>
					<td width="25%">Términos y Condiciones</td>
					<td width="25%">Política de Privacidad</td>
					<td width="25%">Derechos Reservados:<br>Krieger Electronics</td>
				</tr>
			</table>
		</div>
    </body>
</html>
<script language="JavaScript">
	var frmvalidator  = new Validator("contact_form");
	frmvalidator.EnableOnPageErrorDisplaySingleBox();
	frmvalidator.EnableMsgsTogether();
	frmvalidator.addValidation("name","req","Por favor, proporcione su nombre"); 
	frmvalidator.addValidation("email","req","Por favor, proporcione su email"); 
	frmvalidator.addValidation("email","email","Por favor, proporcione un email válido"); 
</script>
<script language='JavaScript' type='text/javascript'>
	function refreshCaptcha(){
		var img = document.images['captchaimg'];
		img.src = img.src.substring(0,img.src.lastIndexOf("?"))+"?rand="+Math.random()*1000;
	}
</script>