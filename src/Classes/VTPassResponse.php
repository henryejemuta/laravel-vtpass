<?php
/**
 * Created By: Henry Ejemuta
 * Project: laravel-vtpass
 * Class Name: VTPassResponse.php
 * Date Created: 7/14/20
 * Time Created: 3:12 PM
 */

namespace HenryEjemuta\LaravelVTPass\Classes;

use HenryEjemuta\LaravelVTPass\Exceptions\VTPassErrorException;

/**
 * The VTPassResponse Class parse all response from the VTPass RESTful API and present a standard and convenient way of
 * accessing response status as well as related error which are throwable through the VTPassErrorException class.
 * The response messages are determined from the status code as documented https://www.vtpass.com/documentation/response-codes/
 *
 * Your query response body is accessible via the $vtPassCall->getBody(); where $vtPassCall is the instance of the VTPassResponse
 * response class return from any of the API call
 *
 * @package HenryEjemuta\LaravelVTPass\Classes
 */
class VTPassResponse
{
    private const RESPONSE = [
        "000" => ['error' => false, 'title' => 'TRANSACTION PROCESSED', 'message' => 'Transaction is processed. Please check [content][transactions][status] for the status of the transaction. It would contain the actual state like initiated, pending, delivered, reversed, resolved. See the next table for more information.'],
        "099" => ['error' => false, 'title' => 'TRANSACTION IS PROCESSING', 'message' => 'Transaction is currently precessing. In such situation, you should requery using your requestID to ascertain the current status of the transaction.'],
        "016" => ['error' => true, 'title' => 'TRANSACTION FAILED', 'message' => 'TRANSACTION FAILED'],
        "001" => ['error' => false, 'title' => 'TRANSACTION QUERY', 'message' => 'The current status of a given transaction carried out on the platform'],
        "010" => ['error' => true, 'title' => 'VARIATION CODE DOES NOT EXIST', 'message' => 'You are using an invalid variation code. Please check the list of available variation codes here.'],
        "011" => ['error' => true, 'title' => 'INVALID ARGUMENTS', 'message' => 'You are not passing at least one of the arguments expected in your request.'],
        "012" => ['error' => true, 'title' => 'PRODUCT DOES NOT EXIST', 'message' => 'PRODUCT DOES NOT EXIST'],
        "013" => ['error' => true, 'title' => 'BELOW MINIMUM AMOUNT ALLOWED', 'message' => 'You are attempting to pay an amount lower than the minimum allowed for that product/service.'],
        "014" => ['error' => true, 'title' => 'REQUEST ID ALREADY EXIST', 'message' => 'You have used the RequestID for a previous transaction.'],
        "015" => ['error' => true, 'title' => 'INVALID REQUEST ID', 'message' => 'This is returned for a requery operation. This RequestID was not used on our platform.'],
        "017" => ['error' => true, 'title' => 'ABOVE MAXIMUM AMOUNT ALLOWED', 'message' => 'You are attempting to pay an amount higher than the maximum allowed for that product/service.'],
        "018" => ['error' => true, 'title' => 'LOW WALLET BALANCE', 'message' => 'You do not have adequate funds in your wallet to cover the cost of the transaction.'],
        "019" => ['error' => true, 'title' => 'LIKELY DUPLICATE TRANSACTION', 'message' => 'You attempted to buy the same service multiple times for the same biller_code within 30 seconds.'],
        "020" => ['error' => false, 'title' => 'BILLER CONFIRMED', 'message' => 'BILLER CONFIRMED'],
        "021" => ['error' => true, 'title' => 'ACCOUNT LOCKED', 'message' => 'Your account is locked'],
        "022" => ['error' => true, 'title' => 'ACCOUNT SUSPENDED', 'message' => 'Your account is suspended'],
        "023" => ['error' => true, 'title' => 'API ACCESS NOT ENABLE FOR USER', 'message' => 'Your account does not have API access enabled. Please contact us to request for activation'],
        "024" => ['error' => true, 'title' => 'ACCOUNT INACTIVE', 'message' => 'Your account is inactive.'],
        "025" => ['error' => true, 'title' => 'RECIPIENT BANK INVALID', 'message' => 'Your bank code for bank transfer is invalid.'],
        "026" => ['error' => true, 'title' => 'RECIPIENT ACCOUNT COULD NOT BE VERIFIED', 'message' => 'Your bank account number could not be verified.'],
        "027" => ['error' => true, 'title' => 'IP NOT WHITELISTED, CONTACT SUPPORT', 'message' => 'You need to contact support with your server IP for whitelisting'],
        "030" => ['error' => true, 'title' => 'BILLER NOT REACHABLE AT THIS POINT', 'message' => 'The biller for the product or service is unreachable.'],
        "031" => ['error' => true, 'title' => 'BELOW MINIMUM QUANTITY ALLOWED', 'message' => 'You are under-requesting for a service that has a limit on the quantity to be purchased per time.'],
        "032" => ['error' => true, 'title' => 'ABOVE MINIMUM QUANTITY ALLOWED', 'message' => 'You are over-requesting for a service that has a limit on the quantity to be purchased per time.'],
        "034" => ['error' => true, 'title' => 'SERVICE SUSPENDED', 'message' => 'The service being requested for has been suspended for the time being.'],
        "035" => ['error' => true, 'title' => 'SERVICE INACTIVE', 'message' => 'You are requesting for a service that has been turned off at the moment.'],
        "040" => ['error' => true, 'title' => 'TRANSACTION REVERSAL', 'message' => 'Transaction reversal to wallet'],
        "083" => ['error' => true, 'title' => 'SYSTEM ERROR', 'message' => 'Oops!!! System error. Please contact tech support'],
    ];


