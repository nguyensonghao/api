<?php 
require public_path() . "/libs/PHPMailer/class.phpmailer.php";
require public_path() . "/libs/PHPMailer/class.smtp.php";

class EmailController extends BaseController {

    public function sendMail ($keyActive, $email) {
    	$mail = new PHPMailer();
    	$mail->IsSMTP();
        $mail->SMTPDebug  = 0;
        $mail->Debugoutput = "html";
        $mail->Host       = "box308.bluehost.com";
        $mail->Port       = 465;
        $mail->SMTPSecure = "ssl";
        $mail->SMTPAuth   = true;
        $mail->CharSet = "UTF-8";
        $mail->Username   = "support@mazii.net";
        $mail->Password   = "{i(R+g@p9J%T";
        $mail->SetFrom("support@mazii.net", "Từ điển Mazii");
        $mail->AddReplyTo("support@mazii.net","Từ điển Mazii");
        $mail->AddAddress($email, $email);
        $mail->Subject = "Kích hoạt tài khoản";
        $mail->MsgHTML("Chào bạn " .$email. " <br>
        Cảm ơn bạn đã đăng ký tài khoản để sử dụng tính năng nâng cao của Mazii. <br>
        Hãy nhấn vào link dưới đây để kích hoạt tài khoản 
        http://api.mazii.net/api/active/" . $keyActive);
        return $mail->Send();
    }
}

?>