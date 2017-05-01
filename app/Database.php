<?php

namespace App;

use PDO;

class Database {
	/**
	 * @var PDO
	 */
	private $pdo;

	public function __construct(PDO $pdo) {
		$this->pdo = $pdo;
	}

	/**
	 * @param integer $id
	 * @return Model
	 */
	public function getById($table, $id) {
		$stm = $this->pdo->prepare('SELECT * FROM '.$table.' WHERE id = :id');
		$stm->bindParam(':id', $id);
		$success = $stm->execute();
		$row = $stm->fetch(PDO::FETCH_ASSOC);
		return ($success) ? $row : [];
	}

	public function getAll($table) {
		$stm = $this->pdo->prepare('SELECT * FROM '.$table);
		$success = $stm->execute();
		$rows = $stm->fetchAll(PDO::FETCH_ASSOC);
		return ($success) ? $rows : [];
	}

	public function create($table, $data) {
		$columns = array_keys($data);

		$columnSql = implode(',', $columns);
		'name,quantity,recipe_difficulty';

		$bindingSql = ':'.implode(',:', $columns);
		':name,:quantity,:recipe_difficulty';

		$sql = "INSERT INTO $table ($columnSql) VALUES ($bindingSql)";
		$stm = $this->pdo->prepare($sql);

		foreach ($data as $key => $value) {
			$stm->bindValue(':'.$key, $value);
		}
		$status = $stm->execute();

		return ($status) ? $this->pdo->lastInsertId() : false;
	}

	public function update() {

	}
}