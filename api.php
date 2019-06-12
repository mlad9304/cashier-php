<?php 

	class Api extends Rest {
		
		public function __construct() {
			parent::__construct();
		}

		public function login() {
			$email = $this->validateParameter('email', $this->param['email'], STRING);
			$password = $this->validateParameter('password', $this->param['password'], STRING);
			try {
				$stmt = $this->dbConn->prepare("SELECT * FROM users WHERE email = :email");
				$stmt->bindParam(":email", $email);
				$stmt->execute();
				$user = $stmt->fetch(PDO::FETCH_ASSOC);
				if(!is_array($user)) {
					$this->throwError(INVALID_USER_PASS, "Email or Password is incorrect.");
				}
				if(!password_verify($password, $user['password'])) {
					$this->throwError(INVALID_USER_PASS, "Email or Password is incorrect.");
				}

				$paylod = [
					'iat' => time(),
					'iss' => 'localhost',
					'exp' => time() + (7*24*60*60),
					'userId' => $user['id']
				];

				$token = JWT::encode($paylod, SECRETE_KEY);
				
				$data = [
					'token' => $token,
					'name' => $user['name'],
					'surname' => $user['surname'],
					'email' => $user['email'],
					'group' => $user['group']
				];
				$this->returnResponse(SUCCESS_RESPONSE, $data);
			} catch (Exception $e) {
				$this->throwError(JWT_PROCESSING_ERROR, $e->getMessage());
			}
		}

		public function register() {
			$name = $this->validateParameter('name', $this->param['name'], STRING, false);
			$surname = $this->validateParameter('surname', $this->param['surname'], STRING, false);
			$address = $this->validateParameter('address', $this->param['address'], STRING, false);
			$zipcode = $this->validateParameter('zipcode', $this->param['zipcode'], STRING, false);
			$city = $this->validateParameter('city', $this->param['city'], STRING, false);
			$phone = $this->validateParameter('phone', $this->param['phone'], STRING, false);
			$email = $this->validateParameter('email', $this->param['email'], STRING, false);
			$password = $this->validateParameter('password', $this->param['password'], STRING, false);
			$group = $this->param['group'] ? $this->validateParameter('group', $this->param['group'], INTEGER, false) : null;

			$user = new User;
			$user->setName($name);
			$user->setSurname($surname);
			$user->setAddress($address);
			$user->setZipcode($zipcode);
			$user->setCity($city);
			$user->setPhone($phone);
			$user->setEmail($email);
			$user->setPassword($password);
			$user->setGroup($group);

			if($user->checkExist($email)) {
				$this->throwError(USER_EXIST, "User aleady exist.");
			}

			if(!$user->insert()) {
				$message = 'Failed to insert.';
			} else {
				$message = "Registered successfully.";
			}

			$this->returnResponse(SUCCESS_RESPONSE, $message);
		}

		public function getAllUsers() {
			$user = new User;
			$group = new Group;
			$data = [
				'users' => $user->getAllUsers(),
				'groups' => $group->getAll()
			];
			$this->returnResponse(SUCCESS_RESPONSE, $data);
		}

		public function getUser() {
			$id = $this->validateParameter('id', $this->param['id'], INTEGER, false);
			$user = new User;
			$data = [
				'user' => $user->getUser($id)
			];
			$this->returnResponse(SUCCESS_RESPONSE, $data);
		}

		public function updateUser() {
			$id = $this->validateParameter('id', $this->param['id'], INTEGER, false);
			$name = $this->validateParameter('name', $this->param['name'], STRING, false);
			$surname = $this->validateParameter('surname', $this->param['surname'], STRING, false);
			$address = $this->validateParameter('address', $this->param['address'], STRING, false);
			$zipcode = $this->validateParameter('zipcode', $this->param['zipcode'], STRING, false);
			$city = $this->validateParameter('city', $this->param['city'], STRING, false);
			$phone = $this->validateParameter('phone', $this->param['phone'], STRING, false);
			$email = $this->validateParameter('email', $this->param['email'], STRING, false);
			$group = $this->param['group'] ? $this->validateParameter('group', $this->param['group'], INTEGER, false) : null;

			$user = new User;
			$user->setId($id);
			$user->setName($name);
			$user->setSurname($surname);
			$user->setAddress($address);
			$user->setZipcode($zipcode);
			$user->setCity($city);
			$user->setPhone($phone);
			$user->setEmail($email);
			$user->setGroup($group);

			if(!$user->update()) {
				$message = 'Failed to update.';
			} else {
				$message = "Updated successfully.";
			}

			$this->returnResponse(SUCCESS_RESPONSE, $message);
		}

		public function deleteUser() {
			$id = $this->validateParameter('id', $this->param['id'], INTEGER, false);
			$user = new User;
			$user->setId($id);
			if(!$user->delete()) {
				$message = 'Failed to delete.';
			} else {
				$message = "Deleted successfully.";
			}

			$this->returnResponse(SUCCESS_RESPONSE, $message);
		}

		public function getAllCustomers() {
			$customer = new Customer;
			$data = [
				'customers' => $customer->getAll()
			];
			$this->returnResponse(SUCCESS_RESPONSE, $data);
		}

		public function addCustomer() {
			$name = $this->validateParameter('name', $this->param['name'], STRING, false);
			$surname = $this->validateParameter('surname', $this->param['surname'], STRING, false);
			$func = $this->validateParameter('func', $this->param['func'], STRING, false);
			$social_reason = $this->validateParameter('social_reason', $this->param['social_reason'], STRING, false);
			$billing_address = $this->validateParameter('billing_address', $this->param['billing_address'], STRING, false);
			$delivery_address = $this->validateParameter('delivery_address', $this->param['delivery_address'], STRING, false);
			$zipcode_billing = $this->validateParameter('zipcode_billing', $this->param['zipcode_billing'], STRING, false);
			$city_billing = $this->validateParameter('city_billing', $this->param['city_billing'], STRING, false);
			$country_billing = $this->validateParameter('country_billing', $this->param['country_billing'], STRING, false);
			$zipcode_delivery = $this->validateParameter('zipcode_delivery', $this->param['zipcode_delivery'], STRING, false);
			$city_delivery = $this->validateParameter('city_delivery', $this->param['city_delivery'], STRING, false);
			$country_delivery = $this->validateParameter('country_delivery', $this->param['country_delivery'], STRING, false);
			$email = $this->validateParameter('email', $this->param['email'], STRING, false);
			$mobile_phone = $this->validateParameter('mobile_phone', $this->param['mobile_phone'], STRING, false);
			$fixed_phone = $this->validateParameter('fixed_phone', $this->param['fixed_phone'], STRING, false);
			$status = $this->validateParameter('status', $this->param['status'], STRING, false);
			$comment = $this->validateParameter('comment', $this->param['comment'], STRING, false);
			$created_date = $this->validateParameter('created_date', $this->param['created_date'], STRING, false);
			$in_progress = 0;
			$special_condition = $this->param['special_condition'];

			$cust = new Customer;
			$cust->setName($name);
			$cust->setSurName($surname);
			$cust->setFunc($func);
			$cust->setSocialReason($social_reason);
			$cust->setBillingAddress($billing_address);
			$cust->setDeliveryAddress($delivery_address);
			$cust->setZipcodeBilling($zipcode_billing);
			$cust->setCityBilling($city_billing);
			$cust->setCountryBilling($country_billing);
			$cust->setZipcodeDelivery($zipcode_delivery);
			$cust->setCityDelivery($city_delivery);
			$cust->setCountryDelivery($country_delivery);
			$cust->setEmail($email);
			$cust->setMobilePhone($mobile_phone);
			$cust->setFixedPhone($fixed_phone);
			$cust->setStatus($status);
			$cust->setComment($comment);
			$cust->setCreatedDate(date('Y-m-d'));
			$cust->setInProgress($in_progress);
			$cust->setSpecialCondition($special_condition);

			if(!$cust->insert()) {
				$message = 'Failed to insert.';
			} else {
				$message = "Inserted successfully.";
			}

			$this->returnResponse(SUCCESS_RESPONSE, $message);
		}

		public function getCustomer() {
			$id = $this->validateParameter('id', $this->param['id'], INTEGER, false);
			$user = new Customer;
			$data = [
				'customer' => $user->getCustomer($id)
			];
			$this->returnResponse(SUCCESS_RESPONSE, $data);
		}

		public function updateCustomer() {
			$id = $this->validateParameter('id', $this->param['id'], INTEGER);
			$name = $this->validateParameter('name', $this->param['name'], STRING, false);
			$surname = $this->validateParameter('surname', $this->param['surname'], STRING, false);
			$func = $this->validateParameter('func', $this->param['func'], STRING, false);
			$social_reason = $this->validateParameter('social_reason', $this->param['social_reason'], STRING, false);
			$billing_address = $this->validateParameter('billing_address', $this->param['billing_address'], STRING, false);
			$delivery_address = $this->validateParameter('delivery_address', $this->param['delivery_address'], STRING, false);
			$zipcode_billing = $this->validateParameter('zipcode_billing', $this->param['zipcode_billing'], STRING, false);
			$city_billing = $this->validateParameter('city_billing', $this->param['city_billing'], STRING, false);
			$country_billing = $this->validateParameter('country_billing', $this->param['country_billing'], STRING, false);
			$zipcode_delivery = $this->validateParameter('zipcode_delivery', $this->param['zipcode_delivery'], STRING, false);
			$city_delivery = $this->validateParameter('city_delivery', $this->param['city_delivery'], STRING, false);
			$country_delivery = $this->validateParameter('country_delivery', $this->param['country_delivery'], STRING, false);
			$email = $this->validateParameter('email', $this->param['email'], STRING, false);
			$mobile_phone = $this->validateParameter('mobile_phone', $this->param['mobile_phone'], STRING, false);
			$fixed_phone = $this->validateParameter('fixed_phone', $this->param['fixed_phone'], STRING, false);
			$status = $this->validateParameter('status', $this->param['status'], STRING, false);
			$comment = $this->validateParameter('comment', $this->param['comment'], STRING, false);
			$created_date = $this->validateParameter('created_date', $this->param['created_date'], STRING, false);
			$in_progress = 0;
			$special_condition = $this->param['special_condition'];

			$cust = new Customer;
			$cust->setId($id);
			$cust->setName($name);
			$cust->setSurName($surname);
			$cust->setFunc($func);
			$cust->setSocialReason($social_reason);
			$cust->setBillingAddress($billing_address);
			$cust->setDeliveryAddress($delivery_address);
			$cust->setZipcodeBilling($zipcode_billing);
			$cust->setCityBilling($city_billing);
			$cust->setCountryBilling($country_billing);
			$cust->setZipcodeDelivery($zipcode_delivery);
			$cust->setCityDelivery($city_delivery);
			$cust->setCountryDelivery($country_delivery);
			$cust->setEmail($email);
			$cust->setMobilePhone($mobile_phone);
			$cust->setFixedPhone($fixed_phone);
			$cust->setStatus($status);
			$cust->setComment($comment);
			$cust->setCreatedDate(date('Y-m-d'));
			$cust->setInProgress($in_progress);
			$cust->setSpecialCondition($special_condition);

			if(!$cust->update()) {
				$message = 'Failed to update.';
			} else {
				$message = "Updated successfully.";
			}

			$this->returnResponse(SUCCESS_RESPONSE, $message);
		}

		public function deleteCustomer() {
			$id = $this->validateParameter('id', $this->param['id'], INTEGER);

			$cust = new Customer;
			$cust->setId($id);

			if(!$cust->delete()) {
				$message = 'Failed to delete.';
			} else {
				$message = "deleted successfully.";
			}

			$this->returnResponse(SUCCESS_RESPONSE, $message);
		}

		public function getAllGroups() {
			$group = new Group;
			$data = [
				'groups' => $group->getAll()
			];
			$this->returnResponse(SUCCESS_RESPONSE, $data);
		}

		public function addGroup() {
			$name = $this->validateParameter('name', $this->param['name'], STRING, false);

			$group = new Group;
			$group->setName($name);

			if($group->checkExist($name)) {
				$this->throwError(GROUP_EXIST, "Group aleady exist.");
			}

			if(!$group->insert()) {
				$message = 'Failed to insert.';
			} else {
				$message = "Inserted successfully.";
			}

			$this->returnResponse(SUCCESS_RESPONSE, $message);
		}

		public function getGroup() {
			$id = $this->validateParameter('id', $this->param['id'], INTEGER, false);
			$group = new Group;
			$data = [
				'group' => $group->getGroup($id)
			];
			$this->returnResponse(SUCCESS_RESPONSE, $data);
		}

		public function updateGroup() {
			$id = $this->validateParameter('id', $this->param['id'], INTEGER);
			$name = $this->validateParameter('name', $this->param['name'], STRING, false);

			$group = new Group;
			$group->setId($id);
			$group->setName($name);

			if(!$group->update()) {
				$message = 'Failed to update.';
			} else {
				$message = "Updated successfully.";
			}

			$this->returnResponse(SUCCESS_RESPONSE, $message);
		}

		public function deleteGroup() {
			$id = $this->validateParameter('id', $this->param['id'], INTEGER);

			$group = new Group;
			$group->setId($id);

			if(!$group->delete()) {
				$message = 'Failed to delete.';
			} else {
				$message = "deleted successfully.";
			}

			$this->returnResponse(SUCCESS_RESPONSE, $message);
		}
	}
	
 ?>