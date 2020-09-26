<?php
/**
 * Created By: Henry Ejemuta
 * Project: laravel-vtpass
 * Class Name: VTPass.php
 * Date Created: 7/13/20
 * Time Created: 7:55 PM
 */

namespace HenryEjemuta\LaravelVTPass;

use HenryEjemuta\LaravelVTPass\Classes\VTPassResponse;
use HenryEjemuta\LaravelVTPass\Exceptions\VTPassErrorException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

/**
 * Class VTPass
 * @package HenryEjemuta\LaravelVTPass
 *
 * This is a Laravel wrapper around the VTPass API hence all failed request will throw VTPassResponse with the appropriate message from the VTPass API as well as error code
 *
 * How To Buy Airtime
 * =====================
 * - Get service categories. use VTPass::getSeviceCatrgories();
 * - Get Service ID.  use VTPass::getSeviceCatrgories()
 * Purchase products.
 * a. MTN airtime
 * b. GLO airtime
 * c. Airtel airtime
 * d. 9mobile airtime
 *
 *
 * @link https://www.vtpass.com/documentation/response-codes/ for any error details as well as status code to gracefully handle them within your application
 *
 */
class VTPass
{
    /**
     * @var string
     */
    private $productPurchaseEndpoint;

    /**
     * base url
     *
     * @var
     */
    private $baseUrl;

    /**
     * the cart session key
     *
     * @var
     */
    protected $instanceName;

    /**
     * Flexible handle to the VTPass Configuration
     *
     * @var
     */
    protected $config;


    public function __construct($baseUrl, $instanceName, $config)
    {
        $this->baseUrl = $baseUrl;
        $this->instanceName = $instanceName;
        $this->config = $config;
        $this->productPurchaseEndpoint = "{$this->baseUrl}/pay";
    }


    /**
     * get instance name of the cart
     *
     * @return string
     */
    public function getInstanceName()
    {
        return $this->instanceName;
    }

    /**
     * @return PendingRequest
     */
    private function withBasicAuth()
    {
        return Http::withBasicAuth($this->config['username'], $this->config['password'])->asJson();
    }


    /**
     * This help in getting the available service IDs on VTpass RESTful API.
     * Using a GET method, the VTpass service IDs for Data [for instance] can be accessed with the endpoint below:
     * @param $serviceCategoryIdentifier
     * @return VTPassResponse
     *
     * @throws VTPassErrorException
     */
    public function getServiceID($serviceCategoryIdentifier): VTPassResponse
    {
        $endpoint = "{$this->baseUrl}/services?identifier=$serviceCategoryIdentifier";

        $response = $this->withBasicAuth()->get($endpoint);

        $responseObject = json_decode($response->body());
        if (isset($responseObject->response_description) && isset($responseObject->content))
            return new VTPassResponse($responseObject->response_description, $responseObject->content);
        return new VTPassResponse(-1, null);
    }

    /**
     * This help in getting the available service code from the biller using their respective service ID on VTpass RESTful API.
     * @param string $serviceID Service ID as specified by VTpass. For example mtn-data as serviceID to get all MTN Data Plans
     * @return VTPassResponse
     *
     * @throws VTPassErrorException
     */
    public function getVariationCodes($serviceID): VTPassResponse
    {
        $endpoint = "{$this->baseUrl}/service-variations?serviceID=$serviceID";

        $response = $this->withBasicAuth()->get($endpoint);

        $responseObject = json_decode($response->body());

        if (isset($responseObject->response_description) && isset($responseObject->content))
            return new VTPassResponse($responseObject->response_description, $responseObject->content);
        return new VTPassResponse(-1, null);
    }


    /**
     * Using a GET method, the VTpass service categories can be accessed with the endpoint below:
     * @return VTPassResponse
     *
     * @throws VTPassErrorException
     */
    public function getServicesCategories()
    {
        $endpoint = "{$this->baseUrl}/service-categories";

        $response = $this->withBasicAuth()->get($endpoint);

        $responseObject = json_decode($response->body());
        if (isset($responseObject->response_description) && isset($responseObject->content))
            return new VTPassResponse($responseObject->response_description, $responseObject->content);
        return new VTPassResponse(-1, null);
    }


