<?php

/**
 * Requirements
 * - PHP >= 5.4
 * - Composer
 * - Guzzle HTTP Client
 */

/**
 * Requires Guzzle HTTP Client
 * @link http://guzzlephp.org
 */
use Guzzle\Http\Client;

/**
 * InHouse Connexions API
 * This class used to communicate with inhouse connexions api
 * You'll need a login and a token to be able to use the service
 * @author Vincent Gabriel <vadimg88@gmail.com>
 * @since Jan 2014
 */
class Connexions
{
	/**
	 * @var string connexions endpoint
	 */
	protected static $endpoint = 'https://{lender}inhouseusa.com/tandem/services/connexionsapi/';
	/**
	 * @var string connexions lender attribute
	 */
	protected static $lender = 'uat-2.';
	/**
	 * @var string connexions login
	 */
	protected static $login;
	/**
	 * @var string connexions token
	 */
	protected static $token;
	/**
	 * @var string connexions api method
	 */
	protected static $method;

	/**
	 * @var string connexions api full uri used
	 */
	protected static $fulluri;
	/**
	 * @var obj Guzzle Object
	 */
	protected static $request;
	/**
	 * @var obj Guzzle object
	 */
	protected static $response;

	/**
	 * @var string request body sent
	 */
	protected static $requestBody;
	/**
	 * @var array request headers
	 */
	protected static $requestHeaders = [];
	/**
	 * @var array request options
	 */
	protected static $requestOptions = [];
	/**
	 * @var array request query
	 */
	protected static $requestQuery = [];
	/**
	 * @var array status update options
	 */
	protected static $statusUpdateOptions = [];

	/**
	 * @var int request error code
	 */
	protected static $requestErrorCode = 0;
	/**
	 * @var string request error message
	 */
	protected static $requestErrorMessage = null;

	/**
	 * @var obj Guzzle client
	 */
	protected static $client;

	/**
	 * @var obj DOM
	 */
	protected static $domDocument;

	/**
	 * @var array
	 */
	protected static $domDocumentOptions = [];

	/**
	 * Connexsions API methods
	 */
	const METHOD_GET_APPRAISALS = 'getAppraisalOrders';
	const METHOD_GET_APPRAISAL = 'getAppraisal';
	const METHOD_GET_UPDATED_APPRAISALS = 'getUpdatedAppraisals';
	const METHOD_GET_UPDATED_APPRAISAL = 'getUpdatedAppraisal';
	const METHOD_GET_CONDITIONED_ORDERS = 'getConditionedOrders';
	const METHOD_GET_CONDITIONS = 'getConditions';
	const METHOD_GET_DOCUMENT_FILE_IDENTIFIERS = 'getDocumentFileIdentifiers';
	const METHOD_SET_STATUS_UPDATE = 'setStatusUpdate';

	/**
	 * @var methods
	 */
	protected static $methods = [
		self::METHOD_GET_APPRAISALS => 'getAppraisalOrders',
		self::METHOD_GET_APPRAISAL => 'getAppraisal',
		self::METHOD_GET_UPDATED_APPRAISALS => 'getUpdatedAppraisals',
		self::METHOD_GET_UPDATED_APPRAISAL => 'getUpdatedAppraisal',
		self::METHOD_GET_CONDITIONED_ORDERS => 'getConditionedOrders',
		self::METHOD_GET_CONDITIONS => 'getConditions',
		self::METHOD_GET_DOCUMENT_FILE_IDENTIFIERS => 'getDocumentFileIdentifiers',
		self::METHOD_SET_STATUS_UPDATE => 'setStatusUpdate',
	];

	/** 
	* Action Types
	*/
	const ACTION_TYPE_ORIGINAL = 'Original';
	const ACTION_TYPE_UPDATE = 'Update';
	const ACTION_TYPE_HOLD = 'Hold';
	const ACTION_TYPE_RESUME = 'Resume';
	const ACTION_TYPE_CANCEL = 'Cancellation';

	/**
	 * @var array action type list
	 */
	protected static $actionTypes = [
		self::ACTION_TYPE_ORIGINAL => 'Original',
		self::ACTION_TYPE_UPDATE => 'Update',
		self::ACTION_TYPE_HOLD => 'Hold',
		self::ACTION_TYPE_RESUME => 'Resume',
		self::ACTION_TYPE_CANCEL => 'Cancellation',
	];

	/**
	 * Contacts
	 */
	const CONTACT_REQUESTED_BY = 'RequestedBy';
	const CONTACT_ASSOCIATED_USER = 'AssociatedUser';

	/**
	 * @var array contacts list
	 */
	protected static $contactTypes = [
		self::CONTACT_REQUESTED_BY => 'RequestedBy',
		self::CONTACT_ASSOCIATED_USER => 'AssociatedUser',
	];

	/**
	 * Contact Points
	 */
	const CONTACT_POINT_PHONE = 'Phone';
	const CONTACT_POINT_EMAIL = 'Email';
	const CONTACT_POINT_FAX = 'Fax';

	/**
	 * @var array contact points list
	 */
	protected static $contactPointTypes = [
		self::CONTACT_POINT_PHONE => 'Phone',
		self::CONTACT_POINT_EMAIL => 'Email',
		self::CONTACT_POINT_FAX => 'Fax',
	];

	/**
	 * Contact points location
	 */
	const CONTACT_POINT_LOCATION_WORK = 'Work';
	const CONTACT_POINT_LOCATION_MOBILE = 'Mobile';

	/**
	 * @var array contact points location
	 */
	protected static $contactPointsLocationTypes = [
		self::CONTACT_POINT_LOCATION_WORK => 'Work',
		self::CONTACT_POINT_LOCATION_MOBILE => 'Mobile',
	];
 
	/**
	 * Embedded files
	 */
	const FILE_ORIGINAL_APPRAISAL = 1;
	const FILE_PURCHASE = 2;
	const FILE_APPRAISAL_REPORT = 3;
	const FILE_AMC_CERTIFICATION = 4;
	const FILE_CONSTRUCTION_PLANS = 5;
	const FILE_PRELIMINARY_TITLE_REPORT = 6;
	const FILE_CONDO_CERTIFICATION = 7;
	const FILE_OTHER_DOCUMENT = 8;
	const FILE_INVOICE = 9;
	const FILE_REBUTTALS = 23;

