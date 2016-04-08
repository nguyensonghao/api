<?php 

class WordController extends BaseController {

	public function addDataBase () {
		for ($i = 1; $i < 9; $i++) {
			$list_data = file_get_contents(public_path() . '/AllData/English/10100000' . $i . '/json/words.json');
			$list_data = json_decode($list_data);
			for ($j = 0; $j < count($list_data); $j++) {
				DB::table('word')->insert($list_data[$j]);
			}
		}
		

	}

}


?>