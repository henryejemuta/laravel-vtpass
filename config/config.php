<?php
/**
 * Created By: Henry Ejemuta
 * Email: henry.ejemuta@gmail.com
 * GitHub: https://github.com/henryejemuta
 * Project: laravel-vtpass
 * Class Name: VTPass.php
 * Date Created: 7/13/20
 * Time Created: 3:43 PM
 */

return [
    /*
     * ---------------------------------------------------------------
     * Base Url
     * ---------------------------------------------------------------
     *
     * The VTPass base url upon which others is based, if not set it's going to use the sandbox version
     */
    'base_url' => env('VTPASS_BASE_URL', 'https://sandbox.vtpass.com/api'),


    /*
     * ---------------------------------------------------------------
     * Username
     * ---------------------------------------------------------------
     *
     * Your Username is your VtPass Email
     */
    'username' => env('VTPASS_USERNAME', 'henry.ejemuta@gmail.com'),


    /*
     * ---------------------------------------------------------------
     * Password
     * ---------------------------------------------------------------
     *
     * Your VTPass Password
     */
    'password' => env('VTPASS_PASSWORD', 'sandbox'),

    /*
     * ---------------------------------------------------------------
     * Contract Code
     * ---------------------------------------------------------------
     *
     * This can be gotten from your VTPass dashboard, if not set the sandbox version would be used
     */
    'contract_code' => env('VTPASS_CONTRACT_CODE', '4934121686'),

    /*
     * ---------------------------------------------------------------
     * Default Split Percentage
     * ---------------------------------------------------------------
     *
     * The default percentage to be split into the sub account on any transaction. (Only applies if a specific amount is not passed during transaction initialization)
     */
    'default_split_percentage' => env('VTPASS_DEFAULT_SPLIT_PERCENTAGE', 20),

    /*
     * ---------------------------------------------------------------
     * Default Currency Code
     * ---------------------------------------------------------------
     *
     * The default currency to be used for any request requiring currency code usage
     */
    'default_currency_code' => env('VTPASS_DEFAULT_CURRENCY_CODE', 'NGN'),


    /*
     * ---------------------------------------------------------------
     * Default Payment Redirect URL
     * ---------------------------------------------------------------
     *
     * The default currency to be used for any request requiring currency code usage
     */
    'redirect_url' => env('VTPASS_DEFAULT_PAYMENT_REDIRECT_URL', env('APP_URL')),



    /*
     * ---------------------------------------------------------------
     * Wallet ID
     * ---------------------------------------------------------------
     *
     * ID of business wallet from which transfer will initiated.
     */
    'wallet_id' => env('VTPASS_WALLET_ID', '2A47114E88904626955A6BD333A6B164'),

];
