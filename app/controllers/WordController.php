<?php 

class WordController extends BaseController {

	public function addDataBase () {
		for ($i = 1; $i < 9; $i++) {
			$list_data = file_get_contents(public_path() . '/AllData/English/10100000' . $i . '/json/words.json');
			$list_data = json_decode($list_data);
			for ($j = 0; $j < count($list_data); $j++) {
				$w = $list_data[$i];
				$word = array(
					'id_word' => $w->id_word,
					'id_subject' => $w->id_subject,
					'id_course' => $w->id_course,
					'word' => $w->word,
					'mean' => $w->mean,
					'example' => $w->example,
					'example_mean' => $w->example_mean,
					'num_ef' => $w->num_ef,
					'time_date' => $w->time_date,
					'next_time' => $w->next_time,
					'num_n' => $w->num_n,
					'num_i' => $w->num_i,
					'max_q' => $w->max_q,
					'phonectic' => $w->phonectic,
					'des' => $w->des
				);
				DB::table('word')->insert($word);
			}
		}
		

	}

}


?>