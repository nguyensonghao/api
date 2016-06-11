<?php 
require public_path() . "/libs/PHPMailer/class.phpmailer.php";
require public_path() . "/libs/PHPMailer/class.smtp.php";

class EmailController extends BaseController {

    public function sendMailActive ($keyActive, $email) {
        $contentEmail = "
Chào bạn " .$email. "
Bạn đã đăng ký thành công tài khoản trên Mazii.
Đây là thông tin tài khoản của bạn.
Email : " .$email. "
Xin hãy click vào link dưới đây để xác nhận tài khoản email của bạn.
http://api.mazii.net/api/active/" . $keyActive;

        $data = array (
            'email'   => $email,
            'content' => $contentEmail
        );

        return Mail::queue([], array('firstname'=> 'Từ điển Mazii'), function($message) use ($data) {
            $message->to($data['email'], $data['email'])->subject('Kích hoạt tài khoản')
            ->setBody($data['content']);
        });
    }

    public function sendMailActiveSuccess ($email) {
        $contentEmail = "Xin chào " .$email. "
Tài khoản của bạn đã được xác thực thành công.
Cảm ơn và chào mừng bạn đã đến với Mazii";

        $data = array (
            'email'   => $email,
            'content' => $contentEmail,
        );

        return Mail::queue([], array('firstname'=> 'Từ điển Mazii'), function($message) use ($data) {
            $message->to($data['email'], $data['email'])->subject('Kích hoạt tài khoản thành công')
            ->setBody($data['content']);
        });
    }

    public function sendMailResetPassword ($keyReset, $email) {
        $contentEmail = "
Xin chào " .$email. "
Bạn vừa gửi một yêu cầu khôi phục mật khẩu trên Mazii.
Nếu bạn không tạo yêu cầu này, hãy bỏ qua email này.
Nếu đúng, click vào link dưới đây để tạo mật khẩu mới:
http://api.mazii.net/api/reset/" . $keyReset . "

Trân trọng !
Mazii";

        $data = array (
            'email'   => $email,
            'content' => $contentEmail,
        );

        return Mail::queue([], array('firstname'=> 'Từ điển Mazii'), function($message) use ($data) {
            $message->to($data['email'], $data['email'])->subject('Cấp lại mật khẩu')
            ->setBody($data['content']);
        });
    }

    public function sendEmailResetPasswordSuccess ($email) {
        $contentEmail = "
Xin chào " .$email. "
Tài khoản của bạn đã được thay đổi mật khẩu. Click vào link http://mazii.net để đăng nhập tài khoản trên Mazii.net.
Trân trọng
Mazii";

        $data = array (
            'email'   => $email,
            'content' => $contentEmail,
        );

        return Mail::queue([], array('firstname'=> 'Từ điển Mazii'), function($message) use ($data) {
            $message->to($data['email'], $data['email'])->subject('Cấp lại mật khẩu thành công')
            ->setBody($data['content']);
        });
    }

    public function sendMailBlockAcount ($email, $reason, $banExpired) {
                $contentEmail = "
Xin chào " .$email. " <br>
Tài khoản của bạn đã bị khoá trên Mazii vì lý do:
" . $reason . "
Thời hạn khoá đến ngày" . $banExpired . "
Xin hãy liên lạc với chúng tôi nếu lý do không phải như vậy.
Trân trọng!
Mazii";
        $data = array (
            'email'   => $email,
            'content' => $contentEmail,
        );

        return Mail::queue([], array('firstname'=> 'Từ điển Mazii'), function($message) use ($data) {
            $message->to($data['email'], $data['email'])->subject('Khóa tài khoản')
            ->setBody($data['content']);
        });
    }
}

?>