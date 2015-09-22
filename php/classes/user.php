<?php


/**
 * Class User for sites bases of users
 *
 * The class to handle to users
 *
 * @author Charles Sandidge charles@designbyNinja.com
 **/
class User implements JsonSerializable {

	/**
	 * id for user, this is primary key
	 * @var int $userId
	 **/
	private $userId;
	/**
	 * password hash for userId;
	 * @var string $passwordHash
	 **/
	private $hash;
	/**
	 * password salt for userId;
	 * @var string $passwordSalt
	 **/
	private $salt;
	/**
	 * email of userId
	 * @var string $email
	 **/
	private $email;

/**
 *
 */
	public function __construct($newUserId, $newHash, $newSalt, $newEmail) {
		try {
			$this->setUserId($newUserId);
			$this->setHash($newHash);
			$this->setSalt($newSalt);
			$this->setEmail($newEmail);
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
	 * accessor method for userId
	 *
	 * @return int value of unique userId
	 **/
	public function getUserId() {
		return ($this->userId);
	}

	/**
	 * mutator method for the userId
	 *
	 * @param int unique value to rep a user $newUserId
	 * @throws InvalidArgumentException for invalid account
	 **/
	public function setUserId($newUserId) {
		// base case: if the userId is null,
		// this is a new user without a mySQL assigned id (yet)
		if($newUserId === null) {
			$this->userId = null;
			return;
		}
		//verify the User is valid
		$newUserId = filter_var($newUserId, FILTER_VALIDATE_INT);
		if(empty($newUserId) === true) {
			throw (new InvalidArgumentException ("userId invalid"));
		}
		$this->userId = $newUserId;
	}

	/**
	 * accessor method for Hash
	 * @return string of users password Hash
	 **/
	public function getHash() {
		return ($this->hash);
	}

	/**
	 * Mutator for Hash -insure it is 128 length string
	 *
	 * @param string of users $newHash
	 * @throws InvalidArgumentException if newHash is not valid int
	 * @throws RangeException if newHash is not exactly 128 xdigits
	 **/

	public function setHash($newHash) {
		// verify Hash is exactly string of 128
		if((ctype_xdigit($newHash)) === false) {
			if(empty($newHash) === true) {
				throw new InvalidArgumentException ("hash invalid");
			}
			if(strlen($newHash) !== 128) {
				throw new RangeException ("hash not valid");
			}
		}
		$this->hash = $newHash;
	}

	/**
	 * accessor method for Salt
	 *
	 * @return string of Salt for user password
	 **/
	public
	function getSalt() {
		return ($this->salt);
	}

	/**
	 * mutator method for Salt
	 *
	 * @param string of users password salt $newSalt
	 * @throw InvalidArgumentException if salt is not valid int
	 * @throw RangeException if salt is not exactly 64 xdigits
	 **/
	public function setSalt($newSalt) {
		// verify salt is exactly string of 64
		if((ctype_xdigit($newSalt)) === false) {
			if(empty($newSalt) === true) {
				throw new InvalidArgumentException ("salt invalid");
			}
			if(strlen($newSalt) !== 64) {
				throw (new RangeException ("salt not valid"));
			}
		}
		$this->salt = $newSalt;
	}

	/**
	 * accessor method for email
	 *
	 * @return string of email for user
	 **/
	public function getEmail() {
		return ($this->email);
	}

	/**
	 * Mutator method for Email
	 *
	 * @param string of users' email $newEmail
	 * @throws InvalidArgumentException if email does not pass sanitization
	 * @throws RangeException if email is longer than 64 characters
	 **/
	public function setEmail($newEmail) {
		// verify email is valid
		$newEmail = filter_var($newEmail, FILTER_SANITIZE_EMAIL);
		if(empty($newEmail) === true) {
			throw new InvalidArgumentException ("user email invalid");
		}
		if(strlen($newEmail) > 64) {
			throw(new RangeException ("Email content too large"));
		}
		$this->email = $newEmail;
	}


	public function JsonSerialize() {
		$fields = get_object_vars($this);
		unset ($fields["hash"]);
		unset ($fields["salt"]);
		return ($fields);
	}

	/**
	 * Inserts User into mySQL
	 *
	 * Inserts this userId into mySQL in intervals
	 * @param PDO $pdo connection to
	 **/
	public function insert(PDO &$pdo) {
		// make sure user doesn't already exist
		if($this->userId !== null) {
			throw (new PDOException("existing user"));
		}
		//create query template
		$query
			= "INSERT INTO user(hash, salt, email)
		VALUES (:hash, :salt, :email)";
		$statement = $pdo->prepare($query);

		// bind the variables to the place holders in the template
		$parameters = array("hash" => $this->hash, "salt" => $this->salt, "email" => $this->email);
		$statement->execute($parameters);

		//update null userId with what mySQL just gave us
		$this->userId = intval($pdo->lastInsertId());
	}

	/**
	 * Deletes User from mySQL
	 *
	 * Delete PDO to delete userId
	 * @param PDO $pdo
	 **/
	public function delete(PDO &$pdo) {
		// enforce the user is not null
		if($this->userId === null) {
			throw(new PDOException("unable to delete a user that does not exist"));
		}

		//create query template
		$query = "DELETE FROM user WHERE userId = :userId";
		$statement = $pdo->prepare($query);

		//bind the member variables to the place holder in the template
		$parameters = array("userId" => $this->userId);
		$statement->execute($parameters);
	}

	/**
	 * updates User in mySQL
	 *
	 * Update PDO to update user class
	 * @param PDO $pdo pointer to PDO connection, by reference
	 **/
	public function update(PDO &$pdo) {

		// create query template
		$query = "UPDATE user SET hash = :hash, salt = :salt,  email = :email WHERE userId = :userId";
		$statement = $pdo->prepare($query);

		// bind the member variables
		$parameters = array("hash" => $this->hash, "salt" => $this->salt, "email" => $this->email, "userId" => $this->userId);
		$statement->execute($parameters);
	}

	/**
	 * get user by email
	 *
	 * @param PDO $pdo pointer to PDO connection, by reference
	 * @param mixed info for $user
	 * @return null|User
	 **/
	public static function getUserByEmail(PDO &$pdo, $user) {
		// sanitize the email before searching
		$user = filter_var($user, FILTER_SANITIZE_EMAIL);
		if($user === false) {
			throw(new PDOException(""));
		}
		// create query template
		$query = "SELECT userId, hash, salt, email FROM user WHERE email = :email";
		$statement = $pdo->prepare($query);

		// bind the user id to the place holder in the template
		$parameters = array("email" => $user);
		$statement->execute($parameters);

		// grab the user from mySQL
		try {
			$user = null;
			$statement->setFetchMode(PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$user = new User ($row["userId"], $row["hash"],$row["salt"], $row["email"]);
			}
		} catch(Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new PDOException($exception->getMessage(), 0, $exception));
		}
		return ($user);
	}
}

