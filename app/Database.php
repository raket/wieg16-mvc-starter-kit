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

	/**
	 * ÖVERKURS
	 *
	 * Skriv den här själv!
	 * Titta på create för strukturidéer
	 * Du kan binda parametrar precis som i create
	 * Klura ut hur du skall sätt ihop rätt textsträng för x=y...
	 * Implode kommer inte ta dig hela vägen den här gången
	 * Kanske array_map eller foreach?
	 */
	public function update($table, $id, $data) {
		$columns = array_keys($data);

		$sql = "UPDATE $table SET (x=y...) WHERE id = :id";
	}

	/**
	 * Skriv den här själv!
	 * Titta på getById för struktur
	 */
	public function delete($table, $id) {

	}
}