    /**
     * This help in getting the available service IDs on VTpass RESTful API.
     * Using a GET method, the VTpass variation codes for GOTV bouquets [for instance] can be accessed with the endpoint below:
     * @param string $serviceID Service ID as specified by VTpass. In this case, it is aero
     * @param string $name This refers to the option name as specified by VTpass. In this case, it is passenger_type. Other option name include trip_type.
     * @return VTPassResponse
     *
     * @throws VTPassErrorException
     */
    public function getProductOptions(string $serviceID, string $name): VTPassResponse
    {
        $endpoint = "{$this->baseUrl}/options?serviceID=$serviceID&name=$name";

        $response = $this->withBasicAuth()->get($endpoint);

        $responseObject = json_decode($response->body());
        if (isset($responseObject->response_description) && isset($responseObject->content))
            return new VTPassResponse($responseObject->response_description, $responseObject->content);
        return new VTPassResponse(-1, null);
    }


    /**
     * Purchase Airtime with the unique service ID (i.e. mtn, glo, airtel, or etisalat to buy airtime corresponding the provided telco service code)
     * @param string $requestId This is a unique reference (otherwise refer to as order number generated from your server to uniquely identify this purchase) with which you can use to identify and query the status of a given transaction after the transaction has been executed.
     * @param string $serviceID Service ID as specified by VTpass. For example mtn, use VTPass::
     * @param int $amount The amount you wish to topup
     * @param string $phoneNumber The phone number of the recipient of this service
     * @return VTPassResponse
     *
     * @throws VTPassErrorException
     */
    public function purchaseAirtime(string $requestId, string $serviceID, int $amount, $phoneNumber): VTPassResponse
    {
        $response = $this->withBasicAuth()->post($this->productPurchaseEndpoint, [
            'request_id' => $requestId,
            'serviceID' => $serviceID,
            'amount' => $amount,
            'phone' => $phoneNumber
        ]);

        $responseObject = json_decode($response->body());
        if (isset($responseObject->code) && isset($responseObject->transactionId)) {
            $statusCode = $responseObject->code;
            unset($responseObject->code);
            return new VTPassResponse($statusCode, $responseObject);
        }
        return new VTPassResponse(-1, null);
    }


    /**
     * Query Transaction Status
     * @param string $requestId This is the reference with which you sent when purchasing a transaction after the transaction has been executed.
     * @return VTPassResponse
     *
     * @throws VTPassErrorException
     */
    public function queryTransactionStatus(string $requestId): VTPassResponse
    {
        $endpoint = "{$this->baseUrl}/requery";

        $response = $this->withBasicAuth()->post($endpoint, [
            'request_id' => $requestId,
        ]);

        $responseObject = json_decode($response->body());
        if (isset($responseObject->code) && isset($responseObject->transactionId)) {
            $statusCode = $responseObject->code;
            unset($responseObject->code);
            return new VTPassResponse($statusCode, $responseObject);
        }
        return new VTPassResponse(-1, null);
    }


    /**
     * @param string $requestId
     * @param string $serviceID
     * @param $billersCode
     * @param $variationCode
     * @param $phoneNumber
     * @param int $amount
     * @return VTPassResponse
     * @throws VTPassErrorException
     */
    public function purchaseProduct(string $requestId, string $serviceID, $billersCode, $variationCode, $phoneNumber, int $amount = 0): VTPassResponse
    {
        $params = [
            'request_id' => $requestId,
            'serviceID' => $serviceID,
            'billersCode' => $billersCode,
            'variation_code' => $variationCode,
            'phone' => $phoneNumber
        ];
        if ($amount !== 0) $params['amount'] = $amount;

        $response = $this->withBasicAuth()->post($this->productPurchaseEndpoint, $params);

        $responseObject = json_decode($response->body());
        if (isset($responseObject->code) && isset($responseObject->transactionId)) {
            $statusCode = $responseObject->code;
            unset($responseObject->code);
            return new VTPassResponse($statusCode, $responseObject);
        }
        return new VTPassResponse(-1, null);
    }


