<?php 

class WordController extends BaseController {

	public $word;
	public $subject;
	public $admin;

	public function __construct () {
		$this->beforeFilter('login-systerm');
		$this->word = new Word();
		$this->subject = new Subject();
		$this->admin = new Admin();
	}

	public function showHome () {
		$list['listWord'] = $this->word->getList();
		return View::make('data.list-data', $list);
	}

	public function showListImage () {
		$list['listWord'] = $this->word->getList();
		return View::make('data.list-image', $list);
	}

	public function showListImageExcuted ($id_course) {
		$list['listWord'] = $this->word->getListExcute($id_course);		
		for ($i = 0; $i < count($list['listWord']); $i++) {
			$word = str_replace("'", "", $list['listWord'][$i]->word);
			$list['listWord'][$i]->word = $word;
			$mean = str_replace("'", "", $list['listWord'][$i]->mean);
			$list['listWord'][$i]->mean = $mean;
			$list['listWord'][$i]->course_name = $this->convertNameCourse($list['listWord'][$i]->id_course);
		}
		return View::make('data.list-image-excuted', $list);
	}

	public function showListImageNotExcuted ($id_course, $id_subject) {
		$list['listWord'] = $this->word->getListNotExcute($id_course, $id_subject);
		$list['listSubject'] = $this->subject->getList($id_course);
		Session::set('select_subject', $id_subject);
		for ($i = 0; $i < count($list['listWord']); $i++) {
			$word = str_replace("'", "", $list['listWord'][$i]->word);
			$list['listWord'][$i]->word = $word;
			$mean = str_replace("'", "", $list['listWord'][$i]->mean);
			$list['listWord'][$i]->mean = $mean;
			$list['listWord'][$i]->course_name = $this->convertNameCourse($list['listWord'][$i]->id_course);
		}
		return View::make('data.list-image', $list);
	}

	public function showLogin () {
		if (Auth::check()) {
			return Redirect::to('danh-sach-anh-chua-duyet/101000000/all');
		} else {
			return View::make('data.login');
		}
		
	}

	public function showAddAdmin () {
		if (Auth::user()->active != 10) {
			return Redirect::to('dang-nhap');
		} else {
			return View::make('data.add-admin');
		}		
	}

	public function showExportData () {
		$listSubject = Subject::all();
		$list['listSubject'] = [];
		foreach ($listSubject as $key => $value) {
			$found = false;
			$size = count($list['listSubject']);
			$id_course = $value->id_course;
			for ($i = 0; $i < $size; $i++) {
				if ($size == 0) {
					break;
				} else {
					if ($list['listSubject'][$i]['id_course'] == $id_course) {
						$found = true;
						break;
					}
				}				
			}
			if (!$found) {
				$subject = array(
					'id_course' => $id_course,
					'count'     => Word::where('id_course', $id_course)->count(),
					'countSubject' => Subject::where('id_course', $id_course)->count()
				);
				array_push($list['listSubject'], $subject);
			}			
		}

		return View::make('data.export-data', $list);
	}

	public function actionCompleteImage () {
		$id = $_POST['id'];
		if ($this->word->completeImage($id)) {
			return Response::json(array('status' => 200));
		} else {
			return Response::json(array('status' => 304));
		}
	}

	public function actionLogin () {
		$username = $_POST['username'];
		$password = $_POST['password'];
		if ($username == null || $password == null || $username == '' || $password == '') {
			return Redirect::back()->with('error', 'Tài khoản hoặc mật khẩu không đúng');
		} else {
			$user = $array = array('username' => $username, 'password' => $password, 'tokenId' => '1' );
			if (Auth::attempt($user)) {
				if (Auth::user()->status == -1) {
					return Redirect::back()->with('error', 'Tài khoản đã bị khóa');
				} else {
					return Redirect::to('danh-sach-anh-chua-duyet/101000000/all');
				}				
			} else {
				return Redirect::back()->with('error', 'Tài khoản hoặc mật khẩu không đúng');
			}
		}
	}

	public function actionLogout () {
		Auth::logout();
		return Redirect::to('dang-nhap')->with('error', 'Bạn vừa đăng xuất hệ thống');
	}

	public function actionAddAdmin () {
		$username = $_POST['username'];
		$password = $_POST['password'];
		$password_confirm = $_POST['password_confirm'];
		if ($username == null || $username == '' || $password_confirm == '' || $password_confirm == null) {
			return Redirect::back()->with('error', 'không được để trống');
		} else {
			if ($password != $password_confirm) {
				return Redirect::back()->with('error', 'mật khẩu không khớp');
			} else {
				$user = new User();
				$user->username = $username;
				$user->password = Hash::make($password);
				$user->tokenId = 1;
				$user->id = User::count();
				if ($user->save()) {
					return Redirect::back()->with('notify', 'thêm tài khoản thành công');
				} else {
					return Redirect::back()->with('error', 'có lỗi xử lý');
				}

			}						
		}
	}

	public function actionGetImageUrl () {
		$id = $_POST['id'];
		$word = DB::table('words')->where('id', $id)->first();
		if (isset($_POST['newWord']) && $_POST['newWord'] != null && $_POST['newWord'] != '') {
			$word->url = $this->getUrlImage(0, $_POST['newWord']);
		} else {
			$word->url = $this->getUrlImage(0, $word->word);
		}			
		return Response::json($word);
	}

