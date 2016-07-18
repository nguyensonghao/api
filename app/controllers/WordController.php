<?php 

class WordController extends BaseController {

	public $word;
	public $subject;
	public $admin;
	public $course;
	public $wordClone;
	public $subjectClone;
	public $validate;

	public function __construct () {
		$this->beforeFilter('login-systerm');
		$this->word = new Word();
		$this->subject = new Subject();
		$this->course = new Course();
		$this->admin = new Admin();
		$this->wordClone = new WordClone();
		$this->subjectClone = new SubjectClone();
		$this->validate = new ValidateController();
		DB::connection()->disableQueryLog();
	}

	public function showHome () {
		$list['listWord'] = $this->word->getList();
		return View::make('data.list-data', $list);
	}

	public function showListImage () {
		$list['listWord'] = $this->word->getList();
		return View::make('data.list-image', $list);
	}

	public function showListImageExcuted ($id_course, $id_subject) {
		$list['listWord'] = $this->word->getListExcute($id_course, $id_subject);
		$list['listSubject'] = $this->subject->getList($id_course);
		$list['listCourse']  = $this->course->getList($id_course);
		Session::set('select_subject', $id_subject);
		Session::set('select_course', $id_course);
		for ($i = 0; $i < count($list['listWord']); $i++) {			
			$list['listWord'][$i]->course_name = $this->convertNameCourse($list['listWord'][$i]->id_course);
		}		
		return View::make('data.list-image-excuted', $list);
	}