    /**
     * Purchase Data Bundle with the unique service ID (i.e. mtn-data, glo-data, airtel-data, or etisalat-data to buy data corresponding the provided telco service code)
     * <strong>This Exclude Smile Data Bundle and strictly for mtn, glo, airtel, and 9mobile only</strong>
     * @param string $requestId This is a unique reference (otherwise refer to as order number generated from your server to uniquely identify this purchase) with which you can use to identify and query the status of a given transaction after the transaction has been executed.
     * @param string $serviceID Service ID as specified by VTpass. For example mtn, use VTPass::
     * @param string $phoneNumber The Phone Number your with to subscribe
     * @param string $variationCode The variation code of the bundle you wish to subscribe to @see  getVariationCodes($serviceID) to get a list of bundle for that telco
     * @param int $amount The amount as specified with the provided $variationCode. This amount will be ignored as the variation code determine the price of the data bundle, hence this is an optional parameter.
     * @param string $customerPhoneNumber The phone number of the recipient of this service
     * @return VTPassResponse
     *
     * @throws VTPassErrorException
     */
    public function purchaseData(string $requestId, string $serviceID, $phoneNumber, $variationCode, $customerPhoneNumber, int $amount = 0): VTPassResponse
    {
        return $this->purchaseProduct($requestId, $serviceID, $phoneNumber, $variationCode, $customerPhoneNumber, $amount);
    }

    /**
     * This help in getting the available VTpass variation codes (Bundles) for Smile Network bundles
     * @return VTPassResponse
     *
     * @throws VTPassErrorException
     */
    public function getSmileBundles(): VTPassResponse
    {
        return $this->getVariationCodes('smile-direct');
    }

    /**
     * Merchant Verify
     * @param $billersCode
     * @param $serviceID
     * @param string|null $type
     * @return VTPassResponse
     *
     * @throws VTPassErrorException
     */
    public function verifyMerchant($billersCode, $serviceID, $type = null): VTPassResponse
    {
        $endpoint = "{$this->baseUrl}/merchant-verify";
        $params = [
            'billersCode' => $billersCode,
            'serviceID' => $serviceID,
        ];
        if ($type !== null) $params['type'] = $type;
        $response = $this->withBasicAuth()->post($endpoint, $params);
        $responseObject = json_decode($response->body());
        if (isset($responseObject->code) && isset($responseObject->content))
            return new VTPassResponse($responseObject->code, $responseObject->content);
        return new VTPassResponse(-1, null);
    }

    /**
     * This endpoint allows you to verify customer details before attempting to make payment.
     * There are different options for to verify customer details. You can verify the customer’s accounts using either the Account ID, Customer’s registered email (Email registered on Smile account) or customer phone number (Smile Phone number).
     * @param string $customerID Smile Customer Account ID
     * @return VTPassResponse
     *
     * @throws VTPassErrorException
     */
    public function verifySmileCustomerByID($customerID): VTPassResponse
    {
        return $this->verifyMerchant($customerID, 'smile-direct');
    }

    /**
     * This endpoint allows you to verify customer details before attempting to make payment.
     * There are different options for to verify customer details. You can verify the customer’s accounts using either the Account ID, Customer’s registered email (Email registered on Smile account) or customer phone number (Smile Phone number).
     * @param string $customerUniqueDetail Smile Customer Account Unique Identification detail e.g AccountID, Email, or Phone Number
     * @param string $detailType Unique detail type corresponding to the e.g AccountID, Email, or Phone Number
     * @return VTPassResponse
     *
     * @throws VTPassErrorException
     */
    public function verifySmileCustomer($customerUniqueDetail, $detailType): VTPassResponse
    {
        $endpoint = "{$this->baseUrl}/merchant-verify/smile/$detailType";
        $response = $this->withBasicAuth()->post($endpoint, [
            'billersCode' => $customerUniqueDetail,
        ]);
        $responseObject = json_decode($response->body());
        if (isset($responseObject->code) && isset($responseObject->content))
            return new VTPassResponse($responseObject->code, $responseObject->content);
        return new VTPassResponse(-1, null);
    }

    /**
     * This endpoint allows you to verify customer details before attempting to make payment.
     * There are different options for to verify customer details. You can verify the customer’s accounts using either the Account ID, Customer’s registered email (Email registered on Smile account) or customer phone number (Smile Phone number).
     * @param string $customerEmail Smile Customer Account Email
     * @return VTPassResponse
     *
     * @throws VTPassErrorException
     */
    public function verifySmileCustomerByEmail($customerEmail): VTPassResponse
    {
        return $this->verifySmileCustomer($customerEmail, 'email');
    }

