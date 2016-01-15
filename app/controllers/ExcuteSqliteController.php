<?php 

// Xử lí database sqlite

class ExcuteSqliteController implements ExcuteDatabaseController {

	protected $db;

	public function openDatabase () {
		$filename = public_path() . '/jaen.db';
		$this->db = new SQLite3($filename);
	}

	public function queryAllofTable ($tableName) {
		$results = $this->db->query('select * from ' . $tableName);
		$result = [];

		while ($row = $results->fetchArray()) {
			array_push($result, $row);
		}

		return $result;
	}

}

?>