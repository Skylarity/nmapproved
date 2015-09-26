<?php

class Subcategory implements JsonSerializable {


	private $subcategoryId;

	private $categoryId;

	private $name;

	/**
	 * @param $subcategoryId
	 * @param $name
	 **/
	public function __construct($subcategoryId, $categoryId, $name) {
		try {
			$this->setSubCategoryId($subcategoryId);
			$this->setCategoryId($categoryId);
			$this->setName($name);
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
	 * Accessor for subcategoryId
	 * @return int value of subcategoryId
	 **/
	public function getSubCategoryId() {
		return ($this->subcategoryId);
	}

	/**
	 * Mutator for subcategoryId
	 * @param $newSubCategoryId
	 **/
	public function setSubCategoryId($newSubCategoryId) {
		if($newSubCategoryId === null) {
			$this->subcategoryId = null;
			return;
		}
		$newSubCategoryId = filter_var($newSubCategoryId, FILTER_VALIDATE_INT);
		if(empty($newSubCategoryId) === true) {
			throw (new InvalidArgumentException ("subcategoryId invalid"));
		}
		$this->subcategoryId = $newSubCategoryId;
	}

	/**
	 * Accessor for categoryId
	 * @return int value of categoryId
	 **/
	public function getCategoryId() {
		return ($this->categoryId);
	}

	/**
	 * Mutator for categoryId
	 * @param $newCategoryId
	 **/
	public function setCategoryId($newCategoryId) {
		if($newCategoryId === null) {
			$this->categoryId = null;
			return;
		}
		$newCategoryId = filter_var($newCategoryId, FILTER_VALIDATE_INT);
		if(empty($newCategoryId) === true) {
			throw (new InvalidArgumentException ("categoryId invalid"));
		}
		$this->categoryId = $newCategoryId;
	}


	/**
	 * Accessor for the name of subcategory
	 * @return string of subcategory name
	 **/
	public function getName() {
		return ($this->name);
	}

	/**
	 * Mutator for the name of subcategory
	 * @param $newName
	 **/
	public function setName($newName) {
		if($newName === null) {
			$this->name = null;
			return;
		}
		$newName = filter_var($newName, FILTER_SANITIZE_STRING);
		if(empty($newName) === true) {
			throw (new InvalidArgumentException ("name invalid"));
		}
		$this->name = $newName;
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
	 * Inserts subcategory into mySQL
	 *
	 * @param PDO $pdo
	 **/
	public function insert(PDO &$pdo) {
		// make sure subcategory doesn't already exist
		if($this->subcategoryId === null) {
			//create query template
			$query
				= "INSERT INTO subcategory (name)
		VALUES (:name)";
			$statement = $pdo->prepare($query);

			// bind the variables to the place holders in the template
			$parameters = array("name" => $this->name);
			$statement->execute($parameters);

			//update null subcategory with what mySQL just gave us
			$this->subcategoryId = intval($pdo->lastInsertId());
		}
	}

	/**
	 * Deletes subcategory from mySQL
	 *
	 * @param PDO $pdo
	 **/
	public function delete(PDO &$pdo) {
		// enforce the subcategory is not null
		if($this->subcategoryId === null) {
			throw(new PDOException("unable to delete a subcategory that does not exist"));
		}

		//create query template
		$query = "DELETE FROM subcategory WHERE subcategoryId = :subcategoryId";
		$statement = $pdo->prepare($query);

		//bind the member variables to the place holder in the template
		$parameters = array("subcategoryId" => $this->subcategoryId);
		$statement->execute($parameters);
	}

	/**
	 * Updates subcategory in mySQL
	 *
	 * @param PDO $pdo
	 */
	public function update(PDO &$pdo) {

		// create query template
		$query = "UPDATE subcategory SET name = :name WHERE subcategoryId = :subcategoryId";
		$statement = $pdo->prepare($query);

		// bind the member variables
		$parameters = array("name" => $this->name, "subcategoryId" => $this->subcategoryId);
		$statement->execute($parameters);
	}


	public static function getSubcategoryBySubcategoryId(PDO &$pdo, $subcategory) {

		$subcategory = filter_var($subcategory, FILTER_SANITIZE_STRING);
		if($subcategory === false) {
			throw(new PDOException(""));
		}
		// create query template
		$query = "SELECT subcategoryId, name FROM subcategory WHERE subcategoryId = :subcategoryId";
		$statement = $pdo->prepare($query);

		// bind the subcategory id to the place holder in the template
		$parameters = array("subcategory" => $subcategory);
		$statement->execute($parameters);

		// grab the subcategory from mySQL
		try {
			$subcategory = null;
			$statement->setFetchMode(PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$subcategory = new Subcategory ($row["subcategoryId"], $row["categoryId"], $row["name"]);
			}
		} catch(Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new PDOException($exception->getMessage(), 0, $exception));
		}
		return ($subcategory);
	}

	public static function getSubcategoriesByCategoryId(PDO &$pdo, $categoryId) {

		$categoryId = Filter::filterInt($categoryId, "Category ID");
		// create query template
		$query = "SELECT subcategoryId, categoryId, name FROM subcategory WHERE categoryId = $categoryId";
		$statement = $pdo->prepare($query);
		$statement->execute();

		// Grab the subcategories from MySQL
		$subcategories = new SplFixedArray($statement->rowCount());
		$statement->setFetchMode(PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$subcategory = new Subcategory ($row["subcategoryId"], $row["categoryId"], $row["name"]);
				$subcategories[$subcategories->key()] = $subcategory;
				$subcategories->next();
			} catch(Exception $exception) {
				// Rethrow to caller
				throw new Exception($exception->getMessage(), 0, $exception);
			}
		}

		return $subcategories;
	}

	/**
	 * Get all subcategories
	 *
	 * @param PDO $pdo
	 * @return SplFixedArray $subcategories
	 * @throws Exception catch-all error handling
	 **/
	public static function getAllSubcategories(PDO &$pdo) {

		// create query template
		$query = "SELECT subcategoryId, categoryId, name FROM subcategory";
		$statement = $pdo->prepare($query);
		$statement->execute();

		// Grab the subcategories from MySQL
		$subcategories = new SplFixedArray($statement->rowCount());
		$statement->setFetchMode(PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$subcategory = new Subcategory ($row["subcategoryId"], $row["categoryId"], $row["name"]);
				$subcategories[$subcategories->key()] = $subcategory;
				$subcategories->next();
			} catch(Exception $exception) {
				// Rethrow to caller
				throw new Exception($exception->getMessage(), 0, $exception);
			}
		}

		return $subcategories;
	}
}
