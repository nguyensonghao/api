<?php 

class CrontabController extends BaseController {

	public $reportMean;

	public function __construct () {
		$this->reportMean = new ReportMean();
	}

	public function putNewReportCache () {
		Cache::forget('new-report');
		$reportCache = $this->reportMean->getNewCache();
    	Cache::put('new-report', $reportCache, 5);
	}

	public function sendEmailActive () {
		$mail = new EmailController();
		$listUserActive = DB::table('users')
		->get('active_user.key', 'active_user.email', 'active_user.id')
		->where('is_sendmail', 0)
		->join('active_user', 'active_user.id', '=', 'users.userId')
		->skip(0)->take(5)->get();
		$size = count($listUserActive);
		for ($i = 0; $i < $size; $i++) {
		    $key = $listUserActive[$i]->key;
		    $email = $listUserActive[$i]->email;
		    if ($mail->sendMailActive($key, $email)) {		    	
		    	DB::table('users')->where('userId', $listUserActive[$i]->id)->update(array('is_sendmail' => 1));
		    } else {
		    	DB::table('users')->where('userId', $listUserActive[$i]->id)->update(array('is_sendmail' => -1));
		    }
		}
	}

	public function sendEmailAcitveSuccess () {
		$mail = new EmailController();
		$listUserActiveSuccess = DB::table('active_user')->where('is_active', 1)
		->skip(0)->take(5)->get();
		$size = count($listUserActiveSuccess);
		for ($i = 0; $i < $size; $i++) {
		    $key = $listUserActiveSuccess[$i]->key;
		    $email = $listUserActiveSuccess[$i]->email;
		    if ($mail->sendMailActiveSuccess($email)) {		    	
		    	DB::table('active_user')->where('id', $listUserActiveSuccess[$i]->id)->update(array('is_active' => 2));
		    } else {
		    	DB::table('active_user')->where('id', $listUserActiveSuccess[$i]->id)->update(array('is_active' => -1));
		    }
		}
	}

	public function sendMailResetPassword () {

	}

	public function sendMailResetPasswordSuccess () {

	}

	public function crontabEmail () {
		$this->sendEmailActive();
		$this->sendEmailAcitveSuccess();
	}
}