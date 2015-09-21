<?php

require_once("../helpers/filter.php");

/**
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
     * @var string $category
     */
    private $category;

    /**
     * @param int $businessId primary key of the business
     * @param string $name name of the business
     * @param string $location location of the business
     * @param string $phone phone number of the business
     * @param string $website website of the business
     * @param string $email email of the business
     * @param string $category category of the business
     */
    public function __construct($businessId, $name, $location, $phone, $website, $email, $category) {
        try {
            $this->setBusinessId($businessId);
            $this->setName($name);
            $this->setLocation($location);
            $this->setPhone($phone);
            $this->setWebsite($website);
            $this->setEmail($email);
            $this->setCategory($category);
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
        $this->location = Filter::filterString($location, "Business location", 128);
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
        $this->phone = Filter::filterString($phone, "Business phone number", 64);
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
        $this->website = Filter::filterString($website, "Business website", 64);
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
        $this->email = Filter::filterString($email, "Business email", 64);
    }

    /**
     * @return string
     */
    public function getCategory() {
        return $this->category;
    }

    /**
     * @param string $category
     */
    public function setCategory($category) {
        $this->category = Filter::filterString($category, "Business category", 64);
    }

    public function JsonSerialize() {
        $fields = get_object_vars($this);
        return ($fields);
    }

    // TODO: DB Stuff

}