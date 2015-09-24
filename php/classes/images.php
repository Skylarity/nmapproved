<?php


class Image implements JsonSerializable {

	private $imageId;

	private $imageType;

	private $imagePath;


	/**
	 * Constructor for image class
	 *
	 * @param $newImageId
	 * @param $newImageType
	 * @param $newImagePath
	 * @throws Exception
	 */
	public function __construct($newImageId, $newImageType, $newImagePath) {
		try {
			$this->setImageId($newImageId);
			$this->setImageType($newImageType);
			$this->setImagePath($newImagePath);
		} catch(InvalidArgumentException $invalidArgument) {
			//rethrow the exception to the caller
			throw(new InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			// rethrow the exception to the caller
			throw (new RangeException($range->getMessage(), 0, $range));
		} catch(Exception $exception) {
			// rethrow generic exception
			throw(new Exception($exception->getMessage(), 0, $exception));
		}
	}

	/**
	 * Accessor for imageId
	 * @return int value of imageId
	 **/
	public function getImageId() {
		return ($this->imageId);
	}

	/**
	 * Mutator for imageId
	 * @param $newImageId
	 **/
	public function setImageId($newImageId) {
		if($newImageId === null) {
			$this->imageId = null;
			return;
		}
		$newImageId = filter_var($newImageId, FILTER_VALIDATE_INT);
		if(empty($newImageId) === true) {
			throw (new InvalidArgumentException ("imageId invalid"));
		}
		$this->imageId = $newImageId;
	}

	/**
	 * Accessor for image type
	 * @return string of image type
	 **/
	public function getImageType() {
		return ($this->imageType);
	}

	/**
	 * Mutator for image type
	 * @param $newImageType
	 */
	public function setImageType($newImageType) {
		if($newImageType === null) {
			$this->imageId = null;
			return;
		}
		$newImageType = filter_var($newImageType, FILTER_SANITIZE_STRING);
		if(empty($newImageType) === true) {
			throw (new InvalidArgumentException ("imageType invalid"));
		}
		$this->imageType = $newImageType;
	}

	/**
	 * Accessor for image path
	 * @return string for image path
	 */
	public function getImagePath() {
		return ($this->imagePath);
	}

	/**
	 * Mutator for image Path
	 * @param $newImagePath
	 **/
	public function setImagePath($newImagePath) {
		if($newImagePath === null) {
			$this->imagePath = null;
			return;
		}
		$newImagePath = filter_var($newImagePath, FILTER_SANITIZE_STRING);
		if(empty($newImagePath) === true) {
			throw (new InvalidArgumentException ("imagePath invalid"));
		}
		$this->imagePath = $newImagePath;
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
	 * Inserts Image into database mySQL
	 *
	 * @param PDO $pdo
	 **/
	public function insert(PDO &$pdo) {
		// make sure image doesn't already exist
		if($this->imageId !== null) {
			throw (new PDOException("existing image"));
		}
		//create query template
		$query
			= "INSERT INTO image (imageType, imagePath)
		VALUES (:imageType, :imagePath)";
		$statement = $pdo->prepare($query);

		// bind the variables to the place holders in the template
		$parameters = array("imageType" => $this->imageType, "imagePath" => $this->imagePath);
		$statement->execute($parameters);

		//update null userId with what mySQL just gave us
		$this->imageId = intval($pdo->lastInsertId());
	}

	/**
	 * Deletes image from mySQL
	 *
	 * @param PDO $pdo
	 **/
	public function delete(PDO &$pdo) {
		// enforce the image is not null
		if($this->imageId === null) {
			throw(new PDOException("unable to delete an image that does not exist"));
		}

		//create query template
		$query = "DELETE FROM image WHERE imageId = :imageId";
		$statement = $pdo->prepare($query);

		//bind the member variables to the place holder in the template
		$parameters = array("imageId" => $this->imageId);
		$statement->execute($parameters);
	}

	/**
	 * Updates image in mySQL
	 *
	 * @param PDO $pdo
	 */
	public function update(PDO &$pdo) {

		// create query template
		$query = "UPDATE image SET imageType = :imageType, imagePath = :imagePath WHERE imageId = :imageId";
		$statement = $pdo->prepare($query);

		// bind the member variables
		$parameters = array("imageType" => $this->imageType, "imagePath" => $this->imagePath, "imageId" => $this->imageId);
		$statement->execute($parameters);
	}

	public static function getImageByImageId(PDO &$pdo, $image) {

		$image = filter_var($image, FILTER_SANITIZE_STRING);
		if($image === false) {
			throw(new PDOException(""));
		}
		// create query template
		$query = "SELECT imageId, imagePath, imageType FROM image WHERE imageId = :imageId";
		$statement = $pdo->prepare($query);

		// bind the image id to the place holder in the template
		$parameters = array("imageId" => $image);
		$statement->execute($parameters);

		// grab the image from mySQL
		try {
			$user = null;
			$statement->setFetchMode(PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$image = new Image ($row["imageId"], $row["imagePath"], $row["imageType"]);
			}
		} catch(Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new PDOException($exception->getMessage(), 0, $exception));
		}
		return ($image);
	}
}