<?php 

// Import dữ liệu vào trong elasticsearch
// Link hỗ trợ tìm hiểu "https://www.elastic.co/guide/en/elasticsearch/client/php-api/current/_indexing_documents.html"

require_once public_path() . '/vendor/autoload.php';

class ImportElasticController extends BaseController {
	
	public $sqlite;
	public $client;

	public function __construct() {
		$this->sqlite = new ExcuteSqliteController();
		$this->sqlite->openDatabase();
		$this->client = Elasticsearch\ClientBuilder::create()
	    ->setHosts(["localhost:9200"])
	    ->setRetries(0)
	    ->build();
	}

 	public function importExample () {
 		ini_set('max_execution_time', 600000000);
 		$listExam = $this->sqlite->queryAllofTable('exam_fts'); 		
 		$params = ['body' => []];

		for ($i = 0; $i < count($listExam); $i++) {
			$exam = $listExam[$i];
			$params['body'][] = [
			    'index' => [
			        '_index' => 'mazii',
			        '_type' => 'exam',
			        '_id' => $i
			    ]
			];

			$params['body'][] = [
			    'id'  => $exam['id'],
		    	'eng' => $exam['eng'], 
		    	'jap' => $exam['jap']
			];

			// Nếu trên 1000 record thì bulk 1 lần
			if ($i % 1000 == 0) {
			    $responses = $this->client->bulk($params);

			    $params = ['body' => []];

			    unset($responses);
			}
		}

		if (!empty($params['body'])) {
			$responses = $this->client->bulk($params);
		}
 	}


 	public function importJaen () {
 		ini_set('max_execution_time', 600000000);
 		$listJaen = $this->sqlite->queryAllofTable('jaen_fts'); 		
 		$params = ['body' => []];

		for ($i = 0; $i < count($listJaen); $i++) {
			$jaen = $listJaen[$i];
			$params['body'][] = [
			    'index' => [
			        '_index' => 'mazii',
			        '_type'  => 'jaen',
			        '_id'    => $i
			    ]
			];

			$params['body'][] = [
			    'id'       => $jaen['id'],
			    '_word'    => $jaen['word'],
		    	'phonetic' => $jaen['phonetic'],
		    	'mean'     => $jaen['mean'],
		    	'seq'      => $jaen['seq']
			];

			// Nếu trên 1000 record thì bulk 1 lần
			if ($i % 1000 == 0) {
			    $responses = $this->client->bulk($params);

			    $params = ['body' => []];

			    unset($responses);
			}
		}

		if (!empty($params['body'])) {
			$responses = $this->client->bulk($params);
		}
 	}

 	public function importKanji () {
 		ini_set('max_execution_time', 600000000); 
 		$listKanji = $this->sqlite->queryAllofTable('kanji_fts'); 		
 		$params = ['body' => []];

		for ($i = 0; $i < count($listKanji); $i++) {
			$kanji = $listKanji[$i];
			$params['body'][] = [
			    'index' => [
			        '_index' => 'mazii',
			        '_type' => 'kanji',
			        '_id' => $i
			    ]
			];

			$params['body'][] = [
			    'kanji'  => $kanji['kanji'], 
		    	'mean'   => $kanji['mean'],
		    	'jlpt'   => $kanji['jlpt'],
		    	'seq'    => $kanji['seq'],
		    	'stroke' => $kanji['stroke'],
		    	'on'     => $kanji['on'],
		    	'kun'    => $kanji['kun']
			];

			// Nếu trên 1000 record thì bulk 1 lần
			if ($i % 1000 == 0) {
			    $responses = $this->client->bulk($params);

			    $params = ['body' => []];

			    unset($responses);
			}
		}

		if (!empty($params['body'])) {
			$responses = $this->client->bulk($params);
		}

 	}

}

?>