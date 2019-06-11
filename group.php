<?php 
	class Group {
		private $id;
		private $name;
		private $tableName = 'groups';
		private $dbConn;

		function setId($id) { $this->id = $id; }
		function getId() { return $this->id; }
		function setName($name) { $this->name = $name; }
		function getName() { return $this->name; }

		public function __construct() {
			$db = new DbConnect();
			$this->dbConn = $db->connect();
		}

		public function getAll() {
			$stmt = $this->dbConn->prepare("SELECT * FROM " . $this->tableName);
			$stmt->execute();
			$groups = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $groups;
		}

		public function getGroup($id) {
			$stmt = $this->dbConn->prepare("SELECT * FROM " . $this->tableName . " WHERE id = :id");
			$stmt->bindParam(":id", $id);
			$stmt->execute();
			$group = $stmt->fetch(PDO::FETCH_ASSOC);
			return $group;
		}

		public function checkExist($name) {
			$stmt = $this->dbConn->prepare("SELECT * FROM ". $this->tableName ." WHERE name = :name");
			$stmt->bindParam(":name", $name);
			$stmt->execute();
			$group = $stmt->fetch(PDO::FETCH_ASSOC);
			if(is_array($group)) {
				return true;
			} else {
				return false;
			}
			
		}

		public function update() {
			$stmt = $this->dbConn->prepare("UPDATE " . $this->tableName . " SET name = :name WHERE id = :id");
			$stmt->bindParam(":id", $this->id);
			$stmt->bindParam(':name', $this->name);
			if ($stmt->execute()) {
				return true;
			} else {
				return false;
			}
		}

		public function insert() {
			
			$sql = 'INSERT INTO ' . $this->tableName . '(id, name) VALUES (null, :name)';

			$stmt = $this->dbConn->prepare($sql);
			$stmt->bindParam(':name', $this->name);
			
			if($stmt->execute()) {
				return true;
			} else {
				return false;
			}
		}

		public function delete() {
			$stmt = $this->dbConn->prepare('DELETE FROM ' . $this->tableName . ' WHERE id = :id');
			$stmt->bindParam(':id', $this->id);
			
			if($stmt->execute()) {
				return true;
			} else {
				return false;
			}
		}
	}
 ?>