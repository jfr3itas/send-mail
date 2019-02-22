<?php

require "../bibliotecas/php-mailer/all_phpmailer.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mensagem {
    
    private $email = null;
    private $assunto = null; 
    private $msg = null;
    public  $status = array('codigo' => null, 'descricaoo' => '');
    
    public function __get($atributo) {
        return $this->$atributo;
    }
    
    public function __set($atributo, $valor) {
        $this->$atributo = $valor;
    }
    
    public function mensagemValida() {
        if(empty($this->email) || empty($this->assunto) || empty($this->msg))
            return false;
        
        return true;
    }
    
} $mensagem = new Mensagem();


$mensagem->__set('email', $_POST['email']);
$mensagem->__set('assunto', $_POST['assunto']);
$mensagem->__set('msg', $_POST['msg']);


if(!$mensagem->mensagemValida()) {
    echo "Não Válida";
    header('Location: ../index.php');
}

$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = false;                                 // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = '######';                       // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = '######';               // SMTP username
    $mail->Password = '######';                         // SMTP password
    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587;                                    // TCP port to connect to
    
    //Recipients
    $mail->setFrom('########', 'Remetente');
    $mail->addAddress($mensagem->__get('email'), '');  // Add a recipient
    
    //Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = $mensagem->__get('assunto');
    $mail->Body    = $mensagem->__get('msg');
    
    $mail->send();

    $mensagem->status['codigo'] = 1;
    $mensagem->status['descricao'] = 'Email enviado com sucesso!';

} catch (Exception $e) {

    $mensagem->status['codigo'] = 2;
    $mensagem->status['descricao'] = 'Não foi possível enviar esse email.';

}


?>

<html>
	<head>
		<meta charset="utf-8" />
    	<title>App Mail Send</title>
    	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

	</head>

    <body>
        <div class="container">
            <div class="py-3 text-center">
				<img class="d-block mx-auto mb-2" src="../imagens/logo.png" alt="" width="72" height="72">
				<h2>Send Mail</h2>
				<p class="lead">Seu app de envio de e-mails particular!</p>
			</div>
        </div>

        <div class="row">
            <div class="col-md-12">

                <?php if($mensagem->status['codigo'] == 1) { ?>
                    <div class="container">
                        <h1 class="display-4 text-success">Sucesso</h1>
                        <p><?= $mensagem->status['descricao']; ?></p>
                        <a href="../index.php" class="btn btn-success btn-sm mt-2 text-white">Voltar</a>
                    </div>
                <?php } ?>


                <?php if($mensagem->status['codigo'] == 2) { ?>
                    <div class="container">
                        <h1 class="display-4 text-danger">Ops!</h1>
                        <p><?= $mensagem->status['descricao']; ?></p>
                        <a href="../index.php" class="btn btn-danger btn-sm mt-2 text-white">Voltar</a>
                    </div>
                <?php } ?>

            </div>
        </div>
        
    </body>

</html>