	/**
	 * @var array embedded files
	 */
	protected static $embeddedFileTypes = [
		self::FILE_ORIGINAL_APPRAISAL => 'Original Appraisal',
		self::FILE_PURCHASE => 'Purchase Agreement',
		self::FILE_APPRAISAL_REPORT => 'Appraisal Report',
		self::FILE_AMC_CERTIFICATION => 'AMC Certification',
		self::FILE_CONSTRUCTION_PLANS => 'Construction Plans/Specs',
		self::FILE_PRELIMINARY_TITLE_REPORT => 'Preliminary Title Report',
		self::FILE_CONDO_CERTIFICATION => 'Condo/HOA Certification',
		self::FILE_OTHER_DOCUMENT => 'Other Document',
		self::FILE_INVOICE => 'Invoice',
		self::FILE_REBUTTALS => 'Rebuttals',
	];

	/**
	 * Hold reason types
	 */
	const HOLD_REASON_LENDER_LETTER_OF_INTENT = 1;
	const HOLD_REASON_LENDER_PURCAHSE_AGREEMENT = 2;
	const HOLD_REASON_LENDER_FHA_CASE_NUMBER = 3;
	const HOLD_REASON_LENDER_CREDIT_CARD_INFO = 4;
	const HOLD_REASON_LENDER_HOLD = 5;
	const HOLD_REASON_LENDER_OTHER = 6;
	const HOLD_REASON_BORROWER_NO_RETURN_CALL = 7;
	const HOLD_REASON_BORROWER_OUT_OF_TOWN = 8;
	const HOLD_REASON_BORROWER_DELAYED_INSPECTION_TIME = 9;
	const HOLD_REASON_BORROWER_REPAIRS_NOT_COMPLETE = 10;
	const HOLD_REASON_BORROWER_OTHER = 11;
	const HOLD_REASON_LENDER_CONDO_QUESTIONS = 14;
	const HOLD_REASON_LENDER_SHORT_SALE = 15;

	/**
	 * @var array hold reason list
	 */
	protected static $holdReasonTypes = [
		self::HOLD_REASON_LENDER_LETTER_OF_INTENT => 'Lender Delay – Letter of Intent',
		self::HOLD_REASON_LENDER_PURCAHSE_AGREEMENT => 'Lender Delay – Purchase Agreement',
		self::HOLD_REASON_LENDER_FHA_CASE_NUMBER => 'Lender Delay – FHA Case Number',
		self::HOLD_REASON_LENDER_CREDIT_CARD_INFO => 'Lender Delay – Credit Card Info',
		self::HOLD_REASON_LENDER_HOLD => 'Lender Delay – Lender Hold',
		self::HOLD_REASON_LENDER_OTHER => 'Lender Delay – Other',
		self::HOLD_REASON_BORROWER_NO_RETURN_CALL => 'Borrower/Agent Delay – No Return Call',
		self::HOLD_REASON_BORROWER_OUT_OF_TOWN => 'Borrower/Agent Delay – Out of Town',
		self::HOLD_REASON_BORROWER_DELAYED_INSPECTION_TIME => 'Borrower/Agent Delay – Delayed Inspection Time',
		self::HOLD_REASON_BORROWER_REPAIRS_NOT_COMPLETE => 'Borrower/Agent Delay – Repairs Not Complete',
		self::HOLD_REASON_BORROWER_OTHER => 'Borrower/Agent Delay – Other',
		self::HOLD_REASON_LENDER_CONDO_QUESTIONS => 'Lender Delay – Condo Questionnaire',
		self::HOLD_REASON_LENDER_SHORT_SALE => 'Lender Delay – Short Sale',
	];

	/**
	 * loan purpose
	 */
	const LOAN_PURPOSE_PURCHASE = 'Purchase';
	const LOAN_PURPOSE_REFINANCE = 'Refinance';

	/**
	 * @var array loan purpose list
	 */
	protected static $loanPurposeTypes = [
		self::LOAN_PURPOSE_PURCHASE => 'Purchase',
		self::LOAN_PURPOSE_REFINANCE => 'Refinance',
	];

	/**
	 * Loan Types
	 */
	const LOAN_TYPE_CONVENTIONAL = 'Conventional';
	const LOAN_TYPE_FHA = 'FHA';
	const LOAN_TYPE_FARMERS = 'FarmersHomeAdministration';
	const LOAN_TYPE_OTHER = 'Other';

	/**
	 * @var array loan types
	 */
	protected static $loanTypes = [
		self::LOAN_TYPE_CONVENTIONAL => 'Conventional',
		self::LOAN_TYPE_FHA => 'FHA',
		self::LOAN_TYPE_FARMERS => 'FarmersHomeAdministration',
		self::LOAN_TYPE_OTHER => 'Other',
	];

	/**
	 * Payment Type
	 */
	const PAYMENT_TYPE_AMERICAN = 'AmericanExpress';
	const PAYMENT_TYPE_DISCOVER = 'Discover';
	const PAYMENT_TYPE_MASTERCARD = 'MasterCard';
	const PAYMENT_TYPE_VISA = 'Visa';

	/**
	 * @var array payment types
	 */
	protected static $paymentTypes = [
		self::PAYMENT_TYPE_AMERICAN => 'AmericanExpress',
		self::PAYMENT_TYPE_DISCOVER => 'Discover',
		self::PAYMENT_TYPE_MASTERCARD => 'MasterCard',
		self::PAYMENT_TYPE_VISA => 'Visa',
	];

	/**
	 * Role types
	 */
	const ROLE_TYPE_SYS_ADMIN = 'SystemAdmin';
	const ROLE_TYPE_APPRAISAL_COORDINATOR = 'AppraisalCoordinator';
	const ROLE_TYPE_LOAN_OFFICER = 'LoanOfficer';
	const ROLE_TYPE_ACCOUNT_EXEC = 'AccountExecutive';
	const ROLE_TYPE_PROCESSOR = 'Processor';
	const ROLE_TYPE_BROKER = 'Broker';