	public function getUrlImage ($start, $query) {
		try {
			$query = rawurlencode($query);
			$data = file_get_contents('https://www.googleapis.com/customsearch/v1element?key=AIzaSyCVAXiUzRYsML1Pv6RwSG1gunmMikTzQqY&rsz=filtered_cse&start='. $start .'&num=20&hl=en&prettyPrint=true&source=gcsc&gss=.com&searchtype=image&q=' . $query . '&cx=011716203299611176711:o1y_nlme1qq');
			$data = json_decode($data);
			$result = $data->results;
			return $result;	
		} catch (Exception $e) {
			return null;
		}
	}

	public function actionLoadMoreImageUrl () {
		$id = $_POST['id'];
		$start = $_POST['start'];
		$word = DB::table('words')->where('id', $id)->first();
		$word->url = $this->getUrlImage($start, $word->word);
		return Response::json($word);		
	}

	public function actionDownloadImage () {
		try {
			$id = $_POST['id'];
			$url = $_POST['url'];
			$word = DB::table('words')->where('id', $id)->first();
			$fileName = $word->id_word . '.jpg';
			$courseId = $word->id_course;
			$courseName = $this->convertNameCourse($courseId);		
			$toPath = public_path() . '/AllData/' . $courseName . '/' . $courseId . '/images/words/';
			$filePath = public_path() . '/thumbnail';
			if ($this->download_and_crop_image($filePath, $fileName, $url, 254, 334)) {
				if ($this->resize_image($filePath, $toPath, $fileName, 254, 334)) {
					return Response::json(array('status' => 200));
				} else {
					return Response::json(array('status' => 304));
				}
			} else {
				return Response::json(array('status' => 304));
			}	
		} catch (Exception $e) {
			return Response::json(array('status' => 304));
		}		
	}

	public function actionFixMean () {
		$id   = $_POST['id'];
		$mean = $_POST['mean'];		
		$word = $_POST['word'];
		$des  = $_POST['des'];
		$phonectic = $_POST['phonectic'];
		if ($this->word->updateMean($id, $mean, $phonectic, $word, $des)) {
			return Response::json(array('status' => 200));
		} else {
			return Response::json(array('status' => 304));
		}
	}
	
	public function download_and_crop_image ($file_path, $file_name, $url_download, $h, $w) {
		try {
			$img = file_get_contents($url_download);
			$im  = imagecreatefromstring($img);
			$width = imagesx($im);
			$height = imagesy($im);
			if (($width / $height) > ($w / $h)) {
				$crop_width  = $height * $w / $h;
				$crop_height = $height;
			} else {
				$crop_width  = $width;
				$crop_height = $width * $h / $w;
			}

			$crop_measure  = min($crop_width, $crop_height);
			$to_crop_array = array('x' =>0 , 'y' => 0, 'width' => $crop_width, 'height'=> $crop_height);
			$thumb_im = imagecrop($im, $to_crop_array);
			imagejpeg($thumb_im, $file_path . '/' . $file_name);
			return true;
		} catch (Exception $e) {
			return false;
		}
	}
	
	public function resize_image ($to_path, $from_path, $file_name, $h, $w) {
		try {
			$resize = new ResizeController();
		   	$resize->load($to_path . '/' . $file_name);
		   	$resize->resize($w, $h);
		   	$resize->save($from_path . '/' . $file_name);			
		   	return true;
		} catch (Exception $e) {
			return false;
		}
	}

	public function actionExportData ($id_course) {
		try {
			$course_name = $this->convertNameCourse($id_course);
			$strListWord = json_encode(Word::select('id_word', 'id_subject', 'id_course', 'word', 'mean', 'example', 'example_mean', 'num_ef', 'time_date', 'next_time', 'num_n', 'num_i', 'max_q', 'phonectic', 'des')->where('id_course', $id_course)->get());
			$strListSubject = json_encode(Subject::select('id', 'name', 'id_course', 'mean', 'total', 'num_word', 'time_date')->where('id_course', $id_course)->get());
			$fileNameWord = public_path() . '/AllData/' . $course_name . '/' . $id_course . '/json/words.json';
			$fileNameSubject = public_path() . '/AllData/' . $course_name . '/' . $id_course . '/json/subject.json';
			$fileWord = fopen($fileNameWord, "w");
			$fileSubject = fopen($fileNameSubject, "w");
			if (fwrite($fileWord, $strListWord) && fwrite($fileSubject, $strListSubject)) {
				fclose($fileWord);
				fclose($fileSubject);
				return Redirect::to('xuat-du-lieu')->with('notify', 'Xuất dữ liệu mã khóa học '.$id_course.' thành công');
			} else {
				fclose($fileWord);
				fclose($fileSubject);
				return Redirect::to('xuat-du-lieu')->with('error', 'Có lỗi trong quá trình xử lý');
			}			
		} catch (Exception $e) {
			Log::info($e);
			return Redirect::to('xuat-du-lieu')->with('error', 'Có lỗi trong quá trình xử lý');
		}		
	}

	public function convertNameCourse ($id_course) {
		$id = (int)($id_course / 1000000);
		switch ($id) {
			case 101:
				return 'English';

			case 102:
				return 'Chinese';

			case 103:
				return 'Korea';

			default:
				return 'Japanese';
		}
	}

	public static function actionActiveMenu ($type) {
		$name = Request::segment(2);
		if ($type == $name) {
			return 'active';
		} else return '';
	}	

}


?>