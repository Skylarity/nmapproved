<?php

require_once(dirname(__DIR__) . "/helpers/filter.php");

class BusinessCategory {

	/**
	 * Business ID to link to subcategory
	 * @var $businessId
	 */
	private $businessId;

	/**
	 * Subcategory to link to business ID
	 * @var $subcategoryId
	 */
	private $subcategoryId;

	public function __constructor($businessId, $subcategoryId) {
		try {
			$this->setBusinessId($businessId);
			$this->setSubcategoryId($subcategoryId);
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
	 * @return mixed
	 */
	public function getBusinessId() {
		return $this->businessId;
	}

	/**
	 * @param mixed $businessId
	 */
	public function setBusinessId($businessId) {
		$this->businessId = Filter::filterInt($businessId, "Business ID");
	}

	/**
	 * @return mixed
	 */
	public function getSubcategoryId() {
		return $this->subcategoryId;
	}

	/**
	 * @param mixed $subcategoryId
	 */
	public function setSubcategoryId($subcategoryId) {
		$this->subcategoryId = Filter::filterInt($subcategoryId, "Subcategory ID");
	}

	// TODO: Database functions

}