	/**
	 * @var array role types
	 */
	protected static $roleTypes = [
		self::ROLE_TYPE_SYS_ADMIN => 'SystemAdmin',
		self::ROLE_TYPE_APPRAISAL_COORDINATOR => 'AppraisalCoordinator',
		self::ROLE_TYPE_LOAN_OFFICER => 'LoanOfficer',
		self::ROLE_TYPE_ACCOUNT_EXEC => 'AccountExecutive',
		self::ROLE_TYPE_PROCESSOR => 'Processor',
		self::ROLE_TYPE_BROKER => 'Broker',
	];

	/**
	 * Status Codes
	 */
	const STATUS_CODE_ASSIGNED = 3;
	const STATUS_CODE_AMC_ACCEPTED = 4;
	const STATUS_CODE_AMC_REJECTED = 5;
	const STATUS_CODE_SCHEDULED = 6;
	const STATUS_CODE_INSPECTED = 7;
	const STATUS_CODE_COMPLETED = 8;
	const STATUS_CODE_HOLD = 9;
	const STATUS_CODE_CANCELLED = 5;

	/**
	 * @var array status codes
	 */
	protected static $statusCodes = [
		self::STATUS_CODE_ASSIGNED => 'Order Assigned',
		self::STATUS_CODE_AMC_ACCEPTED => 'AMC Accepted',
		self::STATUS_CODE_AMC_REJECTED => 'AMC Rejected',
		self::STATUS_CODE_SCHEDULED => 'Scheduled',
		self::STATUS_CODE_INSPECTED => 'Inspected',
		self::STATUS_CODE_COMPLETED => 'Completed',
		self::STATUS_CODE_HOLD => 'Hold',
		self::STATUS_CODE_CANCELLED => 'Cancelled (AMC Rejected)',
	];

	/**
	 * Title Categories
	 */
	const TITLE_CAT_SINGLE_FAMILY = 'SingleFamily';
	const TITLE_CAT_CONDOMINIUM = 'Condominium';
	const TITLE_CAT_COOPERATIVE = 'Cooperative';
	const TITLE_CAT_TWO_FOUR_UNIT = 'TwoToFourUnitProperty';
	const TITLE_CAT_MANF_MOBILE_HOME = 'ManufacturedMobileHome';
	const TITLE_CAT_VACANT_LAND = 'VacantLand';
	const TITLE_CAT_OTHER = 'Other';

	/**
	 * @var array title categories
	 */
	protected static $titleCategories = [
		self::TITLE_CAT_SINGLE_FAMILY => 'SingleFamily',
		self::TITLE_CAT_CONDOMINIUM => 'Condominium',
		self::TITLE_CAT_COOPERATIVE => 'Cooperative',
		self::TITLE_CAT_TWO_FOUR_UNIT => 'TwoToFourUnitProperty',
		self::TITLE_CAT_MANF_MOBILE_HOME => 'ManufacturedMobileHome',
		self::TITLE_CAT_VACANT_LAND => 'VacantLand',
		self::TITLE_CAT_OTHER => 'Other',
	];


	/**
	 * Connexions API class construct
	 * @param string $login api login
	 * @param string $token api token
	 * @param string $lender api lender sub domain
	 * @param string $endpoint api endpoint url if different
	 * @return object
	 */
	public function __construct($login, $token, $lender=null, $endpoint=null) {
		// Set login and token
		$this->setLogin($login)->setToken($token);

		// Set lender
		if($lender!==null) {
			$this->setLender($lender);
		}

		// Set endpoint
		if($endpoint!==null) {
			$this->setEndPoint($endpoint);
		}

		// Create XML dom
		$this->createDomDocument();

		return $this;
	}

	/** Get Requests Start **/

	/**
	 * Get appraisals request method
	 * @return object
	 */
	public function getAppraisals() {
		$this->sendRequest(self::METHOD_GET_APPRAISALS);

		return $this;
	}

	/**
	 * Get appraisal request method
	 * @param int $orderId
	 * @return object
	 */
	public function getAppraisal($orderId) {
		// Add query
		$this->addQuery('appraisal_id', $orderId);

		// Send
		$this->sendRequest(self::METHOD_GET_APPRAISAL);

		return $this;
	}

	/**
	 * Get updated appraisals request method
	 * @return object
	 */
	public function getUpdatedAppraisals() {
		$this->sendRequest(self::METHOD_GET_UPDATED_APPRAISALS);

		return $this;
	}

	/**
	 * Get updated appraisal request method
	 * @param int $orderId
	 * @return object
	 */
	public function getUpdatedAppraisal($orderId) {
		// Add query
		$this->addQuery('appraisal_id', $orderId);

		// Send
		$this->sendRequest(self::METHOD_GET_UPDATED_APPRAISAL);

		return $this;
	}

	/**
	 * Get conditioned orders
	 * @return object
	 */
	public function getConditionedOrders() {
		$this->sendRequest(self::METHOD_GET_CONDITIONED_ORDERS);

		return $this;
	}

	/**
	 * Get appraisal order conditions
	 * @param int $orderId
	 * @return object
	 */
	public function getConditions($orderId) {
		// Add query
		$this->addQuery('appraisal_id', $orderId);

		// Send
		$this->sendRequest(self::METHOD_GET_CONDITIONS);

		return $this;
	}

	/**
	 * Get document file identifiers
	 * @return object
	 */
	public function getDocumentFileIdentifiers() {
		$this->sendRequest(self::METHOD_GET_DOCUMENT_FILE_IDENTIFIERS);

		return $this;
	}

	/**
	 * Get document file identifiers by date range
	 * @param string $from YYYY/MM/DD
	 * @param string $to YYYY/MM/DD
	 * @return object
	 */
	public function getDocumentFileIdentifiersByDate($from, $to) {
		// Add query
		$this->addQuery('FromDate', $from);
		$this->addQuery('ToDate', $to);

		$this->sendRequest(self::METHOD_GET_DOCUMENT_FILE_IDENTIFIERS);

		return $this;
	}

	/**
	 * Get document file identifiers by loan number
	 * @param string $loanNumber
	 * @return object
	 */
	public function getDocumentFileIdentifiersByLoanNumber($loanNumber) {
		// Add query
		$this->addQuery('Loan_Number', $loanNumber);

		$this->sendRequest(self::METHOD_GET_DOCUMENT_FILE_IDENTIFIERS);

		return $this;
	}

