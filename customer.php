<?php 
	class Customer {
		private $id;
		private $name;
		private $surname;
		private $func;
		private $social_reason;
		private $billing_address;
		private $delivery_address;
		private $zipcode_billing;
		private $city_billing;
		private $country_billing;
		private $zipcode_delivery;
		private $city_delivery;
		private $country_delivery;
		private $email;
		private $mobile_phone;
		private $fixed_phone;
		private $status;
		private $comment;
		private $created_date;
		private $in_progress;
		private $special_condition;
		private $tableName = 'customers';
		private $dbConn;

		function setId($id) { $this->id = $id; }
		function getId() { return $this->id; }
		function setName($name) { $this->name = $name; }
		function getName() { return $this->name; }
		function setSurName($surname) { $this->surname = $surname; }
		function getSurName() { return $this->surname; }
		function setFunc($func) { $this->func = $func; }
		function getFunc() { return $this->func; }
		function setSocialReason($social_reason) { $this->social_reason = $social_reason; }
		function getSocialReason() { return $this->social_reason; }
		function setBillingAddress($billing_address) { $this->billing_address = $billing_address; }
		function getBillingAddress() { return $this->billing_address; }
		function setDeliveryAddress($delivery_address) { $this->delivery_address = $delivery_address; }
		function getDeliveryAddress() { return $this->delivery_address; }
		function setZipcodeBilling($zipcode_billing) { $this->zipcode_billing = $zipcode_billing; }
		function getZipcodeBilling() { return $this->zipcode_billing; }
		function setCityBilling($city_billing) { $this->city_billing = $city_billing; }
		function getCityBilling() { return $this->city_billing; }
		function setCountryBilling($country_billing) { $this->country_billing = $country_billing; }
		function getCountryBilling() { return $this->country_billing; }
		function setZipcodeDelivery($zipcode_delivery) { $this->zipcode_delivery = $zipcode_delivery; }
		function getZipcodeDelivery() { return $this->zipcode_delivery; }
		function setCityDelivery($city_delivery) { $this->city_delivery = $city_delivery; }
		function getCityDelivery() { return $this->city_delivery; }
		function setCountryDelivery($country_delivery) { $this->country_delivery = $country_delivery; }
		function getCountryDelivery() { return $this->country_delivery; }
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
		function setInProgress($in_progress) { $this->in_progress = $in_progress; }
		function getInProgress() { return $this->in_progress; }
		function setSpecialCondition($special_condition) { $this->special_condition = $special_condition; }
		function getSpecialCondition() { return $this->special_condition; }

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

		public function getCustomer($id) {
			$stmt = $this->dbConn->prepare("SELECT * FROM " . $this->tableName . " WHERE id = :id");
			$stmt->bindParam(":id", $id);
			$stmt->execute();
			$user = $stmt->fetch(PDO::FETCH_ASSOC);
			return $user;
		}

		public function insert() {
			
			$sql = 'INSERT INTO ' . $this->tableName . 
			'(id, type, name, surname, func, social_reason, billing_address, delivery_address, zipcode_billing, city_billing, country_billing, zipcode_delivery, city_delivery, country_delivery, email, mobile_phone, fixed_phone, status, comment, created_date, in_progress, special_condition) VALUES '.
			'(null, null, :name, :surname, :func, :social_reason, :billing_address, :delivery_address, :zipcode_billing, :city_billing, :country_billing, :zipcode_delivery, :city_delivery, :country_delivery, :email, :mobile_phone, :fixed_phone, :status, :comment, :created_date, :in_progress, :special_condition)';

			$stmt = $this->dbConn->prepare($sql);
			$stmt->bindParam(':name', $this->name);
			$stmt->bindParam(':surname', $this->surname);
			$stmt->bindParam(':func', $this->func);
			$stmt->bindParam(':social_reason', $this->social_reason);
			$stmt->bindParam(':billing_address', $this->billing_address);
			$stmt->bindParam(':delivery_address', $this->delivery_address);
			$stmt->bindParam(':zipcode_billing', $this->zipcode_billing);
			$stmt->bindParam(':city_billing', $this->city_billing);
			$stmt->bindParam(':country_billing', $this->country_billing);
			$stmt->bindParam(':zipcode_delivery', $this->zipcode_delivery);
			$stmt->bindParam(':city_delivery', $this->city_delivery);
			$stmt->bindParam(':country_delivery', $this->country_delivery);
			$stmt->bindParam(':email', $this->email);
			$stmt->bindParam(':mobile_phone', $this->mobile_phone);
			$stmt->bindParam(':fixed_phone', $this->fixed_phone);
			$stmt->bindParam(':status', $this->status);
			$stmt->bindParam(':comment', $this->comment);
			$stmt->bindParam(':created_date', $this->created_date);
			$stmt->bindParam(':in_progress', $this->in_progress);
			$stmt->bindParam(':special_condition', $this->in_progress);
			
			if($stmt->execute()) {
				return true;
			} else {
				return false;
			}
		}

		public function update() {
			
			$stmt = $this->dbConn->prepare("UPDATE " . $this->tableName 
			. " SET name = :name, surname = :surname, func = :func, social_reason = :social_reason, billing_address = :billing_address, delivery_address = :delivery_address, zipcode_billing = :zipcode_billing".
			", city_billing = :city_billing, country_billing = :country_billing, zipcode_delivery = :zipcode_delivery, city_delivery = :city_delivery, country_delivery = :country_delivery, email = :email, mobile_phone = :mobile_phone, fixed_phone = :fixed_phone, status = :status, comment = :comment, created_date = :created_date, in_progress = :in_progress, special_condition = :special_condition WHERE id = :id");
			$stmt->bindParam(":id", $this->id);
			$stmt->bindParam(':name', $this->name);
			$stmt->bindParam(':surname', $this->surname);
			$stmt->bindParam(':func', $this->func);
			$stmt->bindParam(':social_reason', $this->social_reason);
			$stmt->bindParam(':billing_address', $this->billing_address);
			$stmt->bindParam(':delivery_address', $this->delivery_address);
			$stmt->bindParam(':zipcode_billing', $this->zipcode_billing);
			$stmt->bindParam(':city_billing', $this->city_billing);
			$stmt->bindParam(':country_billing', $this->country_billing);
			$stmt->bindParam(':zipcode_delivery', $this->zipcode_delivery);
			$stmt->bindParam(':city_delivery', $this->city_delivery);
			$stmt->bindParam(':country_delivery', $this->country_delivery);
			$stmt->bindParam(':email', $this->email);
			$stmt->bindParam(':mobile_phone', $this->mobile_phone);
			$stmt->bindParam(':fixed_phone', $this->fixed_phone);
			$stmt->bindParam(':status', $this->status);
			$stmt->bindParam(':comment', $this->comment);
			$stmt->bindParam(':created_date', $this->created_date);
			$stmt->bindParam(':in_progress', $this->in_progress);
			$stmt->bindParam(':special_condition', $this->in_progress);
			if ($stmt->execute()) {
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