    /**
     * This endpoint allows you to verify customer details before attempting to make payment.
     * There are different options for to verify customer details. You can verify the customer’s accounts using either the Account ID, Customer’s registered email (Email registered on Smile account) or customer phone number (Smile Phone number).
     * @param string $customerPhone Smile Customer Account Phone Number
     * @return VTPassResponse
     *
     * @throws VTPassErrorException
     */
    public function verifySmileCustomerByPhone($customerPhone): VTPassResponse
    {
        return $this->verifySmileCustomer($customerPhone, 'phone');
    }


    /**
     * Purchase GoTV plan
     * <strong>This Exclude Smile Data Bundle and strictly for mtn, glo, airtel, and 9mobile only</strong>
     * @param string $requestId This is a unique reference (otherwise refer to as order number generated from your server to uniquely identify this purchase) with which you can use to identify and query the status of a given transaction after the transaction has been executed.
     * @param string $smilePhoneNumber Customer Smile Phone Number to Subscribe
     * @param string $variationCode The variation code of the bundle you wish to subscribe to @see  getVariationCodes($serviceID) to get a list of bundle for that telco
     * @param string $phoneNumber The phone number of the recipient of this service
     * @param int $amount The amount as specified with the provided $variationCode. This amount will be ignored as the variation code determine the price of the data bundle, hence this is an optional parameter.
     * @return VTPassResponse
     *
     * @throws VTPassErrorException
     */
    public function buySmileData(string $requestId, $smilePhoneNumber, $variationCode, $phoneNumber, int $amount = 0): VTPassResponse
    {
        return $this->purchaseProduct($requestId, 'smile-direct', $smilePhoneNumber, $variationCode, $phoneNumber, $amount);
    }

    /**
     * Purchase GoTV plan
     * <strong>This Exclude Smile Data Bundle and strictly for mtn, glo, airtel, and 9mobile only</strong>
     * @param string $requestId This is a unique reference (otherwise refer to as order number generated from your server to uniquely identify this purchase) with which you can use to identify and query the status of a given transaction after the transaction has been executed.
     * @param string $smartCartNumber Customer GoTV
     * @param string $variationCode The variation code of the bundle you wish to subscribe to @see  getVariationCodes($serviceID) to get a list of bundle for that telco
     * @param string $phoneNumber The phone number of the recipient of this service
     * @param int $amount The amount as specified with the provided $variationCode. This amount will be ignored as the variation code determine the price of the data bundle, hence this is an optional parameter.
     * @return VTPassResponse
     *
     * @throws VTPassErrorException
     */
    public function payGoTV(string $requestId, $smartCartNumber, $variationCode, $phoneNumber, int $amount = 0): VTPassResponse
    {
        return $this->purchaseProduct($requestId, 'gotv', $smartCartNumber, $variationCode, $phoneNumber, $amount);
    }

    /**
     * @param string $customerMeterNumber Customer Meter Number to verify
     * @param string $serviceID The DISCO unique serviceID on the VTPass Server i.e. ikeja-electric, eko-electric, portharcourt-electric, e.t.c
     * @param string $type This is basically the type of meter you are trying to validate. It can be either prepaid or postpaid
     * @return VTPassResponse
     * @throws VTPassErrorException
     */
    public function verifyElectricityBillMeterNumber($customerMeterNumber, $serviceID, $type): VTPassResponse
    {
        return $this->verifyMerchant($customerMeterNumber, $serviceID, $type);
    }

    /**
     * Purchase Electricity plan
     * @param string $requestId This is a unique reference (otherwise refer to as order number generated from your server to uniquely identify this purchase) with which you can use to identify and query the status of a given transaction after the transaction has been executed.
     * @param string $serviceID The DISCO unique serviceID on the VTPass Server i.e. ikeja-electric, eko-electric, portharcourt-electric, e.t.c
     * @param $customerMeterNumber
     * @param string $type This is basically the type of meter you are trying to validate. It can be either prepaid or postpaid
     * @param string $phoneNumber The phone number of the recipient of this service
     * @param int $amount The amount as specified with the provided $variationCode. This amount will be ignored as the variation code determine the price of the data bundle, hence this is an optional parameter.
     * @return VTPassResponse
     *
     * @throws VTPassErrorException
     */
    public function buyElectricity(string $requestId, $serviceID, $customerMeterNumber, $type, $phoneNumber, int $amount): VTPassResponse
    {
        return $this->purchaseProduct($requestId, $serviceID, $customerMeterNumber, $type, $phoneNumber, $amount);
    }
}
