<?php 
	class User {
		private $id;
		private $name;
		private $surname;
		private $address;
		private $zipcode;
		private $city;
		private $phone;
		private $email;
		private $password;
		private $tableName = 'users';
		private $dbConn;

		function setId($id) { $this->id = $id; }
		function getId() { return $this->id; }
		function setName($name) { $this->name = $name; }
		function getName() { return $this->name; }
		function setSurName($surname) { $this->surname = $surname; }
		function getSurName() { return $this->surname; }
		function setAddress($address) { $this->address = $address; }
		function getAddress() { return $this->address; }
		function setZipcode($zipcode) { $this->zipcode = $zipcode; }
		function getZipcode() { return $this->zipcode; }
		function setCity($city) { $this->city = $city; }
		function getCity() { return $this->city; }
		function setPhone($phone) { $this->phone = $phone; }
		function getPhone() { return $this->phone; }
		function setEmail($email) { $this->email = $email; }
		function getEmail() { return $this->email; }
		function setPassword($password) { $this->password = $password; }
		function getPassword() { return $this->password; }

		public function __construct() {
			$db = new DbConnect();
			$this->dbConn = $db->connect();
		}

		public function getAllUsers() {
			$stmt = $this->dbConn->prepare("SELECT * FROM " . $this->tableName);
			$stmt->execute();
			$customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $customers;
		}

		public function checkExist($email) {
			$stmt = $this->dbConn->prepare("SELECT * FROM users WHERE email = :email");
			$stmt->bindParam(":email", $email);
			$stmt->execute();
			$user = $stmt->fetch(PDO::FETCH_ASSOC);
			if(is_array($user)) {
				return true;
			} else {
				return false;
			}
			
		}

		public function insert() {
			
			$sql = 'INSERT INTO ' . $this->tableName . 
				'(id, name, surname, address, zipcode, city, phone, email, password) VALUES(null, :name, :surname, :address, :zipcode, :city, :phone, :email, :password)';

			$stmt = $this->dbConn->prepare($sql);
			$stmt->bindParam(':name', $this->name);
			$stmt->bindParam(':surname', $this->surname);
			$stmt->bindParam(':address', $this->address);
			$stmt->bindParam(':zipcode', $this->zipcode);
			$stmt->bindParam(':city', $this->city);
			$stmt->bindParam(':phone', $this->phone);
			$stmt->bindParam(':email', $this->email);
			$stmt->bindParam(':password', password_hash($this->password, PASSWORD_DEFAULT));
			
			if($stmt->execute()) {
				return true;
			} else {
				return false;
			}
		}

		public function delete() {
			$stmt = $this->dbConn->prepare('DELETE FROM ' . $this->tableName . ' WHERE id = :userId');
			$stmt->bindParam(':userId', $this->id);
			
			if($stmt->execute()) {
				return true;
			} else {
				return false;
			}
		}
	}
 ?>