	/**
	 * Get document file identifiers by batch ID
	 * @param string $batchId
	 * @return object
	 */
	public function getDocumentFileIdentifiersByBatchId($batchId) {
		// Add query
		$this->addQuery('Batch_ID', $batchId);

		$this->sendRequest(self::METHOD_GET_DOCUMENT_FILE_IDENTIFIERS);

		return $this;
	}

	/**
	 * Set status update appraisal method
	 * @param int $orderId
	 * @return object
	 */
	public function setAppraisalStatusUpdate($orderId, $xml=null) {
		// Add query
		$this->addQuery('appraisal_id', $orderId);
		
		// Set xml body
		if($xml) {
			$this->setRequestBody($xml);
		} else {
			// Create the response body
			$responseGroup = $this->getDomDocument()->createElement('RESPONSE_GROUP');
			// Mismo
			$mismoVersionId = $this->getDomDocument()->createAttribute('MISMOVersionID');
			$mismoVersionId->value = '2.4';
			// ns
			$mismoNS = $this->getDomDocument()->createAttribute('xmlns:ns1');
			$mismoNS->value = 'http://www.inhouse-solutions.com';

			// Append
			$responseGroup->appendChild($mismoVersionId);
			$responseGroup->appendChild($mismoNS);

			// Create response
			$response = $this->getDomDocument()->createElement('RESPONSE');
			// Add to response group
			$responseGroup->appendChild($response);

			// Add any keys we have
			foreach($this->getStatusUpdateValues() as $key => $value) {
				$keyUpdate = $this->getDomDocument()->createElement('Key');
				// Name
				$keyName = $this->getDomDocument()->createAttribute('_Name');
				$keyName->value = $key;
				// Value
				$keyValue = $this->getDomDocument()->createAttribute('_Value');
				$keyValue->value = $value;

				$keyUpdate->appendChild($keyName);
				$keyUpdate->appendChild($keyValue);

				// Append
				$response->appendChild($keyUpdate);
			}

			// create response data
			$responseData = $this->getDomDocument()->createElement('RESPONSE_DATA');

			// Create valuation response
			$valuationResponse = $this->getDomDocument()->createElement('VALUATION_RESPONSE');

			// Mismo
			$mismoVersionId = $this->getDomDocument()->createAttribute('MISMOVersionID');
			$mismoVersionId->value = '2.4';
			$valuationResponse->appendChild($mismoVersionId);

			// Create Parties element
			$parties = $this->getDomDocument()->createElement('PARTIES');

			// Do we have any borrower info set
			if($this->getBorrower()) {
				// Add borrower
				$borrower = $this->getDomDocument()->createElement('BORROWER');
				foreach($this->getBorrower() as $k => $v) {
					if($v) {
						$borrowerElement = $this->getDomDocument()->createAttribute($k);
						$borrowerElement->value = $v;
						$borrower->appendChild($borrowerElement);
					}
				}
				// Add to the element
				$parties->appendChild($borrower);
			}

			// Do we have any co borrower info set
			if($this->getCoBorrower()) {
				// Add borrower
				$borrower = $this->getDomDocument()->createElement('BORROWER');
				foreach($this->getCoBorrower() as $k => $v) {
					if($v) {
						$borrowerElement = $this->getDomDocument()->createAttribute($k);
						$borrowerElement->value = $v;
						$borrower->appendChild($borrowerElement);
					}
				}
				// Add to the element
				$parties->appendChild($borrower);
			}

			// Add contact details
			if($this->getContactDetail()) {
				$contactDetails = $this->getDomDocument()->createElement('CONTACT_DETAIL');
				foreach($this->getContactDetail() as $k => $v) {
					if($v) {
						$contactDetail = $this->getDomDocument()->createAttribute($k);
						$contactDetail->value = $v;
						$contactDetails->appendChild($contactDetail);
					}
				}
				// Add to the element
				$parties->appendChild($contactDetails);
			}

			// Add amc processor
			if($this->getAMCProcessor()) {
				$amcProcessor = $this->getDomDocument()->createElement('AMC_PROCESSOR');
				foreach($this->getAMCProcessor() as $k => $v) {
					if($v) {
						$amcProcessorInfo = $this->getDomDocument()->createAttribute($k);
						$amcProcessorInfo->value = $v;
						$amcProcessor->appendChild($amcProcessorInfo);
					}
				}
				// Add to the element
				$parties->appendChild($amcProcessor);
			}

			// Add parties to valuation response
			$valuationResponse->appendChild($parties);

			// Add property info
			if($this->getProperty() || $this->getPropertySalesHistory()) {
				// Create property element
				$property = $this->getDomDocument()->createElement('PROPERTY');
				// Add property info
				if($this->getProperty()) {
					foreach($this->getProperty() as $k => $v) {
						if($v) {
							$propertyElement = $this->getDomDocument()->createAttribute($k);
							$propertyElement->value = $v;
							$property->appendChild($propertyElement);
						}
					}
				}

				// Sales history
				if($this->getPropertySalesHistory()) {
					$salesHistory = $this->getDomDocument()->createElement('SALES_HISTORY');
					foreach($this->getPropertySalesHistory() as $k => $v) {
						if($v) {
							$salesHistoryElement = $this->getDomDocument()->createAttribute($k);
							$salesHistoryElement->value = $v;
							$salesHistory->appendChild($salesHistoryElement);
						}
					}

					// Add sales to property
					$property->appendChild($salesHistory);
				}

				// Add property to valuation
				$valuationResponse->appendChild($property);
			}

			// Add loan purpose
			if($this->getLoanPurpose()) {
				// Create loan purpose
				$loanPurpose = $this->getDomDocument()->createElement('LOAN_PURPOSE');
				foreach($this->getLoanPurpose() as $k => $v) {
					if($v) {
						$loanPurposeAttribute = $this->getDomDocument()->createAttribute($k);
						$loanPurposeAttribute->value = $v;
						$loanPurpose->appendChild($loanPurposeAttribute);
					}
				}

				// Add property to valuation
				$valuationResponse->appendChild($loanPurpose);
			}

			// Add notes
			if($this->getNotes()) {
				$notes = $this->getDomDocument()->createElement('NOTES');
				foreach($this->getNotes() as $elem) {
					foreach($elem as $k => $v) {
						$noteElement = $this->getDomDocument()->createElement('NOTE');
						$noteAttribute = $this->getDomDocument()->createAttribute($k);
						$noteAttribute->value = $v;
						$noteElement->appendChild($noteAttribute);
						$notes->appendChild($noteElement);
					}
				}

				// Add notes to valuation response
				$valuationResponse->appendChild($notes);
			}

			// Add files
			if($this->getFiles()) {
				foreach($this->getFiles() as $elem) {
					$files = $this->getDomDocument()->createElement('EMBEDDED_FILE');
					$documentElement = $this->getDomDocument()->createElement('DOCUMENT');
					$contents = null;
					foreach($elem as $k => $v) {
						if($k == 'contents') {
							$contents = $v;
							continue;
						}	
						$documentAttribute = $this->getDomDocument()->createAttribute($k);
						$documentAttribute->value = $v;
						$files->appendChild($documentAttribute);
					}

					// Add contents
					$documentContents = $this->getDomDocument()->createCDATASection($contents);
					$documentElement->appendChild($documentContents);
					$files->appendChild($documentElement);

					// Add notes to valuation response
					$valuationResponse->appendChild($files);
				}
			}

			// Add valuation response to Response data
			$responseData->appendChild($valuationResponse);

			// Add response data to response
			$response->appendChild($responseData);

			// Add to the document itself
			$this->getDomDocument()->appendChild($responseGroup);

			$this->getDomDocument()->preserveWhiteSpace = false;
			$this->getDomDocument()->formatOutput = true;

			$body = $this->getDocumentXML();
			$this->setRequestBody($body);
		}

		$this->sendRequest(self::METHOD_SET_STATUS_UPDATE, 'post');

		return $this;
	}