	public function showListImageNotExcuted ($id_course, $id_subject) {
		$list['listWord'] = $this->word->getListNotExcute($id_course, $id_subject);
		$list['listSubject'] = $this->subject->getList($id_course);
		$list['listCourse']  = $this->course->getList($id_course);
		Session::set('select_subject', $id_subject);
		Session::set('select_course', $id_course);
		for ($i = 0; $i < count($list['listWord']); $i++) {			
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

	public function showExportDataSubject () {		
		$list['listCourse'] = Course::get();
		return View::make('data.export-data-subject', $list);
	}

	public function showExportDataCourse () {
		$listCourse = Course::get();
		$listCourseSort = [];		
		$size = count($listCourse);
		for ($i = 0; $i < $size; $i++) {
			$course = array(
				'id' => $listCourse[$i]->id,
				'name' => $listCourse[$i]->name,
				'desc' => $listCourse[$i]->desc,
				'subject' => Subject::where('id_course', $listCourse[$i]->id)->count(),
				'word' => Word::where('id_course', $listCourse[$i]->id)->count()
			);			
			array_push($listCourseSort, $course);
		}

		$list['listCourse'] = $listCourse;		
		$list['listCourseSort'] = $listCourseSort;

		return View::make('data.list-course', $list);
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
			$data = file_get_contents('https://www.googleapis.com/customsearch/v1element?key=AIzaSyCVAXiUzRYsML1Pv6RwSG1gunmMikTzQqY&rsz=filtered_cse&start='. $start .'&num=20&hl=en&prettyPrint=true&source=gcsc&gss=.com&searchtype=image&q=' . $query . '&cx=011716203299611176711:fmop1dxcjq4');
			$data = json_decode($data);
			$result = $data->results;
			return $result;	
		} catch (Exception $e) {
			Log::info($e);
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
					unlink(public_path() . '/thumbnail/' . $fileName);
					return Response::json(array('status' => 200));
				} else {
					return Response::json(array('status' => 304));
				}
			} else {
				return Response::json(array('status' => 304));
			}	
		} catch (Exception $e) {
			Log::info($e);
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
			imagedestroy($im);
			return true;
		} catch (Exception $e) {
			Log::info($e);
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
			Log::info($e);
			return false;
		}
	}

	public function actionExportData ($id_course) {
		try {
			$course_name = $this->convertNameCourse($id_course);
			$strListWord = json_encode(Word::select('id_word', 'id_subject', 'id_course', 'word', 'mean', 'example', 'example_mean', 'num_ef', 'time_date', 'next_time', 'num_n', 'num_i', 'max_q', 'phonetic', 'des')->where('id_course', $id_course)->get());
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

	public function sortDataSubject ($id_course) {
		$list['course'] = Course::where('id', $id_course)->first();
		$listSubject = Subject::select('id', 'name', 'id_course', 'mean', 'total', 'num_word', 'time_date')
		->where('id_course', $id_course)->get();		
		$listSubjectSort = [];		
		$size = count($listSubject);
		for ($i = 0; $i < $size; $i++) {
			$subject = array(
				'id' => $listSubject[$i]->id,
				'name' => $listSubject[$i]->name,
				'id_course' => $listSubject[$i]->id_course,
				'mean' => $listSubject[$i]->mean,
				'total' => Word::where('id_subject', $listSubject[$i]->id)->count(),
				'num_word' => $listSubject[$i]->num_word,
				'time_date' => $listSubject[$i]->time_date,
			);			
			array_push($listSubjectSort, $subject);
		}

		$list['listSubject'] = $listSubject;		
		$list['listSubjectSort'] = $listSubjectSort;

		return View::make('data.list-subject', $list);
	}

	public function sortDataSubjectJson ($id_course) {
		// Check public course
		$checkPublic = Course::where('id', $id_course)->first()->status;
		if (is_null($checkPublic) || $checkPublic == 0) {
			return Redirect::back()->with('error', 'Khóa học chưa được public');
		}
		
		ini_set('max_execution_time', 600000000);
		$listSubject = Subject::select('id', 'name', 'id_course', 'mean', 'total', 'num_word', 'time_date')		
		->where('id_course', $id_course)->get();		
		$size = count($listSubject);
		for ($i = 0; $i < $size; $i++) {
			$subject = $listSubject[$i];
			$count = Word::where('id_subject', $subject->id)->count();
			Subject::where('id', $subject->id)->update(array('total' => $count));
		}
		try {
			$course_name = $this->convertNameCourse($id_course);			
			$strListSubject = json_encode(Subject::select('id', 'name', 'id_course', 'mean', 'total', 'num_word', 'time_date')->where('id_course', $id_course)->get());
			$strListWord = json_encode(Word::select('id_word', 'id_subject', 'id_course', 'word', 'mean', 'example', 'example_mean', 'num_ef', 'time_date', 'next_time', 'num_n', 'num_i', 'max_q', 'phonetic', 'des')->where('id_course', $id_course)->get());

			// Create folder storage json file
			$filePathSubject = public_path() . '/AllData/' . $course_name . '/' . $id_course . '/json/';
			$filePathWord = public_path() . '/AllData/' . $course_name . '/' . $id_course . '/json/';
			$this->createFolder($filePathSubject);
			$this->createFolder($filePathWord);
			$fileNameSubject = $filePathSubject . 'subject.json';
			$fileNameWord = $filePathWord . 'words.json';
			$fileSubject = fopen($fileNameSubject, "w");
			$fileWord = fopen($fileNameWord, "w");			

			// Write json string to file
			if (fwrite($fileSubject, $strListSubject) && fwrite($fileWord, $strListWord)) {
				fclose($fileSubject);
				fclose($fileWord);
				return Redirect::back()->with('notify', 'Xuất dữ liệu thành công');
			} else {				
				fclose($fileSubject);
				fclose($fileWord);
				return Redirect::back()->with('error', 'Có lỗi trong quá trình xuất dữ liệu');
			}			
		} catch (Exception $e) {			
			return Redirect::back()->with('error', 'Có lỗi trong quá trình xuất dữ liệu' . $e);
		}		
	}

	public function sortDataCourseJson () {
		ini_set('max_execution_time', 600000000);
		$listCourse = Course::select('id', 'name', 'desc', 'subject', 'word')->get();
		$size = count($listCourse);
		for ($i = 0; $i < $size; $i++) {
			$course = $listCourse[$i];
			$subject = Subject::where('id_course', $course->id)->count();
			$word = Word::where('id_course', $course->id)->count();
			Course::where('id', $course->id)->update(array('subject' => $subject, 'word' => $word));
		}
		try {			
			$strListCourse = json_encode(Course::select('id', 'name', 'desc', 'subject', 'word', 'status', 'srclang', 'deslang')->where('status', 1)->get());
			$fileNameCourse = public_path() . '/AllData/courses.json';			
			$fileCourse = fopen($fileNameCourse, "w");
			if (fwrite($fileCourse, $strListCourse)) {
				fclose($fileCourse);
				return Redirect::back()->with('notify', 'Xuất dữ liệu thành công');
			} else {				
				fclose($fileCourse);
				return Redirect::back()->with('error', 'Có lỗi trong quá trình xuất dữ liệu');
			}			
		} catch (Exception $e) {			
			return Redirect::back()->with('error', 'Có lỗi trong quá trình xuất dữ liệu');
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

			case 104:
				return 'Japanese';

			default:
				return 'Other';
		}
	}

	public function showImportData () {
		$list['importStatus'] = DB::table('import_status')->first();
		return View::make('data.import-data', $list);
	}

	protected function getImageUrlWordByWord ($word) {
		$result = [];
		if (!is_null($word)) {
			try {
				$url = $this->getUrlImage(0, $word);
				if (!is_null($url))
					$result = [$url[0], $url[1], $url[2]];	
			} catch (Exception $e) {
				$result = [];
			}			
		}

		return $result;
	}

	public function actionImportData () {
		ini_set('max_execution_time', 600000000);
		if (Input::hasFile('file')) {
			try {
				Input::file('file')->move(public_path() . '/import', 'data.json');	
			} catch (Exception $e) {
				return Redirect::back()->with('error', 'Lỗi upload file ' . $e->getMessage());
			}			

			// Load file json then import database
			$filePath = public_path() . '/import/data.json';
			try {
				$data = file_get_contents($filePath);
				$data = json_decode($data, true);
				$dataCourse = $data['course'];
				$dataSubject = $data['subjects'];
				$dataWord = $data['words'];

				// Check exits courseName
				if (!is_null(Course::where('name', $dataCourse['name'])->first())) {
					return Redirect::back()->with('error', 'Tên khóa học bị trùng');
				}

				// Validate fields is empty
				if (!$this->checkEmpty([$dataCourse['srclang'], $dataCourse['deslang'], $dataCourse['name'], $dataCourse['id'], $dataCourse
					['word'], $dataCourse['subject']])) {
					return Redirect::back()->with('error', 'Kiểm tra lại những trường bạn bắt buộc phải điền trong course: id, name, srclang, deslang, subject, word');
				}

				// get Last id of course and auto increament
				$idCourse = $dataCourse['id'];
				$idCourse = $this->course->getLastIdCourse($idCourse) + 1;				
				$dataCourse['id'] = $idCourse;

				// Validate id course
				$typeCourse = $this->convertNameCourse($idCourse);
				if ($typeCourse == 'Other') {
					return Redirect::back()->with('error', 'Có vẻ như id course không hợp lệ, kiểm tra lại');
				}

				$dataCourse['type'] = $typeCourse;
				$sizeSubject = count($dataSubject);
				$sizeWord = count($dataWord);
				
				// Import course into database
				if (DB::table('courses')->insert($dataCourse)) {
					// Show notify importing...
					$this->changeStatusImport($idCourse, $dataCourse['name'], 1);

					// Get last id subject of course and increament subjectId
					$lastSubjectId = $this->subject->getLastIdSubject($idCourse);

					// Insert subject into database
					for ($i = 0; $i < $sizeSubject; $i++) {
						// Create new subjectId and reset value of subject
						++ $lastSubjectId;
						$id = $lastSubjectId;
						$listStorgeSubject[$dataSubject[$i]['id']] = $lastSubjectId;
						$dataSubject[$i]['id'] = $id;
						$dataSubject[$i]['id_course'] = $idCourse;
						$name = $dataSubject[$i]['name'];

						if ($this->subject->insertSubject(($dataSubject[$i]))) {
							// Insert subject into subjectclone table to check download status							
							$this->subjectClone->insertSubjectClone($dataSubject[$i]);							

							// Get url download word
							$listUrl = $this->getImageUrlWordByWord($name);
							if ($listUrl == []) {								
								Log::info('Get url word ' . $name . ' error');
							} else {
								// Insert url into subjectclone
								$this->subjectClone->updateUrlDownload($id, $listUrl);

								// Download image with url
								for ($j = 0; $j < count($listUrl); $j++) {
									$url = $listUrl[$j]->url;
									$filePath = public_path() . '/thumbnail/subject/';
									// Create folder subject in thumbnail
									if ($this->createFolder($filePath)) {
										$fileName = $id . '.jpg';
										if ($this->download_and_crop_image($filePath, $fileName, $url, 334, 254)) {
											// Create folder storge image of lessons
											$filePathImage = public_path() . '/AllData/' . $typeCourse . '/' . $dataCourse['id'] . '/images/lessons/';
											$this->createFolder(public_path() . '/AllData/' . $typeCourse);
											$this->createFolder(public_path() . '/AllData/' . $typeCourse . '/' . $dataCourse['id']);
											$this->createFolder(public_path() . '/AllData/' . $typeCourse . '/' . $dataCourse['id'] . '/images');
											$this->createFolder($filePathImage);																						

											// Resize image after download
											if ($this->resize_image($filePath, $filePathImage, $fileName, 254, 334)) {
												// remove file thumbnail in folder thumbnail/subject
												unlink($filePath . $fileName);

												// Update status download success into subjectclone
												$this->subjectClone->changeStatus($id, 2);
											}

											break;
										}
									} else {
										return Redirect::back()->with('error', 'Có lỗi trong quá trình tạo thư mục public/thumbnail/subject');
									}									
								}
							}
						} else {
							return Redirect::back()->with('error', 'Có lỗi trong quá trình thêm subject');
						}
					}

					// Insert word into database

					// Get last wordID and auto increament
					$lastWordId = $this->word->getLastIdWord($idCourse);

					for ($i = 0; $i < $sizeWord; $i++) {
						// Create new wordId and reset value of wordId
						++ $lastWordId;
						$word = $dataWord[$i]['word'];
						$dataWord[$i]['id_subject'] = $listStorgeSubject[$dataWord[$i]['id_subject']];
						$dataWord[$i]['id_course'] = $idCourse;
						$dataWord[$i]['id_word'] = $lastWordId;
						$id = $lastWordId;

						if ($this->word->insertWord($dataWord[$i])) {

							// Insert word into wordclone table to check status download of word
							$this->wordClone->insertWordClone($dataWord[$i]);							

							// Get url download word
							$listUrl = $this->getImageUrlWordByWord($word);
							if ($listUrl == []) {								
								Log::info('Get url word ' . $word . ' error');
							} else {
								// Insert url into wordClone
								$this->wordClone->updateUrlDownload($id, $listUrl);

								// Download image with url
								for ($j = 0; $j < count($listUrl); $j++) {
									$url = $listUrl[$j]->url;
									
									// Create folder subject in thumbnail
									$filePath = public_path() . '/thumbnail/words/';
									$this->createFolder($filePath);
									$fileName = $id . '.jpg';

									// Download and resize image word
									if ($this->download_and_crop_image($filePath, $fileName, $url, 334, 254)) {
										// Create folder storge image of lessons
										$filePathImage = public_path() . '/AllData/' . $typeCourse . '/' . $dataCourse['id'] . '/images/words/';
										if ($this->createFolder($filePathImage)) {
											// Resize image after download
											if ($this->resize_image($filePath, $filePathImage, $fileName, 254, 334)) {
												// remove file thumbnail in folder thumbnail/subject
												unlink($filePath . $fileName);

												// Change status of word is download success
												$this->wordClone->changeStatus($id, 2);
											} else {
												echo 'Error resize image';
											}

											break;
										}
									}
								}
							}
						} else {
							return Redirect::back()->with('error', 'Có lỗi trong quá trình thêm word');
						}
					}

					$this->changeStatusImport($idCourse, $dataCourse['name'], 0);
					
					// Export data json 
					$this->exportData($idCourse);
					return Redirect::back()->with('notify', 'Thêm dữ liệu thành công');					
				} else {
					return Redirect::back()->with('error', 'Có lỗi trong quá trình thêm course');
				}				
			} catch (Exception $e) {
				Log::info($e);
				return Redirect::back()->with('error', 'Có lỗi trong quá trình import dữ liệu ' . $e->getMessage());
			}
		} else {
			return Redirect::back()->with('error', 'Bạn phải chọn file trước khi upload');
		}
	}

	public function showManagerCourse () {
		$list['listCourse'] = Course::paginate(10);
		return View::make('data.manager-course', $list);
	}

	public function actionPublicCoures ($idCourse, $status) {
		if (Course::where('id', $idCourse)->update(array('status' => $status))) {
			return Redirect::back()->with('notify', 'Chuyển trạng thái khóa học thành công');
		} else {
			return Redirect::back()->with('error', 'Có lỗi trong quá trình xử lý');
		}
	}

	protected function createFolder ($folder) {
		// Check exits folder first create
		try {
			if (!is_dir($folder)) {
				// if not exits folder then create folder
				mkdir($folder);
				return true;
			}
		} catch (Exception $e) {
			Log::info($e->getMessage());
			return false;
		}

		return true;
	}

	protected function checkEmpty ($array) {
		if (!is_array($array))
			return false;
		
		$size = count($array);
		for ($i = 0; $i < $size; $i++) {
			$item = (string) $array[$i];
			if (is_null($item))
				return false;
		}

		return true;
	}

	protected function changeStatusImport ($id, $name, $status) {
		$import = DB::table('import_status')->first();
		if (is_null($import)) {
			return DB::table('import_status')
			->insert(array('id' => $id, 'name' => $name, 'status' => $status));
		} else {
			return DB::table('import_status')
			->where('id', $import->id)->where('name', $import->name)
			->update(array('id' => $id, 'name' => $name, 'status' => $status));
		}
	}

	protected function crop_image ($filePath) {
		$ini_filename = $filePath;
		$im = imagecreatefromjpeg($ini_filename );

		$ini_x_size = getimagesize($ini_filename )[0];
		$ini_y_size = getimagesize($ini_filename )[1];
		
		$crop_measure = min($ini_x_size, $ini_y_size);

		$to_crop_array = array('x' =>0 , 'y' => 0, 'width' => $crop_measure, 'height'=> $crop_measure);
		$thumb_im = imagecrop($im, $to_crop_array);

		imagejpeg($thumb_im, $filePath, 100);
	}

	public static function actionActiveMenu ($type) {
		if (Request::segment(1) == $type) {
			return 'active';
		} else {
			$name = Request::segment(2);
			if ($type == $name) {
				return 'active';
			}
		}

		return '';
	}

	public function exportData ($id_course) {
		$listSubject = Subject::select('id', 'name', 'id_course', 'mean', 'total', 'num_word', 'time_date')		
		->where('id_course', $id_course)->get();		
		$size = count($listSubject);
		for ($i = 0; $i < $size; $i++) {
			$subject = $listSubject[$i];
			$count = Word::where('id_subject', $subject->id)->count();
			Subject::where('id', $subject->id)->update(array('total' => $count));
		}
		try {
			$course_name = $this->convertNameCourse($id_course);			
			$strListSubject = json_encode(Subject::select('id', 'name', 'id_course', 'mean', 'total', 'num_word', 'time_date')->where('id_course', $id_course)->get());
			$strListWord = json_encode(Word::select('id_word', 'id_subject', 'id_course', 'word', 'mean', 'example', 'example_mean', 'num_ef', 'time_date', 'next_time', 'num_n', 'num_i', 'max_q', 'phonetic', 'des')->where('id_course', $id_course)->get());
			$filePathSubject = public_path() . '/AllData/' . $course_name . '/' . $id_course . '/json/';
			$filePathWord = public_path() . '/AllData/' . $course_name . '/' . $id_course . '/json/';
			$this->createFolder($filePathSubject);
			$this->createFolder($filePathWord);
			$fileNameSubject = $filePathSubject . 'subject.json';
			$fileNameWord = $filePathWord . 'words.json';
			$fileSubject = fopen($fileNameSubject, "w");
			$fileWord = fopen($fileNameWord, "w");
			if (fwrite($fileSubject, $strListSubject) && fwrite($fileWord, $strListWord)) {
				fclose($fileSubject);
				fclose($fileWord);
				return true;
			} else {				
				fclose($fileSubject);
				fclose($fileWord);
				return false;
			}			
		} catch (Exception $e) {			
			return false;
		}	
	}

	public function actionUploadImage () {
		try {
			if(isset($_FILES["file"]["type"])) {
				$id = $_POST['id'];				
				if ($_FILES["file"]["size"] < 100000) {
					if ($_FILES["file"]["error"] > 0) {
						return Response::json(array('status' => -1));
					} else {						
						$word = DB::table('words')->where('id', $id)->first();
						$fileName = $word->id_word . '.jpg';
						$courseId = $word->id_course;
						$courseName = $this->convertNameCourse($courseId);		
						$toPath = public_path() . '/AllData/' . $courseName . '/' . $courseId . '/images/words/';
						$sourcePath = $_FILES['file']['tmp_name'];
						$targetPath = public_path() . '/thumbnail/upload/' . $fileName;
						if (move_uploaded_file($sourcePath, $targetPath)) {
							if ($this->download_and_crop_image(public_path() . '/thumbnail/upload/', $fileName, $targetPath, 254, 334)) {
								if ($this->resize_image(public_path() . '/thumbnail/upload/', $toPath, $fileName, 254, 334)) {
									unlink(public_path() . '/thumbnail/upload/' . $fileName);
									return Response::json(array('status' => 0));
								} else {
									return Response::json(array('status' => -1));
								}
							} else {
								return Response::json(array('status' => -1));
							}
						} else {
							return Response::json(array('status' => -1));
						}
					}
				}
			} else {
				return Response::json(array('status' => -2));
			}	
		} catch (Exception $e) {
			return Response::json(array('status' => -3, 'error' => $e->getMessage()));
		}		
	}

	public function actionSearch () {
		$keySearch = $_POST['search-enter'];
		if ($this->validate->validateSpecialChar($keySearch)) {
			$list['keySearch'] = $keySearch;
			$list['result'] = Word::where('word', 'like', '%' . $keySearch . '%')->skip(0)->take(30)->get();
		} else {
			$list['error'] = 'Từ khóa tìm kiếm chứa ký tự đặc biệt hoặc trống';			
		}
		return View::make('data.search', $list);
	}
}


?>