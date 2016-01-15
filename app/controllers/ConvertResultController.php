<?php 
// Convert kết quả trả về sau khi tìm kiếm hợp với mẫu

class ConvertResultController {

	public function convertExample ($result) {
		$response = [];

		if (count($result['hits']['hits']) == 0) {
			$response['status'] = 304;
		} else {
			$response['status'] = 200;
			$response['results'] = [];

			$data = $result['hits']['hits'];
			$size = count($data);

			for ($i = 0; $i < $size; $i++) {

				$source = $data[$i]['_source'];
				$record = array(
					'_id'     => $data[$i]['_id'],
					'id'      => $source['id'],
					'content' => $source['eng'],
					'mean'    => $source['jap'],
					'transcription' => null
				);
				array_push($response['results'], $record);
			}
		}

		return $response;
	}

	public function convertJaen ($result, $key) {
		$response = [];

		if (count($result['hits']['hits']) == 0 || $result['hits']['max_score'] < 0.5) {
			$response['status'] = 304;
		} else {
			$response['status'] = 200;
			$response['data'] = [];

			$data = $result['hits']['hits'];
			$size = count($data);

			for ($i = 0; $i < $size; $i++) {

				$source = $data[$i]['_source'];
				$record = array(
					'_id'      => $data[$i]['_id'],
					'id'       => $data[$i]['_id'],
			    	'word'     => $source['_word'],
			    	'phonetic' => $source['phonetic'],
			    	'seq'      => $source['seq'],
				);

				// Convert mean to array
				$means = json_decode($result['hits']['hits'][$i]['_source']['mean']);
				$record['means'] = [];					

				for ($j = 0; $j < count($means); $j++) {
					$mean = $means[$j]->mean;
					if (!isset($means[$j]->kind))
						$kind = null;
					else
						$kind = $means[$j]->kind;

					array_push($record['means'], array(
						'mean' => $mean,
						'kind' => $kind
					));

				}
				array_push($response['data'], $record);
			}
		}

		return $response;
	}

	public function convertKanji ($result, $key, $numberRecord) {
		$response = [];

		if (count($result['hits']['hits']) == 0 || $result['hits']['max_score'] < 0.5) {
			$response['status'] = 304;
		} else {
			$response['status'] = 200;
			$response['results'] = [];

			$data = $result['hits']['hits'];
			$size = count($data);
			for ($i = 0; $i < $size; $i++) {
				if ($i == (int)$numberRecord)
					break;

				$source = $data[$i]['_source'];
				$record = array(
					'_id'          => $data[$i]['_id'],
					'kanji'        => $source['kanji'],
			    	'mean'         => $source['mean'],
			    	'level'        => $source['jlpt'],
			    	'seq'          => $source['seq'],
			    	'on'           => $source['on'],
			    	'kun'          => $source['kun'],
			    	'stroke_count' => $source['stroke']
				);

				// Convert example for kanji
				$query = new QuerySearchController();
				$resultExample = $query->actionSearhJaenSimple($key);
				$record['examples'] = [];

				$listExample = $resultExample['hits']['hits'];
				$sizeExample = count($listExample);
				for ($j = 0; $j < $sizeExample; $j++) {
					$sourceExample = $listExample[$j]['_source'];
					$recordExample = array(
						'p' => $sourceExample['phonetic'],
						'w' => $sourceExample['_word']
					);

					if (isset($sourceExample['mean']) && $sourceExample['mean'] != null) {
						$meanExample = json_decode($sourceExample['mean']);
						// Log::info(array_keys($meanExample));
						$recordExample['m'] = $meanExample[0]->mean;
						$recordExample['h'] = strtoupper($meanExample[0]->mean);
					}
					
					array_push($record['examples'], $recordExample);
				}

				array_push($response['results'], $record);
			}

		}

		return $response;
	}

	public function convertGetKanji ($result) {
		$response = [];

		if (count($result['hits']['hits']) == 0 || $result['hits']['max_score'] < 0.5) {
			$response['status'] = 304;
		} else {
			$response['status'] = 200;
			$response['results'] = [];
				$data = $result['hits']['hits'];
				$size = count($data);
				for ($i = 0; $i < $size; $i++) {

					$source = $data[$i]['_source'];
					$record = array(
						'id'    => $data[$i]['_id'],
						'key'   => $data[$i]['_id'],
				    	'value'	=> array(
				    		'id'	=> $data[$i]['_id'],
				    		'kanji' => $source['kanji'],
				    	)
					);

					$mean = strtoupper(str_replace(array('(', ')', '-'), '', explode("|", $source['mean'])[0]));
					$mean = explode(" ", $mean)[0];
					$record['value']['mean'] = $mean;
					array_push($response['results'], $record);

				}
		}

		return $response;


	}

}

?>