	/** Set Helpers **/
	/**
	 * Set borrower information
	 * @return obj
	 */
	public function setBorrower($firstName=null, $lastName=null, $phone=null, $email=null) {
		$this->setDomDocumentOptions('borrower', [
			'_SequenceIdentifier' => 1,
			'_FirstName' => $firstName,
			'_LastName' => $lastName,
			'_HomeTelephoneNumber' => $phone,
			'_Email' => $email
		]);

		return $this;
	}

	/**
	 * Set co borrower information
	 * @return obj
	 */
	public function setCoBorrower($firstName=null, $lastName=null, $phone=null, $email=null) {
		$this->setDomDocumentOptions('coborrower', [
			'_SequenceIdentifier' => 2,
			'_FirstName' => $firstName,
			'_LastName' => $lastName,
			'_HomeTelephoneNumber' => $phone,
			'_Email' => $email
		]);

		return $this;
	}

	/**
	 * get borrower information
	 * @return array
	 */
	public function getBorrower() {
		return $this->hasDomDocumentOption('borrower') ? $this->getDomDocumentOption('borrower') : null;
	}

	/**
	 * get co borrower information
	 * @return array
	 */
	public function getCoBorrower() {
		return $this->hasDomDocumentOption('coborrower') ? $this->getDomDocumentOption('coborrower') : null;
	}

	/**
	 * Set property information
	 * @return obj
	 */
	public function setProperty($streetAddres=null, $streetAddres2=null, $city=null, $state=null, $zipcode=null, $county=null, $titleCategory=null, $titleCategoryOtherDescription=null) {
		$this->setDomDocumentOptions('property', [
			'_StreetAddress' => $streetAddres,
			'_StreetAddress2' => $streetAddres2,
			'_City' => $city,
			'_State' => $state,
			'_PostalCode' => $zipcode,
			'_County' => $county,
			'_TitleCategoryType' => $titleCategory,
			'_TitleCategoryTypeOtherDescription' => $titleCategoryOtherDescription
		]);
		return $this;
	}

	/**
	 * get property information
	 * @return array
	 */
	public function getProperty() {
		return $this->hasDomDocumentOption('property') ? $this->getDomDocumentOption('property') : null;
	}

	/**
	 * Set property sales information
	 * @return obj
	 */
	public function setPropertySalesHistory($amount) {
		$this->setDomDocumentOptions('saleshistory', [
			'PropertySalesAmount' => $amount
		]);
		return $this;
	}

	/**
	 * get property sales information
	 * @return array
	 */
	public function getPropertySalesHistory() {
		return $this->hasDomDocumentOption('saleshistory') ? $this->getDomDocumentOption('saleshistory') : null;
	}

	/**
	 * add note
	 * @return obj
	 */
	public function addNote($note) {
		$notes = $this->getNotes();
		$notes[] = ['Value' => $note];
		$this->setDomDocumentOptions('notes', $notes);
		return $this;
	}

	/**
	 * get notes
	 * @return array
	 */
	public function getNotes() {
		return $this->hasDomDocumentOption('notes') ? $this->getDomDocumentOption('notes') : [];
	}

	/**
	 * add document from string
	 * @return obj
	 */
	public function addDocumentFromString($name, $type, $contents, $mime='application/pdf') {
		$this->addDocument($name, $type, $contents, $mime);
		return $this;
	}

	/**
	 * add document from file
	 * @return obj
	 */
	public function addDocumentFromFile($name, $type, $file, $mime='application/pdf') {
		$contents = null;

		// Load file
		if(!file_exists($file)) {
			throw new Exception(404, 'Sorry, That file was not found.');
		}

		$contents = file_get_contents($file);

		$this->addDocument($name, $type, $contents, $mime);
		return $this;
	}

	/**
	 * add document
	 * @return obj
	 */
	protected function addDocument($name, $type, $contents, $mime='application/pdf', $encoding='Base64') {
		$files = $this->getFiles();
		$files[] = [
			'_Name' => $name,
			'_Type' => $type,
			'MIMEType' => $mime,
			'_EncodingType' => $encoding,
			'contents' => base64_encode($contents),
		];
		$this->setDomDocumentOptions('files', $files);
		return $this;
	}

	/**
	 * get files
	 * @return array
	 */
	public function getFiles() {
		return $this->hasDomDocumentOption('files') ? $this->getDomDocumentOption('files') : [];
	}

	/**
	 * Set contact details
	 * @return obj
	 */
	public function setContactDetail($firstName, $lastName, $email, $name=null, $phone=null) {
		$this->setDomDocumentOptions('contactdetail', [
			'_Name' => $name,
			'_FirstName' => $firstName,
			'_LastName' => $lastName,
			'_Email' => $email,
			'_WorkTelephoneNumber' => $phone,
		]);
		return $this;
	}

