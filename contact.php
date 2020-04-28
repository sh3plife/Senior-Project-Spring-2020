<?php


require 'PHPMailer-master/PHPMailerAutoload.php';

$fromEmail = 'form@jimshepp.com';
$fromName = 'Contact Form';

$sendToEmail = 'james.n.sheppard@gmail.com';
$sendToName = 'Shep FormData';

$subject = 'New message from contact form';

$smtpHost = 'smtp.gmail.com';
$smtpUsername = 'shepformdata@gmail.com';
$smtpPassword = 'Whiskers1989';


$fields = array('name' => 'Name', 'surname' => 'Surname', 'phone' => 'Phone', 'email' => 'Email', 'message' => 'Message');


$okMessage = 'Contact form successfully submitted. Thank you, I will get back to you soon!';


$errorMessage = 'There was an error while submitting the form. Please try again later';


error_reporting(E_ALL & ~E_NOTICE);

try {
    if (count($_POST) == 0) {
        throw new \Exception('Form is empty');
    }
    
    $emailTextHtml = "<h1>You have a new message from your contact form</h1><hr>";
    $emailTextHtml .= "<table>";
    
    foreach ($_POST as $key => $value) {
        // If the field exists in the $fields array, include it in the email
        if (isset($fields[$key])) {
            $emailTextHtml .= "<tr><th>$fields[$key]</th><td>$value</td></tr>";
        }
    }
    $emailTextHtml .= "</table><hr>";
    
    $mail = new PHPMailer;
    
    $mail->setFrom($fromEmail, $fromName);
    $mail->addAddress($sendToEmail, $sendToName); 
    $mail->addReplyTo($from);
    
    $mail->isHTML(true);
    
    $mail->Subject = $subject;
    $mail->Body    = $emailTextHtml;
    $mail->msgHTML($emailTextHtml); 
    
    
    $mail->isSMTP();
    

    $mail->SMTPDebug = 0;
    $mail->Debugoutput = 'html';
    

    $mail->Host = gethostbyname($smtpHost);
    
    $mail->Port = 587;
    
    $mail->SMTPSecure = 'tls';
    
    $mail->SMTPAuth = true;
    
    $mail->Username = $smtpUsername;
    
    $mail->Password = $smtpPassword;
    
    if (!$mail->send()) {
        throw new \Exception('I could not send the email.' . $mail->ErrorInfo);
    }
    
    $responseArray = array('type' => 'success', 'message' => $okMessage);
} catch (\Exception $e) {
    
    $responseArray = array('type' => 'danger', 'message' => $e->getMessage());
}



if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    $encoded = json_encode($responseArray);
    
    header('Content-Type: application/json');
    
    echo $encoded;
}

else {
    echo $responseArray['message'];
}
