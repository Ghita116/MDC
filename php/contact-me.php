<?php
if($_POST) {

    $to_Email = 'marketing.maisondescours@gmail.com'; // Write your email here to receive the form submissions
    $subject = 'New message from microsite MDC'; // Write the subject you'll see in your inbox
   
    // Use PHP To Detect An Ajax Request
    if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
   
        // Exit script for the JSON data
        $output = json_encode(
        array(
            'type'=> 'error',
            'text' => 'Request must come from Ajax'
        ));
       
        die($output);
    }
   
    // Checking if the $_POST vars well provided, Exit if there is one missing
    if(!isset($_POST["userName"]) || !isset($_POST["userEmail"]) || !isset($_POST["userSubject"]) || !isset($_POST["userMessage"])) {
        
        $output = json_encode(array('type'=>'error', 'text' => '<i class="fas fa-times"></i> Input fields are empty!'));
        die($output);
    }
   
    // PHP validation for the fields required
    if(empty($_POST["userName"])) {
        $output = json_encode(array('type'=>'error', 'text' => '<i class="fas fa-times"></i> We are sorry but your name is too short or not specified.'));
        die($output);
    }
    
    if(!filter_var($_POST["userEmail"], FILTER_VALIDATE_EMAIL)) {
        $output = json_encode(array('type'=>'error', 'text' => '<i class="fas fa-times"></i> Please enter a valid email address.'));
        die($output);
    }

    // To avoid the spammy bots, you can change the value of the minimum characters required. Here it's <20
    if(strlen($_POST["userMessage"])<1) {
        $output = json_encode(array('type'=>'error', 'text' => '<i class="fas fa-times"></i> Too short message! Take your time and write a few words.'));
        die($output);
    }
   
    // Proceed with PHP email
    $headers = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type:text/html;charset=UTF-8' . "\r\n";
    $headers .= 'From: <contact@maisondescours.ma>' . "\r\n"; // As an example, the 'From' address should be set to something like 'noreply@yourdomain.com' in order to be based on the same domain as the form.
    $headers .= 'Reply-To: '.$_POST["userEmail"]."\r\n";
    
    'X-Mailer: PHP/' . phpversion();
    
    // Body of the Email received in your Mailbox
    $emailcontent = 'Hey! You have received a new message from the visitor <strong>'.$_POST["userName"].'</strong><br/><br/>'. "\r\n" .
                'His message: <br/> <em>'.$_POST["userMessage"].'</em><br/><br/>'. "\r\n" .
                '<strong>Feel free to contact '.$_POST["userName"].' via email at : '.$_POST["userEmail"].'</strong>' . "\r\n".
                'or on'.$_POST["userPhone"].'</strong>' . "\r\n";
                
    $Mailsending = @mail($to_Email, $subject, $emailcontent, $headers);
   
    if(!$Mailsending) {
        
        //If mail couldn't be sent output error. Check your PHP email configuration (if it ever happens)
        $output = json_encode(array('type'=>'error', 'text' => '<i class="fas fa-times"></i> Oops! Looks like something went wrong, please check your PHP mail configuration.'));
        die($output);
        
    } else {
        $output = json_encode(array('type'=>'message', 'text' => '<i class="fas fa-check"></i> Merci '.$_POST["userName"] .', Votre message a été envoyé avec succès. '));
        die($output);
    }
}
?>