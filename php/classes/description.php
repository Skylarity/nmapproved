<?php

require_once("../helpers/filter.php");

/**
 * This class contains the description for a business.
 *
 * @author Skyler Rexroad
 */
class Description implements JsonSerializable {

    /**
     * Business ID, primary key
     * @var int $businessId
     */
    private $businessId;

    /**
     * Business description
     * @var string $description
     */
    private $description;

    /**
     * @param int $businessId business ID
     * @param string $description business description
     */
    public function __construct($businessId, $description) {
        try {
            $this->setBusinessId($businessId);
            $this->setDescription($description);
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
        $this->businessId = Filter::filterInt($businessId, "Business ID", true);
    }

    /**
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description) {
        $this->description = Filter::filterString($description, "Business description");
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
     * Inserts business description into MySQL
     * @param PDO $pdo
     */
    public function insert(PDO &$pdo) {
        // Create query template
        $query = "INSERT INTO description(businessId, description) VALUES (:businessId, :description)";
        $statement = $pdo->prepare($query);

        // Bind the variables to the placeholders.
        $parameters = array("businessId" => $this->getBusinessId(), "description" => $this->getDescription());
        $statement->execute($parameters);
    }

    /**
     * Deletes business description from MySQL
     * @param PDO $pdo
     */
    public function delete(PDO &$pdo) {
        // Verify business is NOT NULL
        if($this->businessId === null) {
            throw new PDOException("Unable to delete description from a nonexistent business");
        }

        // Create query template
        $query = "DELETE FROM description WHERE businessId = :businessId";
        $statement = $pdo->prepare($query);

        // Bind the variables to the placeholders
        $parameters = array("businessId" => $this->getBusinessId());
        $statement->execute($parameters);
    }

    /**
     * Updates business description in MySQL
     * @param PDO $pdo
     */
    public function update(PDO &$pdo) {
        // Create query template
        $query = "UPDATE description SET description = :description WHERE businessId = :businessId";
        $statement = $pdo->prepare($query);

        // Bind the variables to the placeholders.
        $parameters = array("businessId" => $this->getBusinessId(), "description" => $this->getDescription());
        $statement->execute($parameters);
    }

    /**
     * @param PDO $pdo
     * @param $businessId business ID to get the description by
     * @return SplFixedArray array of descriptions
     * @throws Exception catch-all error handling
     */
    public function getDescriptionByBusinessId(PDO &$pdo, $businessId) {
        // Filter ID
        $businessId = Filter::filterInt($businessId, "Description's business ID");

        // Create query template
        $query = "SELECT businessId, description FROM description WHERE businessId = :businessId";
        $statement = $pdo->prepare($query);

        // Bind the variables to the placeholders
        $parameters = array("businessId" => $businessId);
        $statement->execute($parameters);

        // Grab the description(s) from MySQL
        $descriptions = new SplFixedArray($statement->rowCount());
        $statement->setFetchMode(PDO::FETCH_ASSOC);
        while(($row = $statement->fetch()) !== false) {
            try {
                $description = new Description($row["businessId"], $row["description"]);
                $descriptions[$descriptions->key()] = $description;
                $descriptions->next();
            } catch(Exception $exception) {
                // Rethrow to caller
                throw new Exception($exception->getMessage(), 0, $exception);
            }
        }

        return $descriptions;
    }

}