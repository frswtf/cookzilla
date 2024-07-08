<?php
include_once 'dbconnect.php'; // Assuming this file establishes the connection

require 'vendor/autoload.php'; // Assuming this loads PHPMailer

if (isset($_POST['recover_password'])) {
  $uemail = $_POST['email'];
  $stmt = prepare("SELECT id FROM users WHERE uemail = ?");
    $stmt->bind_param("s", $uemail);
    $stmt->execute();
    $stmt->bind_result($id);
  
    if ($stmt->fetch()) {
      $stmt->close(); // Close the statement before preparing a new one
  
      // Generate a secure random code
      $code = rand(100000, 999999); // Example: 6 digit code
  
      // Generate expiry time (optional)
      $expires = date("U") + 1800; // Code expires after 30 minutes (optional)
  
      $stmt = $conn->prepare("UPDATE users SET reset_code = ?, reset_expires = ? WHERE id = ?");
      $stmt->bind_param("isi", $code, $expires, $id); // Update with code and expiry (optional)
      $stmt->execute();
  
      // Send the reset code to the user's email
      $message = "Your password reset code is: $code"; // Message with code
      $mail = new PHPMailer\PHPMailer\PHPMailer;
  
      $mail->isSMTP();
      $mail->Host = 'smtp.gmail.com';
      $mail->SMTPAuth = true;
      $mail->Username = 'svrgnxmaz@gmail.com';
      $mail->Password = 'rlog lycf jamz rvea'; // **Please remove this line before deploying!**
      $mail->SMTPSecure = 'tls';
      $mail->Port = 587;
  
      $mail->setFrom('from@example.com', 'No-Reply');
      $mail->addAddress($uemail);
      $mail->isHTML(false); // Set to plain text for code
  
      $mail->Subject = 'Password Reset Code';
      $mail->Body = $message;
  
      if (!$mail->send()) {
        echo 'Message could not be sent.';
        echo 'Mailer Error: ' . $mail->ErrorInfo;
      } else {
        echo 'Password reset code has been sent to your email!';
      }
    } else {
      echo "Invalid email or security answer.";
    }
  
    $stmt->close();
  }
  


?>

<!DOCTYPE html>
<html>
<body>
    <h2>Password Recovery</h2>
    <form method="post" action="">
        <input type="hidden" name="recover_password" value="1">
        Email: <input type="email" name="email"><br>
        <input type="submit" value="RecoverPassword">
    </form>
</body>
</html>
