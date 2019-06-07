<?php 
	class Customer {
		private $id;
		private $name;
		private $surname;
		private $function;
		private $social_reason;
		private $billing_address;
		private $delivery_address;
		private $zipcode;
		private $city;
		private $country;
		private $email;
		private $mobile_phone;
		private $fixed_phone;
		private $status;
		private $comment;
		private $created_date;
		private $tableName = 'customers';
		private $dbConn;

		function setId($id) { $this->id = $id; }
		function getId() { return $this->id; }
		function setName($name) { $this->name = $name; }
		function getName() { return $this->name; }
		function setSurName($surname) { $this->surname = $surname; }
		function getSurName() { return $this->surname; }
		function setFunction($function) { $this->function = $function; }
		function getFunction() { return $this->function; }
		function setSocialReason($social_reason) { $this->social_reason = $social_reason; }
		function getSocialReason() { return $this->social_reason; }
		function setBillingAddress($billing_address) { $this->billing_address = $billing_address; }
		function getBillingAddress() { return $this->billing_address; }
		function setDeliveryAddress($delivery_address) { $this->delivery_address = $delivery_address; }
		function getDeliveryAddress() { return $this->delivery_address; }
		function setZipcode($zipcode) { $this->zipcode = $zipcode; }
		function getZipcode() { return $this->zipcode; }
		function setCity($city) { $this->city = $city; }
		function getCity() { return $this->city; }
		function setCountry($country) { $this->country = $country; }
		function getCountry() { return $this->country; }
		function setEmail($email) { $this->email = $email; }
		function getEmail() { return $this->email; }
		function setMobilePhone($mobile_phone) { $this->mobile_phone = $mobile_phone; }
		function getMobilePhone() { return $this->mobile_phone; }
		function setFixedPhone($fixed_phone) { $this->fixed_phone = $fixed_phone; }
		function getFixedPhone() { return $this->fixed_phone; }
		function setStatus($status) { $this->status = $status; }
		function getStatus() { return $this->status; }
		function setComment($comment) { $this->comment = $comment; }
		function getComment() { return $this->comment; }
		function setCreatedDate($created_date) { $this->created_date = $created_date; }
		function getCreatedDate() { return $this->created_date; }

		public function __construct() {
			$db = new DbConnect();
			$this->dbConn = $db->connect();
		}

		public function getAll() {
			$stmt = $this->dbConn->prepare("SELECT * FROM " . $this->tableName);
			$stmt->execute();
			$customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $customers;
		}

		public function getCustomerDetailsById() {

			$sql = "SELECT 
						c.*, 
						u.name as created_user,
						u1.name as updated_user
					FROM customers c 
						JOIN users u ON (c.created_by = u.id) 
						LEFT JOIN users u1 ON (c.updated_by = u1.id) 
					WHERE 
						c.id = :customerId";

			$stmt = $this->dbConn->prepare($sql);
			$stmt->bindParam(':customerId', $this->id);
			$stmt->execute();
			$customer = $stmt->fetch(PDO::FETCH_ASSOC);
			return $customer;
		}
		

		public function insert() {
			
			$sql = 'INSERT INTO ' . $this->tableName . 
			'(id, type, name, surname, function, social_reason, billing_address, delivery_address, zipcode, city, country, email, mobile_phone, fixed_phone, status, comment, create_date) VALUES '.
			'(null, null, :name, :surname, :function, :social_reason, :billing_address, :delivery_address, :zipcode, :city, :country, :email, :mobile_phone, :fixed_phone, :status, :comment, :create_date)';

			$stmt = $this->dbConn->prepare($sql);
			$stmt->bindParam(':name', $this->name);
			$stmt->bindParam(':surname', $this->surname);
			$stmt->bindParam(':function', $this->function);
			$stmt->bindParam(':social_reason', $this->social_reason);
			$stmt->bindParam(':billing_address', $this->billing_address);
			$stmt->bindParam(':delivery_address', $this->delivery_address);
			$stmt->bindParam(':zipcode', $this->zipcode);
			$stmt->bindParam(':city', $this->city);
			$stmt->bindParam(':country', $this->country);
			$stmt->bindParam(':email', $this->email);
			$stmt->bindParam(':mobile_phone', $this->mobile_phone);
			$stmt->bindParam(':fixed_phone', $this->fixed_phone);
			$stmt->bindParam(':status', $this->status);
			$stmt->bindParam(':comment', $this->comment);
			$stmt->bindParam(':create_date', $this->create_date);
			
			if($stmt->execute()) {
				return true;
			} else {
				return false;
			}
		}

		public function update() {
			
			$sql = "UPDATE $this->tableName SET";
			if( null != $this->getName()) {
				$sql .=	" name = '" . $this->getName() . "',";
			}

			if( null != $this->getAddress()) {
				$sql .=	" address = '" . $this->getAddress() . "',";
			}

			if( null != $this->getMobile()) {
				$sql .=	" mobile = " . $this->getMobile() . ",";
			}

			$sql .=	" updated_by = :updatedBy, 
					  updated_on = :updatedOn
					WHERE 
						id = :userId";

			$stmt = $this->dbConn->prepare($sql);
			$stmt->bindParam(':userId', $this->id);
			$stmt->bindParam(':updatedBy', $this->updatedBy);
			$stmt->bindParam(':updatedOn', $this->updatedOn);
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