	/**
	 * get contact details
	 * @return array
	 */
	public function getContactDetail() {
		return $this->hasDomDocumentOption('contactdetail') ? $this->getDomDocumentOption('contactdetail') : null;
	}

	/**
	 * set amc processor
	 * @return obj
	 */
	public function setAMCProcessor($firstName, $lastName, $email, $phone=null) {
		$this->setDomDocumentOptions('amcprocessor', [
			'_FirstName' => $firstName,
			'_LastName' => $lastName,
			'_Email' => $email,
			'_WorkTelephoneNumber' => $phone,
		]);
		return $this;
	}

	/**
	 * get amc processor
	 * @return array
	 */
	public function getAMCProcessor() {
		return $this->hasDomDocumentOption('amcprocessor') ? $this->getDomDocumentOption('amcprocessor') : null;
	}

	/**
	 * Set loan purpose
	 * @return obj
	 */
	public function setLoanPurpose($type) {
		$this->setDomDocumentOptions('loanpurpose', [
			'_Type' => $type
		]);
		return $this;
	}

	/**
	 * get loan purpose
	 * @return array
	 */
	public function getLoanPurpose() {
		return $this->hasDomDocumentOption('loanpurpose') ? $this->getDomDocumentOption('loanpurpose') : null;
	}

	/**
	 * set appraisal id
	 * @param int $id
	 * @return obj
	 */
	public function setAppraisalId($id) {
		$this->addStatusUpdate('AppraisalID', $id);
		return $this;
	}
	/**
	 * set status code
	 * @param int $code
	 * @return obj
	 */
	public function setStatusCode($code) {
		$this->addStatusUpdate('StatusCode', $code);
		return $this;
	}
	/**
	 * set hold code
	 * @param int $code
	 * @return obj
	 */
	public function setHoldCode($code) {
		$this->addStatusUpdate('HoldCode', $code);
		return $this;
	}
	/**
	 * set hold reason
	 * @param string $reason
	 * @return obj
	 */
	public function setHoldReason($reason) {
		$this->addStatusUpdate('HoldReason', $reason);
		return $this;
	}
	/**
	 * set appointment date time
	 * @param string $date
	 * @return obj
	 */
	public function setAppointmentDateTime($date) {
		$this->addStatusUpdate('AppointmentDateTime', $date);
		return $this;
	}
	/**
	 * set complexity flag
	 * @param string $flag
	 * @return obj
	 */
	public function setComplexityFlag($flag) {
		$this->addStatusUpdate('ComplexityFlag', $flag);
		return $this;
	}
	/**
	 * set report delivery date
	 * @param string $date
	 * @return obj
	 */
	public function setReportDeliveryDate($date) {
		$this->addStatusUpdate('ReportDeliveryDate', $date);
		return $this;
	}
	/**
	 * set rural flag
	 * @param string $flag
	 * @return obj
	 */
	public function setRuralFlag($flag) {
		$this->addStatusUpdate('RuralFlag', $flag);
		return $this;
	}
	/**
	 * set target due date
	 * @param string $date
	 * @return obj
	 */
	public function setTargetDueDate($date) {
		$this->addStatusUpdate('TargetDueDate', $date);
		return $this;
	}
	/**
	 * set borrower other phone
	 * @param string $phone
	 * @return obj
	 */
	public function setBorrowerOtherPhone($phone) {
		$this->addStatusUpdate('BorrowerOtherPhone', $phone);
		return $this;
	}
	/**
	 * set co borrower other phone
	 * @param string $phone
	 * @return obj
	 */
	public function setCoBorrowerOtherPhone($phone) {
		$this->addStatusUpdate('CoBorrowerOtherPhone', $phone);
		return $this;
	}
	/**
	 * set ucdp file id
	 * @param int $id
	 * @return obj
	 */
	public function setUCDPDocFileId($id) {
		$this->addStatusUpdate('UCDPDocFileId', $id);
		return $this;
	}

	/** Set Helpers **/


	/** Various types getter **/
	/**
	 * Return list of methods
	 * @return array
	 */
	public function getMethods() {
		return self::$methods;
	}
	/**
	 * Return list of action types
	 * @return array
	 */
	public function getActionTypes() {
		return self::$actionTypes;
	}
	/**
	 * Return list of contact 
	 * @return array
	 */
	public function getContactTypes() {
		return self::$contactTypes;
	}
	/**
	 * Return list of contact point 
	 * @return array
	 */
	public function getContactPointTypes() {
		return self::$contactPointTypes;
	}
	/**
	 * Return list of contact point location 
	 * @return array
	 */
	public function getContactPointsLocationTypes() {
		return self::$contactPointsLocationTypes;
	}
	/**
	 * Return list of embedded file types
	 * @return array
	 */
	public function getEmbeddedFileTypes() {
		return self::$embeddedFileTypes;
	}
	/**
	 * Return list of hold reason
	 * @return array
	 */
	public function getHoldReasonTypes() {
		return self::$holdReasonTypes;
	}
	/**
	 * Return list of loan purpose
	 * @return array
	 */
	public function getLoanPurposeTypes() {
		return self::$loanPurposeTypes;
	}
	/**
	 * Return list of loan types
	 * @return array
	 */
	public function getLoanTypes() {
		return self::$loanTypes;
	}
	/**
	 * Return list of payment types
	 * @return array
	 */
	public function getPaymentTypes() {
		return self::$paymentTypes;
	}
	/**
	 * Return list of role types
	 * @return array
	 */
	public function getRoleTypes() {
		return self::$roleTypes;
	}
	/**
	 * Return list of status code
	 * @return array
	 */
	public function getStatusCodes() {
		return self::$statusCodes;
	}
	
	/**
	 * Return list of title categories
	 * @return array
	 */public function getTitleCategories() {
		return self::$titleCategories;
	}

	/** Various types getter **/

	/** Get Requests End **/

	/** Guzzle Start **/

