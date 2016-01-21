<?php 
require public_path() . "/libs/PHPMailer/class.phpmailer.php";
require public_path() . "/libs/PHPMailer/class.smtp.php";

class EmailController extends BaseController {

    public function sendMailActive ($keyActive, $email) {
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
        Bạn đã đăng ký thành công tài khoản trên Mazii. <br>
        Đây là thông tin tài khoản của bạn. <br>
        Email : " .$email. "
        Xin hãy click vào link dưới đây để xác nhận tài khoản email của bạn. <br>
        http://api.mazii.net/api/active/" . $keyActive);
        return $mail->Send();
    }

    public function sendMailActiveSuccess ($email) {
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
        $mail->Subject = "Kích hoạt tài thành công";
        $mail->MsgHTML("Xin chào " .$email. " <br>
        Tài khoản của bạn đã được xác thực thành công.
        Cảm ơn vào chào mừng bạn đã đến với Mazii");
        return $mail->Send();
    }
}

?>