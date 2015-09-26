<?php

require_once(dirname(__DIR__) . "/helpers/filter.php");

class Category implements JsonSerializable {


	private $categoryId;

	private $name;

	/**
	 * @param int $categoryId
	 * @param string $name
	 */
	public function __construct($categoryId, $name) {
		try {
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
	 * Implements toString for this class
	 * @return string
	 */
	public function __toString() {
		return $this->getName();
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
	 * Inserts category into mySQL
	 *
	 * @param PDO $pdo
	 **/
	public function insert(PDO &$pdo) {
		// make sure category doesn't already exist
		if($this->categoryId === null) {
			//create query template
			$query
				= "INSERT INTO category (name) VALUES (:name)";
			$statement = $pdo->prepare($query);

			// bind the variables to the place holders in the template
			$parameters = array("name" => $this->name);
			$statement->execute($parameters);

			//update null subcategory with what mySQL just gave us
			$this->categoryId = intval($pdo->lastInsertId());
		}
	}

	/**
	 * Deletes category from mySQL
	 *
	 * @param PDO $pdo
	 **/
	public function delete(PDO &$pdo) {
		// enforce the category is not null
		if($this->categoryId === null) {
			throw(new PDOException("unable to delete a category that does not exist"));
		}

		//create query template
		$query = "DELETE FROM category WHERE categoryId = :categoryId";
		$statement = $pdo->prepare($query);

		//bind the member variables to the place holder in the template
		$parameters = array("categoryId" => $this->categoryId);
		$statement->execute($parameters);
	}

	/**
	 * Updates subcategory in mySQL
	 *
	 * @param PDO $pdo
	 */
	public function update(PDO &$pdo) {

		// create query template
		$query = "UPDATE category SET name = :name WHERE categoryId = :categoryId";
		$statement = $pdo->prepare($query);

		// bind the member variables
		$parameters = array("name" => $this->name, "categoryId" => $this->categoryId);
		$statement->execute($parameters);
	}


	public static function getCategoryByCategoryId(PDO &$pdo, $category) {

		$category = filter_var($category, FILTER_SANITIZE_STRING);
		if($category === false) {
			throw(new PDOException(""));
		}
		// create query template
		$query = "SELECT categoryId, name FROM category WHERE categoryId = :categoryId";
		$statement = $pdo->prepare($query);

		// bind the subcategory id to the place holder in the template
		$parameters = array("categoryId" => $category);
		$statement->execute($parameters);

		// grab the subcategory from mySQL
		try {
			$user = null;
			$statement->setFetchMode(PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$category = new Category ($row["categoryId"], $row["name"]);
			}
		} catch(Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new PDOException($exception->getMessage(), 0, $exception));
		}
		return ($category);
	}

	public static function getCategoryByCategoryName(PDO &$pdo, $name) {

		$name = Filter::filterString($name, "Category name");
		// create query template
		$query = "SELECT categoryId, name FROM category WHERE name = :name";
		$statement = $pdo->prepare($query);

		// bind the subcategory id to the place holder in the template
		$parameters = array("name" => $name);
		$statement->execute($parameters);

		// grab the subcategory from mySQL
		try {
			$category = null;
			$statement->setFetchMode(PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$category = new Category ($row["categoryId"], $row["name"]);
			}
		} catch(Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new PDOException($exception->getMessage(), 0, $exception));
		}
		return ($category);
	}

	/**
	 * Get all categorys
	 *
	 * @param PDO $pdo
	 * @return category
	 **/
	public static function getAllCategories(PDO &$pdo) {

		// create query template
		$query = "SELECT categoryId, name FROM category";
		$statement = $pdo->prepare($query);

		// grab the user from mySQL
		try {
			$category = null;
			$statement->setFetchMode(PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$category = new Category ($row["categoryId"], $row["name"]);
			}
		} catch(Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new PDOException($exception->getMessage(), 0, $exception));
		}
		return ($category);
	}
}
