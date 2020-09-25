<?php
/**
 * Created By: Henry Ejemuta
 * Project: laravel-vTPass
 * Class Name: VTPass.php
 * Date Created: 7/13/20
 * Time Created: 7:55 PM
 */

namespace HenryEjemuta\LaravelVTPass;

use HenryEjemuta\LaravelVTPass\Exceptions\VTPassFailedRequestException;
use HenryEjemuta\LaravelVTPass\Exceptions\VTPassInvalidParameterException;
use Illuminate\Support\Facades\Http;

/**
 * Class VTPass
 * @package HenryEjemuta\LaravelVTPass
 *
 * This is a Laravel wrapper around the VTPass API hence all failed request will throw VTPassFailedRequestException with the appropriate message from the VTPass API as well as error code
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
     * base url
     *
     * @var
     */
    private $baseUrl;
    private $v1 = "/api/v1/";
    private $v2 = "/api/v2/";

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

    /**
     * Http Client for remote request handling
     * @var Http
     */
    private $httpClient;


    private $oAuth2Token = '';
    private $oAuth2TokenExpires = '';

    public function __construct($baseUrl, $instanceName, $config)
    {
        $this->baseUrl = $baseUrl;
        $this->instanceName = $instanceName;
        $this->config = $config;
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
     * @return  Http
     */
    private function withBasicAuth()
    {
        return Http::withBasicAuth($this->config['username'], $this->config['password'])->asJson();
    }


    /**
     * Using a GET method, the VTpass service categories can be accessed with the endpoint below:
     * @return array
     *
     * @throws VTPassFailedRequestException
     */
    public function getServicesCategories()
    {
        $endpoint = "{$this->baseUrl}/service-categories";

        $response = $this->withBasicAuth()->get($endpoint);

        $responseObject = json_decode($response->body());
        if (!$response->successful())
            throw new VTPassFailedRequestException($responseObject->responseMessage ?? "Path '{$responseObject->path}' {$responseObject->error}", $responseObject->response_description ?? $responseObject->status);

        return $responseObject;
    }


    /**
     * This help in getting the available service IDs on VTpass RESTful API.
     * Using a GET method, the VTpass service IDs for Data [for instance] can be accessed with the endpoint below:
     * @param $serviceCategoryIdentifier
     * @return object
     *
     * @throws VTPassFailedRequestException
     */
    public function getServiceID($serviceCategoryIdentifier)
    {
        $endpoint = "{$this->baseUrl}/services?identifier=$serviceCategoryIdentifier";

        $response = $this->withBasicAuth()->get($endpoint);

        $responseObject = json_decode($response->body());
        if (!$response->successful())
            throw new VTPassFailedRequestException($responseObject->responseMessage ?? "Path '{$responseObject->path}' {$responseObject->error}", $responseObject->response_description ?? $responseObject->status);

        return $responseObject;
    }


    /**
     * This help in getting the available service IDs on VTpass RESTful API.
     * Using a GET method, the VTpass variation codes for GOTV bouquets [for instance] can be accessed with the endpoint below:
     * @param string $serviceID Service ID as specified by VTpass. In this case, it is aero
     * @return object
     *
     * @throws VTPassFailedRequestException
     */
    public function getVariationCodes($serviceID)
    {
        $endpoint = "{$this->baseUrl}/service-variations?serviceID=$serviceID";

        $response = $this->withBasicAuth()->get($endpoint);

        $responseObject = json_decode($response->body());
        if (!$response->successful())
            throw new VTPassFailedRequestException($responseObject->responseMessage ?? "Path '{$responseObject->path}' {$responseObject->error}", $responseObject->response_description ?? $responseObject->status);

        return $responseObject;
    }


    /**
     * This help in getting the available service IDs on VTpass RESTful API.
     * Using a GET method, the VTpass variation codes for GOTV bouquets [for instance] can be accessed with the endpoint below:
     * @param string $serviceID Service ID as specified by VTpass. In this case, it is aero
     * @param string $name This refers to the option name as specified by VTpass. In this case, it is passenger_type. Other option name include trip_type.
     * @return object
     *
     * @throws VTPassFailedRequestException
     */
    public function getProductOptions(string $serviceID, string $name)
    {
        $endpoint = "{$this->baseUrl}/options?serviceID=$serviceID&name=$name";

        $response = $this->withBasicAuth()->get($endpoint);

        return json_decode($response->body());
    }


    /**
     * To purchase vtu
     * @param string $requestId This is a unique reference with which you can use to identify and query the status of a given transaction after the transaction has been executed.
     * @param string $serviceID Service ID as specified by VTpass. For example mtn, use VTPass::
     * @param int $amount The amount you wish to topup
     * @param string $phoneNumber The phone number of the recipient of this service
     * @return object
     *
     * @throws VTPassFailedRequestException Once the request is sent, a sub account code will be returned. This sub account code is the unique identifier for that sub account and will be used to reference the sub account in split payment requests.
     * <strong>Note: </strong> Currency code and Split Percentage will use the configured default in you .env file if not explicitly provided
     * Also, if bank account is not found within the provide bank code a VTPassFailedRequestException will be thrown
     */
    public function purchaseProducts(string $requestId, string $serviceID, int $amount, $phoneNumber)
    {
        $endpoint = "{$this->baseUrl}/pay";

        $response = $this->withBasicAuth()->post($endpoint, [
            'request_id' => $requestId,
            'serviceID' => $serviceID,
            'amount' => $amount,
            'phone' => $phoneNumber
        ]);

        $responseObject = json_decode($response->body());
        if (!$response->successful())
            throw new VTPassFailedRequestException($responseObject->responseMessage ?? "Path '{$responseObject->path}' {$responseObject->error}", $responseObject->response_description ?? $responseObject->status);

        return $responseObject;
    }


}
