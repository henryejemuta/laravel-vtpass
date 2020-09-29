<?php
/**
 * Created By: Henry Ejemuta
 * Project: laravel-vtpass
 * Class Name: VTPass.php
 * Date Created: 7/13/20
 * Time Created: 8:44 PM
 */

namespace HenryEjemuta\LaravelVTPass\Facades;

use HenryEjemuta\LaravelVTPass\Classes\VTPassResponse;
use Illuminate\Support\Facades\Facade;


/**
 * @method static VTPassResponse getServicesCategories()
 * @method static VTPassResponse getServiceID($serviceCategoryIdentifier)
 * @method static VTPassResponse getVariationCodes($serviceID)
 * @method static VTPassResponse getProductOptions(string $serviceID, string $name)
 * @method static VTPassResponse purchaseAirtime(string $requestId, string $serviceID, int $amount, $phoneNumber)
 * @method static VTPassResponse queryTransactionStatus(string $requestId)
 * @method static VTPassResponse purchaseProduct(string $requestId, string $serviceID, $billersCode, $variationCode, $phoneNumber, int $amount = 0)
 * @method static VTPassResponse purchaseData(string $requestId, string $serviceID, $phoneNumber, $variationCode, $customerPhoneNumber, int $amount = 0)
 * @method static VTPassResponse getSmileBundles()
 * @method static VTPassResponse verifyMerchant($billersCode, $serviceID, $type = null)
 * @method static VTPassResponse verifySmileCustomerByID($customerID)
 * @method static VTPassResponse verifySmileCustomer($customerUniqueDetail, $detailType)
 * @method static VTPassResponse verifySmileCustomerByEmail($customerEmail)
 * @method static VTPassResponse verifySmileCustomerByPhone($customerPhone)
 * @method static VTPassResponse buySmileData(string $requestId, $smilePhoneNumber, $variationCode, $phoneNumber, int $amount = 0)
 * @method static VTPassResponse payGoTV(string $requestId, $smartCartNumber, $variationCode, $phoneNumber, int $amount = 0)
 * @method static VTPassResponse verifyElectricityBillMeterNumber($customerMeterNumber, $serviceID, $type)
 * @method static VTPassResponse buyElectricity(string $requestId, $serviceID, $customerMeterNumber, $type, $phoneNumber, int $amount) @throws VTPassErrorException
 *
 * @see \Henryejemuta\LaravelVTPass\VTPass
 */
class VTPass extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'vtpass';
    }
}
