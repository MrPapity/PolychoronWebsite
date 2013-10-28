<?php 
$email ='info@krieger-electronics.com';
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

	if(empty($name)||empty($visitor_email)||empty($visitor_subject)||empty($user_message)){
		$errors .= "\n Todos los campos son requeridos. ";	
	}
	if(IsInjected($visitor_email)||(strrpos($visitor_email, "@")==0)||(strrpos($visitor_email, ".")==0)){
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
		
		if(mail($to, $subject, $body, $headers)){
			$errors = "Correo enviado correctamente.";
		}else{
			$errors = "Error: El correo no pudo ser enviado.";
		}
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
		<link type="text/css" rel="stylesheet" href="CSS/main_stylesheet.css"/>
		<link type="text/css" rel="stylesheet" href="fonts/stylesheet.css"/>
		<script language="JavaScript" src="scripts/gen_validatorv31.js" type="text/javascript"></script>
		<title>Contacto: Polychoron</title>
	</head>
	<body>
		<div id="nav_bar_bg">
			<div id="nav_bar">
				<img id="logo" src="images/Polychoron_notext.png"/>
				<ul id="nav_list">
					<li id="nav_item"><a id="nav_link" href="index.html">Inicio</a></li><li id="nav_item"><a id="nav_link" href="contact.php">Contactanos</a></li><li id="nav_item"><a id="nav_link" href="JoinUs.html">Únete</a></li><li id="nav_item"><a id="nav_link" href="Services.html">Servicios</a></li>
				</ul>
			</div>
		</div>
	    <div id="content">
	    	<h1>Contáctenos</h1>
	    	<div id="padding_box">
		    	<form method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">			        
			        <table id="contact_form">
						<tr>
							<td>Nombre:</td>
							<td><input id="text_input" type="text" name="name" value='<?php echo htmlentities($name) ?>'></td>
						</tr>
						<tr>
							<td>Correo Electrónico:</td>
							<td><input id="text_input" type="text" name="email" value='<?php echo htmlentities($visitor_email) ?>'></td>
						</tr>
						<tr>
							<td>Asunto:</td>
							<td><input id="text_input" type="text" name="subject" value='<?php echo htmlentities($visitor_subject) ?>'></td>
						</tr>
						<tr>
							<td>Mensaje:</td>
							<td><textarea id="text_input_large" name="message"><?php echo htmlentities($user_message) ?></textarea></td>
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
				<div id='error'>
        			<?php if(!empty($errors)){echo "<p class='err'>".nl2br($errors)."</p>";}?>
				</div>
	    	</div>
	    </div>
	    <div id="footer">
	    	<div id="footer_up">
	    		<ul id="footer_link_list">
	    			<li id="footer_link_item"><a id="footer_link" href="index.html">Inicio</a></li>
	    			<li id="footer_link_item"><a id="footer_link" href="privacy.html">Aviso de Privacidad</a></li>
	    			<li id="footer_link_item"><a id="footer_link" href="terms.html">Términos y Condiciones</a></li>	
	    			<li id="footer_link_item"><a id="footer_link" href="contact.php">Contacto</a></li>
	    		</ul>
	    	</div>
	    	<div id="footer_down">
	    		<p id="footer_text">Contenido del sitio © 2013.<br>Derechos reservados: Polychoron</p>
	    	</div>
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