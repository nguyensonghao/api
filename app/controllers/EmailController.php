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
        Email : " .$email. "<br>
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
        Cảm ơn và chào mừng bạn đã đến với Mazii");
        return $mail->Send();
    }

    public function sendMailResetPassword ($keyReset, $email) {
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
        $mail->Subject = "Cấp lại mật khẩu";
        $mail->MsgHTML("Xin chào " .$email. " <br>
        Bạn vừa gửi một yêu cầu khôi phục mật khẩu trên Mazii.<br>
        Nếu bạn không tạo yêu cầu này, hãy bỏ qua email này. <br>
        Nếu đúng, click vào link dưới đây để tạo mật khẩu mới:
        http://api.mazii.net/api/reset/" . $keyReset . " <br><br>
        Trân trọng !<br>
        Mazii");
        return $mail->Send();
    }

    public function sendEmailResetPasswordSuccess ($email) {
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
        $mail->Subject = "Cấp lại mật khẩu thành công";
        $mail->MsgHTML("Xin chào " .$email. " <br>
        Tài khoản của bạn đã được thay đổi mật khẩu. Click vào link http://mazii.net để đăng nhập tài khoản trên Mazii.net.<br>
        Trân trọng <br>
        Mazii");
        return $mail->Send();
    }

    public function sendMailBlockAcount ($email, $reason, $banExpired) {
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
        $mail->Subject = "Cấp lại mật khẩu thành công";
        $mail->MsgHTML("Xin chào " .$email. " <br>
        Tài khoản của bạn đã bị khoá trên Mazii vì lý do: <br>
        " . $reason . "
        Thời hạn khoá đến ngày" . $banExpired . " <br>
        Xin hãy liên lạc với chúng tôi nếu lý do không phải như vậy. <br>
        Trân trọng! <br>
        Mazii");
        return $mail->Send();    
    }
}

?>