	/**
	 * Send the actual request to the api endpoint and process the response 
	 * @param string $method the method we use currently
	 * @param string $type get|post request
	 * @return void
	 */
	protected function sendRequest($method, $type='get') {
		// Start new client
		self::$client = new Client();

		// Create request
		if($type == 'post') {
			$request = self::$client->post($this->getMethodURI($method), $this->getRequestHeaders(), $this->getRequestBody(), $this->getRequestOptions());
		} else {
			$request = self::$client->get($this->getMethodURI($method), $this->getRequestHeaders(), $this->getRequestOptions());
		}

		// Add Query Params
		$this->addQueryParams();

		// Set request
		$this->setRequest($request);

		// Send Request
		$response = $request->send();

		// Set response
		$this->setResponse($response);
	}

	/**
	 * Get response as simple xml object
	 * @return object/error string
	 */
	public function getXML() {
		try {
			return $this->getResponse()->xml();
		} catch(Exception $e) {
			return $e->getMessage();
		}
	}

	/**
	 * Get response as json encoded string
	 * @return object/error string
	 */
	public function getJSON() {
		try {
			return $this->getResponse()->json();
		} catch(Exception $e) {
			return $e->getMessage();
		}
	}

	/**
	 * Set request object
	 * @param object $request
	 * @return obj
	 */
	protected function setRequest($request) {
		self::$client = $request;
		return $this;
	}

	/**
	 * Get request object
	 * @return obj
	 */
	public function getRequest() {
		return self::$client;
	}

	/**
	 * Set response object
	 * @param object $response
	 * @return obj
	 */
	protected function setResponse($response) {
		self::$response = $response;
		return $this;
	}

	/**
	 * Get response object
	 * @return obj
	 */
	public function getResponse() {
		return self::$response;
	}

	/**
	 * Get raw response returned from request made
	 * @return string
	 */
	public function getRawResponse() {
		return (string) $this->getResponse()->getBody();
	}

	/**
	 * return headers array from request
	 * @return array
	 */
	public function getHeaders() {
		return $this->getResponse()->getHeaders();
	}

	/**
	 * return one header value based on name
	 * @param string $name
	 * @return mixed
	 */
	public function getHeader($name) {
		return $this->getResponse()->getHeader($name);
	}

	/** Guzzle End **/


	/** Debug Start **/
	/**
	 * Show debug information
	 * Prints the following to the screen:
	 * - endpoint
	 * - raw body
	 * - request headers
	 * - request options
	 * - response
	 * - xml response
	 * - response headers
	 * @return void
	 */
	public function debug() {

		echo "<pre>\n";
		echo "/***************** DEBUG START ************************/\n\n";
		echo "Is Successful: " . ($this->isSuccessful() ? 'True' : 'False') . "\n";
		echo "Is Error: " . ($this->isError() ? 'True' : 'False') . "\n";
		echo "Error Code: " . $this->getErrorCode() . "\n";
		echo "Error Message: " . $this->getErrorMessage() . "\n";
		echo "\n----------------------------\n\n";
		echo "Raw Endpoint\n\n";
		echo print_r(self::$fulluri, true) . "\n\n";
		echo "----------------------------\n\n";
		echo "Raw Body\n\n";
		echo print_r(htmlentities($this->getRequestBody()), true) . "\n\n";
		echo "----------------------------\n\n";
		echo "Raw Request Headers\n\n";
		echo print_r($this->getRequestHeaders(), true) . "\n\n";
		echo "----------------------------\n\n";
		echo "Raw Request Options\n\n";
		echo print_r($this->getRequestOptions(), true) . "\n\n";
		echo "----------------------------\n\n";
		echo "Raw Response\n\n";
		echo print_r(htmlentities($this->getRawResponse()), true) . "\n\n";
		echo "----------------------------\n\n";
		echo "XML Response\n\n";
		echo print_r($this->getXML(), true) . "\n\n";
		echo "----------------------------\n\n";
		echo "Raw Headers\n\n";
		echo print_r($this->getHeaders(), true) . "\n\n";
		echo "----------------------------\n\n";
		echo "/***************** DEBUG END ************************/\n\n";
		echo "\n</pre>";
	}
	/** Debug End **/

	/** Configuration Start **/

	/**
	 * Check if request was successful
	 * Checks first if the guzzle request was successful
	 * then the error code returned in the xml response from connexsions
	 * if none returned it was successful otherwise error
	 * @return bool
	 */
	public function isSuccessful() {
		// Check if Guzzle errored out
		if(!$this->getResponse()->isSuccessful()) {
			// Set errors
			$this->setErrorCode($this->getResponse()->getStatusCode())->setErrorMessage($this->getResponse()->getReasonPhrase());
			return false;
		} elseif(($error = $this->getResponseXMLErrorData())) {
			$this->setErrorCode($error['code'])->setErrorMessage($error['message']);
			return false;
		}

		// All good
		return true;
	}

	/**
	 * Get the error code and error message from the response xml
	 * @return array
	 */
	protected function getResponseXMLErrorData() {
		$xml = $this->getXML();
		$error = ['code' => 0, 'message' => null];

		if(!isset($xml->Status) && !isset($xml->Messages)) {
			return false;
		}

		// If the status is 1 it means success so return false as well
		if(isset($xml->Status) && $xml->Status == 1) {
			return false;
		}

		$error['code'] = (string) $xml->Status;
		$error['message'] = (string) $xml->Messages->Message;

		return $error;
	}

	/**
	 * Check if the request had an error 
	 * @see isSuccessful
	 * @return bool
	 */
	public function isError() {
		return !$this->isSuccessful();
	}

	/**
	 * Set the error code returned from the xml
	 * @param int $code
	 * @return object
	 */
	protected function setErrorCode($code) {
		self::$requestErrorCode = $code;
		return $this;
	}

	/**
	 * Set error message returned from the xml
	 * @param string $message
	 * @return object
	 */
	protected function setErrorMessage($message) {
		self::$requestErrorMessage = $message;
		return $this;
	}

	/**
	 * return the error code set from the xml
	 * @return int
	 */
	public function getErrorCode() {
		return self::$requestErrorCode;
	}

	/**
	 * return the error message set from the xml
	 * @return string
	 */
	public function getErrorMessage() {
		return self::$requestErrorMessage;
	}

	/**
	 * create the full endpoint url used to connect to the api
	 * @param string $method
	 * @return string
	 */
	protected function getMethodURI($method) {
		// Set method
		$this->setMethod($method);

		// Build URI
		$endpoint = $this->getEndPointURI();

		// Build Link
		return self::$fulluri = sprintf('%s?login=%s&token=%s&method=%s%s', $endpoint, $this->getLogin(), $this->getToken(), $this->getMethod(), $this->getEndPointQueryParams());
	}

