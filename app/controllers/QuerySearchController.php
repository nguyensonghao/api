<?php 

// Tim kiem full text search

require_once public_path() . '/vendor/autoload.php';

class QuerySearchController {
	public $client;
	public $convert;

	public function __construct() {
		$this->client = Elasticsearch\ClientBuilder::create()
	    ->setHosts(["localhost:9200"])
	    ->setRetries(0)
	    ->build();
	    $this->convert = new ConvertResultController();
	}

	public function actionSearchExample ($key) {
		$params = [
		    'index' => 'mazii',
		    'type'  => 'exam',
		    'body'  => [
		        'query' => [
		            'multi_match' => [
		            	'query'  => $key,
		            	'fields' => array('eng', 'jap')
		            ]
		        ]
		    ]
		];

		$results = $this->client->search($params);
		return $this->convert->convertExample($results);
	}

	public function actionSearchJaen ($key) {
		$params = [
		    'index' => 'mazii',
		    'type'  => 'jaen',
		    'from'  => 0,
		    'size'  => 100,
		    'body'  => [
		        'query' => [
		            'multi_match' => [
		            	'query'  => $key,
		            	'fields' => array('_word', 'phonetic', 'mean')
		            ]
		        ]
		    ]
		];
			
		$results = $this->client->search($params);
		return $this->convert->convertJaen($results, $key);
	}

	public function actionSearchKanji ($key, $numberRecord) {
		$params = [
		    'index' => 'mazii',
		    'type'  => 'kanji',
		    'from'  => 0,
		    'body'  => [
		        'query' => [
		            'multi_match' => [
		            	'query'  => $key,
		            	'fields' => array('kanji', 'mean', 'on', 'kun')
		            ]
		        ]
	        ]
		];

		$results = $this->client->search($params);
		return $this->convert->convertKanji($results, $key, $numberRecord);
	}

	public function actionSearhJaenSimple ($key) {
		$params = [
		    'index' => 'mazii',
		    'type'  => 'jaen',
		    'from'  => 0,
		    'size'  => 10,
		    'body'  => [
		        'query' => [
		            'multi_match' => [
		            	'query'  => $key,
		            	'fields' => array('_word', 'phonetic', 'mean')
		            ]
		        ]
		    ]
		];

		$results = $this->client->search($params);
		return $results;
	}

	public function getJLPT ($begin, $level) {
		$params = [
		    "size"  => 100,
		    "from"  => $begin * 100,
		    "index" => "mazii",
		    "type"  => 'kanji',
		    "body"  => [
		    	'query' => [
			        'filtered' => [
		                'filter' => [
		                    'term'  => [ 'jlpt' => $level ],
		                ],
		            ]
		        ]
		    ]
		];

		$results = $this->client->search($params); 
		return $this->convert->convertGetKanji($results);
	}

}

?>