    private const TRANSACTION_PROCESSED_STATUS = [
        "initiated" => ['meaning' => 'Transaction has been initiated', 'note' => 'Transaction is initiated.'],
        "pending" => ['meaning' => 'Transaction is pending', 'note' => 'Transaction is pending. This may happen when service provider has not concluded the transaction. This status will be updated. Please requery to get a final status.'],
        "delivered" => ['meaning' => 'Transaction Successful', 'note' => 'Transaction is successful and service is confirmed as delivered.'],
        "reversed" => ['meaning' => 'Transaction Reversed', 'note' => 'Payment reversed to your wallet.'],
        "resolved" => ['meaning' => 'Transaction has been Resolved', 'note' => 'Please contact us for more information.']
    ];
    /**
     * @var bool
     */
    private $hasError;

    /**
     * @var string $title
     */
    private $title;

    /**
     * Response Message as determined by status code
     * @var string $message
     */
    private $message;

    /**
     * Response Body from
     * @var object|null $body
     */
    private $body;

    /**
     * @var array $additionalStatusDetails
     */
    private $additionalStatus;

    /**
     * VTPassResponse constructor.
     * @param string $code
     * @param object|array|null $responseBody
     * @throws VTPassErrorException
     */
    public function __construct(string $code, $responseBody = null)
    {
        $this->body = $responseBody;
        $this->additionalStatus = [];
        $this->title = "Failed Connection";
        $this->message = "Unable to communicate with VTPass server";
        $this->hasError = true;

        if (isset(VTPassResponse::RESPONSE["$code"])) {
            $msg = VTPassResponse::RESPONSE["$code"];
            $this->title = $msg['title'];
            $this->message = $msg['message'];
            $this->hasError = $msg['error'];
            if ("$code" === "000" && isset($responseBody->status)) {
                if (isset(VTPassResponse::TRANSACTION_PROCESSED_STATUS["{$responseBody->status}"])) {
                    $this->additionalStatus = VTPassResponse::TRANSACTION_PROCESSED_STATUS["{$responseBody->status}"];
                }
            }
        }

        if ($this->hasError)
            throw new VTPassErrorException($this->message, "$code");

    }

    /**
     * Determine if this ise a success response object
     * @return bool
     */
    public function successful(): bool
    {
        return !($this->hasError);
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return object|null
     */
    public function getBody()
    {
        return is_array($this->body) ? ((object)$this->body) : $this->body;
    }

    /**
     * @return object
     */
    public function getAdditionalStatus(): object
    {
        return (object)($this->additionalStatus);
    }


}