	/**
	 * return the query params set in the get uri
	 * @return string
	 */
	protected function getEndPointQueryParams() {
		$addition = '';
		$options = [];
		if($this->getQueryParams()) {
			foreach($this->getQueryParams() as $k => $v) {
				$options[] = ($k.'='.urlencode($v));
			}

			$addition = '&' . implode('&', $options);
		}

		return $addition;
	}

	/**
	 * build the endpoint uri, replace lender sub domain if it's different
	 * @return string
	 */
	public function getEndPointURI() {
		$endpoint = $this->getEndPoint();
		// Replace endpoint with correct lender
		$endpoint = str_replace('{lender}', $this->getLender(), $endpoint);

		return $endpoint;
	}

	/**
	 * set api login
	 * @param string $login
	 * @return object
	 */
	public function setLogin($login) {
		self::$login = $login;
		return $this;
	}

	/**
	 * get api login
	 * @return string
	 */
	public function getLogin() {
		return self::$login;
	}

	/**
	 * set api token
	 * @param string $token
	 * @return object
	 */
	public function setToken($token) {
		self::$token = $token;
		return $this;
	}

	/**
	 * get api token
	 * @return string
	 */
	public function getToken() {
		return self::$token;
	}

	/**
	 * set api lender used
	 * @param string $lender
	 * @return object
	 */
	public function setLender($lender) {
		self::$lender = $lender;
		return $this;
	}

	/**
	 * return api lender used
	 * @return string
	 */
	public function getLender() {
		return self::$lender;
	}

	/**
	 * set api endpoint
	 * @param string $endpoint
	 * @return object
	 */
	public function setEndPoint($endpoint) {
		self::$endpoint = $endpoint;
		return $this;
	}

	/**
	 * return api endpoint
	 * @return string
	 */
	public function getEndPoint() {
		return self::$endpoint;
	}

	/**
	 * set api method
	 * @param string $method
	 * @return object
	 */
	public function setMethod($method) {
		self::$method = $method;
		return $this;
	}

	/**
	 * get api method
	 * @return string
	 */
	public function getMethod() {
		return self::$method;
	}

	/**
	 * add request header
	 * @param string $key
	 * @param string $value
	 * @return object
	 */
	public function addHeader($key, $value) {
		self::$requestHeaders[$key] = $value;
		return $this;
	}

	/**
	 * add elements to the request headers array
	 * @param array $array
	 * @return object
	 */
	public function addHeaders(array $array) {
		foreach($array as $k => $v) { 
			$this->addHeader($k, $v);
		}
		return $this;
	}

	/**
	 * return request headers
	 * @return array
	 */
	protected function getRequestHeaders() {
		return self::$requestHeaders;
	}

	/**
	 * add the query params as query elements under the request
	 * options array
	 * @return void
	 */
	protected function addQueryParams() {
		foreach($this->getQueryParams() as $key => $value) {
			self::$requestOptions['query'][$key] = $value;
		}
	}

	/**
	 * return the query params added to the array
	 * @return array
	 */
	protected function getQueryParams() {
		return self::$requestQuery;
	}

	/**
	 * add request query
	 * @param string $key
	 * @param string $value
	 * @return object
	 */
	public function addQuery($key, $value) {
		self::$requestQuery[$key] = $value;
		return $this;
	}

	/**
	 * add a request option to the array
	 * @param string $key
	 * @param string $value
	 * @return object
	 */
	public function addOption($key, $value) {
		self::$requestOptions[$key] = $value;
		return $this;
	}

	/**
	 * add a request option to the array
	 * @param array $array (key=>value)
	 * @return object
	 */
	public function addOptions(array $array) {
		foreach($array as $k => $v) { 
			$this->addOption($k, $v);
		}
		return $this;
	}

	/**
	 * return request options
	 * @return array
	 */
	protected function getRequestOptions() {
		return self::$requestOptions;
	}

	/**
	 * add a status update key=>value
	 * @param string $key
	 * @param string $value
	 * @return object
	 */
	public function addStatusUpdate($key, $value) {
		self::$statusUpdateOptions[$key] = $value;
		return $this;
	}

	/**
	 * add a status update key=>value
	 * @param array $array (key=>value)
	 * @return object
	 */
	public function addStatusUpdates(array $array) {
		foreach($array as $k => $v) { 
			$this->addStatusUpdate($k, $v);
		}
		return $this;
	}

	/**
	 * return request options
	 * @return array
	 */
	protected function getStatusUpdateValues() {
		return self::$statusUpdateOptions;
	}

	/**
	 * set the request body
	 * @param string $body
	 * @return object
	 */
	public function setRequestBody($body) {
		self::$requestBody = $body;
		return $this;
	}

	/**
	 * return request body
	 * @return string
	 */
	protected function getRequestBody() {
		return self::$requestBody;
	}

	/** Configuration End **/

	/** Dom Document **/
	/**
	 * create dom document
	 * @return void
	 */
	protected function createDomDocument() {
		self::$domDocument = new DOMDocument('1.0', 'UTF-8');
	}

	/**
	 * return dom document
	 * @return obj
	 */
	protected function getDomDocument() {
		return self::$domDocument;
	}

	/**
	 * return dom xml
	 * @return string
	 */
	protected function getDocumentXML() {
		return $this->getDomDocument()->saveXML();
	}

	/**
	 * return dom document options
	 * @return array
	 */
	protected function getDomDocumentOptions() {
		return self::$domDocumentOptions;
	}

	/**
	 * return dom docment option
	 * @param string $key
	 * @return mixed
	 */
	protected function getDomDocumentOption($key) {
		return self::$domDocumentOptions[$key];
	}

	/**
	 * check to see if there is a key in the dom document options
	 * @param string $key
	 * @return bool
	 */
	protected function hasDomDocumentOption($key) {
		return isset(self::$domDocumentOptions[$key]) ? true : false;
	}

	/**
	 * set an option in the dom document
	 * @param string $key
	 * @param mixed $value
	 * @return string
	 */
	protected function setDomDocumentOptions($key, $value) {
		self::$domDocumentOptions[$key] = $value;
	}

	/** Dom Document **/
}