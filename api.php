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
					'email' => $user['email']
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

			$user = new User;
			$user->setName($name);
			$user->setSurname($surname);
			$user->setAddress($address);
			$user->setZipcode($zipcode);
			$user->setCity($city);
			$user->setPhone($phone);
			$user->setEmail($email);
			$user->setPassword($password);

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
			$data = [
				'users' => $user->getAllUsers()
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

			$user = new User;
			$user->setId($id);
			$user->setName($name);
			$user->setSurname($surname);
			$user->setAddress($address);
			$user->setZipcode($zipcode);
			$user->setCity($city);
			$user->setPhone($phone);
			$user->setEmail($email);

			// if(!$user->update()) {
			// 	$message = 'Failed to update.';
			// } else {
			// 	$message = "Updated successfully.";
			// }

			$this->returnResponse(SUCCESS_RESPONSE, $user->update());
		}

		public function addCustomer() {
			$name = $this->validateParameter('name', $this->param['name'], STRING, false);
			$email = $this->validateParameter('email', $this->param['email'], STRING, false);
			$addr = $this->validateParameter('addr', $this->param['addr'], STRING, false);
			$mobile = $this->validateParameter('mobile', $this->param['mobile'], INTEGER, false);

			$cust = new Customer;
			$cust->setName($name);
			$cust->setEmail($email);
			$cust->setAddress($addr);
			$cust->setMobile($mobile);
			$cust->setCreatedBy($this->userId);
			$cust->setCreatedOn(date('Y-m-d'));

			if(!$cust->insert()) {
				$message = 'Failed to insert.';
			} else {
				$message = "Inserted successfully.";
			}

			$this->returnResponse(SUCCESS_RESPONSE, $message);
		}

		public function getCustomerDetails() {
			$customerId = $this->validateParameter('customerId', $this->param['customerId'], INTEGER);

			$cust = new Customer;
			$cust->setId($customerId);
			$customer = $cust->getCustomerDetailsById();
			if(!is_array($customer)) {
				$this->returnResponse(SUCCESS_RESPONSE, ['message' => 'Customer details not found.']);
			}

			$response['customerId'] 	= $customer['id'];
			$response['cutomerName'] 	= $customer['name'];
			$response['email'] 			= $customer['email'];
			$response['mobile'] 		= $customer['mobile'];
			$response['address'] 		= $customer['address'];
			$response['createdBy'] 		= $customer['created_user'];
			$response['lastUpdatedBy'] 	= $customer['updated_user'];
			$this->returnResponse(SUCCESS_RESPONSE, $response);
		}

		public function updateCustomer() {
			$customerId = $this->validateParameter('customerId', $this->param['customerId'], INTEGER);
			$name = $this->validateParameter('name', $this->param['name'], STRING, false);
			$addr = $this->validateParameter('addr', $this->param['addr'], STRING, false);
			$mobile = $this->validateParameter('mobile', $this->param['mobile'], INTEGER, false);

			$cust = new Customer;
			$cust->setId($customerId);
			$cust->setName($name);
			$cust->setAddress($addr);
			$cust->setMobile($mobile);
			$cust->setUpdatedBy($this->userId);
			$cust->setUpdatedOn(date('Y-m-d'));

			if(!$cust->update()) {
				$message = 'Failed to update.';
			} else {
				$message = "Updated successfully.";
			}

			$this->returnResponse(SUCCESS_RESPONSE, $message);
		}

		public function deleteCustomer() {
			$customerId = $this->validateParameter('customerId', $this->param['customerId'], INTEGER);

			$cust = new Customer;
			$cust->setId($customerId);

			if(!$cust->delete()) {
				$message = 'Failed to delete.';
			} else {
				$message = "deleted successfully.";
			}

			$this->returnResponse(SUCCESS_RESPONSE, $message);
		}
	}
	
 ?>