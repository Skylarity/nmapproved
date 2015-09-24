<?php

require_once(dirname(__DIR__) . "/helpers/filter.php");

/**
 * This class entails a business with ID, name, location, phone number, website, email, and category.
 *
 * @author Skyler Rexroad
 */
class Business implements JsonSerializable {

	/**
	 * Business ID, primary key
	 * @var int $businessId
	 */
	private $businessId;

	/**
	 * Business name
	 * @var string $name
	 */
	private $name;

	/**
	 * Business location
	 * @var string $location
	 */
	private $location;

	/**
	 * Business phone number
	 * @var string $phone
	 */
	private $phone;

	/**
	 * Business website
	 * @var string $website
	 */
	private $website;

	/**
	 * Business email
	 * @var string $email
	 */
	private $email;

	/**
	 * Business category
	 * @var int $categoryId
	 */
	private $categoryId;

	/**
	 * @param int $businessId primary key of the business
	 * @param string $name name of the business
	 * @param string $location location of the business
	 * @param string $phone phone number of the business
	 * @param string $website website of the business
	 * @param string $email email of the business
	 * @param string $categoryId category of the business
	 */
	public function __construct($businessId, $name, $location, $phone, $website, $email, $categoryId) {
		try {
			$this->setBusinessId($businessId);
			$this->setName($name);
			$this->setLocation($location);
			$this->setPhone($phone);
			$this->setWebsite($website);
			$this->setEmail($email);
			$this->setCategoryId($categoryId);
		} catch(InvalidArgumentException $invalidArgument) {
			// Rethrow to caller
			throw new InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument);
		} catch(RangeException $rangeException) {
			// Rethrow to caller
			throw new InvalidArgumentException($rangeException->getMessage(), 0, $rangeException);
		} catch(PDOException $pdoException) {
			// Rethrow to caller
			throw new InvalidArgumentException($pdoException->getMessage(), 0, $pdoException);
		} catch(Exception $exception) {
			// Rethrow to caller
			throw new InvalidArgumentException($exception->getMessage(), 0, $exception);
		}
	}

	/**
	 * @return int
	 */
	public function getBusinessId() {
		return $this->businessId;
	}

	/**
	 * @param int $businessId
	 */
	public function setBusinessId($businessId) {
		$this->businessId = Filter::filterInt($businessId, true);
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName($name) {
		$this->name = Filter::filterString($name, "Business name", 128);
	}

	/**
	 * @return string
	 */
	public function getLocation() {
		return $this->location;
	}

	/**
	 * @param string $location
	 */
	public function setLocation($location) {
		if(strlen($location) > 0) {
			$this->location = Filter::filterString($location, "Business location", 128);
		} else {
			$this->location = "";
		}
	}

	/**
	 * @return string
	 */
	public function getPhone() {
		return $this->phone;
	}

	/**
	 * @param string $phone
	 */
	public function setPhone($phone) {
		if(strlen($phone) > 0) {
			$this->phone = Filter::filterString($phone, "Business phone number", 64);
		} else {
			$this->phone = "";
		}
	}

	/**
	 * @return string
	 */
	public function getWebsite() {
		return $this->website;
	}

	/**
	 * @param string $website
	 */
	public function setWebsite($website) {
		if(strlen($website) > 0) {
			$this->website = Filter::filterString($website, "Business website", 64);
		} else {
			$this->website = "";
		}
	}

	/**
	 * @return string
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * @param string $email
	 */
	public function setEmail($email) {
		if(strlen($email) > 0) {
			$this->email = Filter::filterString($email, "Business email", 64);
		} else {
			$this->email = "";
		}
	}

	/**
	 * @return string
	 */
	public function getCategoryId() {
		return $this->categoryId;
	}

	/**
	 * @param string $categoryId
	 */
	public function setCategoryId($categoryId) {
		$this->categoryId = Filter::filterInt($categoryId, "Business category ID");
	}

	/**
	 * Implements JSON serializing for this class
	 * @return array
	 */
	public function JsonSerialize() {
		$fields = get_object_vars($this);
		return ($fields);
	}

	/**
	 * Inserts business into MySQL
	 * @param PDO $pdo
	 */
	public function insert(PDO &$pdo) {
		// Verify business does not already exist
		if($this->businessId !== null) {
			throw new PDOException("Business already exists!");
		}

		// Create query template
		$query = "INSERT INTO business(name, location, phone, website, email, categoryId) VALUES (:name, :location, :phone, :website, :email, :category)";
		$statement = $pdo->prepare($query);

		// Bind the variables to the placeholders
		$parameters = array("name" => $this->getName(), "location" => $this->getLocation(), "phone" => $this->getPhone(), "website" => $this->getWebsite(), "email" => $this->getEmail(), "category" => $this->getCategoryId());
		$statement->execute($parameters);

		// Update null ID with real ID
		$this->businessId = intval($pdo->lastInsertId());
	}

	/**
	 * Deletes business from MySQL
	 * @param PDO $pdo
	 */
	public function delete(PDO &$pdo) {
		// Verify business is NOT NULL
		if($this->businessId === null) {
			throw new PDOException("Unable to delete a nonexistent business");
		}

		// Create query template
		$query = "DELETE FROM business WHERE businessId = :businessId";
		$statement = $pdo->prepare($query);

		// Bind the variables to the placeholders
		$parameters = array("businessId" => $this->getBusinessId());
		$statement->execute($parameters);
	}

	/**
	 * Updates business in MySQL
	 * @param PDO $pdo
	 */
	public function update(PDO &$pdo) {
		// Create query template
		$query = "UPDATE business SET name = :name, location = :location, phone = :phone, website = :website, email = :email, categoryId = :categoryId WHERE businessId = :businessId";
		$statement = $pdo->prepare($query);

		// Bind the variables to the placeholders
		$parameters = array("name" => $this->getName(), "location" => $this->getLocation(), "phone" => $this->getPhone(), "website" => $this->getWebsite(), "email" => $this->getEmail(), "categoryId" => $this->getCategoryId(), "businessId" => $this->getBusinessId());
		$statement->execute($parameters);
	}

	/**
	 * @param PDO $pdo
	 * @param int $businessId business ID to check for
	 * @return Business|null returns a business if found, or null
	 * @throws Exception catch-all error handling
	 */
	public static function getBusinessById(PDO &$pdo, $businessId) {
		// Filter ID
		$businessId = Filter::filterInt($businessId, "Business ID");

		// Create query template
		$query = "SELECT businessId, name, location, phone, website, email, categoryId FROM business WHERE businessId = :businessId";
		$statement = $pdo->prepare($query);

		// Bind the variables to the placeholders
		$parameters = array("businessId" => $businessId);
		$statement->execute($parameters);

		// Grab the business from MySQL
		try {
			$business = null;
			$statement->setFetchMode(PDO::FETCH_ASSOC);
			$row = $statement->fetch();

			if($row !== false) {
				$business = new Business($row["businessId"], $row["name"], $row["location"], $row["phone"], $row["website"], $row["email"], $row["category"]);
			}
		} catch(Exception $exception) {
			// Rethrow to caller
			throw new Exception($exception->getMessage(), 0, $exception);
		}

		return $business;
	}

	/**
	 * Gets a business from an attribute and a string
	 * @param PDO $pdo
	 * @param string $attribute business attribute to grab
	 * @param string $string data to check against
	 * @return SplFixedArray array of business
	 * @throws Exception catch-all error handling
	 */
	public static function getBusinessesByString(PDO &$pdo, $attribute, $string) {
		// Filter the inputs
		$attribute = Filter::filterString($attribute, "Input string \"attribute\" in getBusinessByString()");
		$string = Filter::filterString($string, "Input string \"string\" in getBusinessByString()");

		// Create query template
		$query = "SELECT businessId, name, location, phone, website, email, categoryId FROM business WHERE $attribute = :string";
		$statement = $pdo->prepare($query);

		// Bind the variables to the placeholders
		$parameters = array("string" => $string);
		$statement->execute($parameters);

		// Grab the businesses from MySQL
		$businesses = new SplFixedArray($statement->rowCount());
		$statement->setFetchMode(PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$business = new Business($row["businessId"], $row["name"], $row["location"], $row["phone"], $row["website"], $row["email"], $row["category"]);
				$businesses[$businesses->key()] = $business;
				$businesses->next();
			} catch(Exception $exception) {
				// Rethrow to caller
				throw new Exception($exception->getMessage(), 0, $exception);
			}
		}

		return $businesses;
	}

}