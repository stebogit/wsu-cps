<?php

namespace Wsu\Cps;

use GuzzleHttp\Client;


/**
 * Class CpsAPI
 * WSU Central Payment Site interface
 */
class CpsApi extends Client
{
    /**
     * Test Credit card:
     *   Visa - 4111111111111111
     *   Mastercard - 5424000000000015
     *   CVN code - 123
     *   Expiration mm/yy must be any valid future date, city and zip must match
     *
     * Amount code details:
     * https://www.cybersource.com/developers/getting_started/test_and_manage/simple_order_api/HTML/fdiglobal/soapi_fdiglobal_errors.html
     *
     * @Note: in the test environment payment form, ALL fields must be filled out, otherwise the form
     *   doesn't allow you to submit the payment
     */
    const E_COMMERCE_URL = 'https://ewebservice.wsu.edu/CentralPaymentSite_WS/service.asmx';
    const E_COMMERCE_URL_DEV = 'https://test-ewebservice.wsu.edu/CentralPaymentSite_WS/service.asmx';

    /** @var string */
    private $url;

    /** @var string */
    private $merchant_id;

    /** @var string */
    private $one_step_tran_type;

    // /** @var string */
    // private $email_address_dept_contact;

    /**
     * CpsApi constructor.
     *
     * @param string $merchant_id
     * @param string $trans_type
     * @param string|null $env
     */
    public function __construct(
        string $merchant_id,
        string $trans_type,
        // ?string $department_email_contact = '',
        ?string $env = 'production'
    ) {
        parent::__construct();

        $this->url = static::E_COMMERCE_URL;
        $this->merchant_id = $merchant_id;
        $this->one_step_tran_type = $trans_type;
        // $this->email_address_dept_contact = $department_email_contact;

        if ($env !== 'production') {
            $this->url = static::E_COMMERCE_URL_DEV;
        }
    }

    /**
     * @param string $return_url
     * @param string $cancel_url
     * @param string $postback_url
     * @param float $amount
     * @param string $first_name
     * @param string $last_name
     * @param array $payload
     * @return PaymentRequestResponse|null
     */
    public function AuthCapRequestWithCancelURL(
        string $return_url,
        string $cancel_url,
        string $postback_url,
        float $amount,
        ?string $first_name = '',
        ?string $last_name = '',
        ?array $payload = []
    ) {
        $result = $this->fetch($this->url . '/AuthCapRequestWithCancelURL', [
            'MerchantID' => $this->merchant_id,
            'AuthorizationAmount' => (string)$amount, // must be string
            'OneStepTranType' => $this->one_step_tran_type,
            'ApplicationIDPrimary' => $last_name, // this goes to the last name field of the payment form
            'ApplicationIDSecondary' => $first_name, // this goes to the first name field of the payment form
            'ReturnURL' => $return_url,
            'PostbackURL' => $postback_url,
            'cancelURL' => $cancel_url,
            'ApplicationStateData' => $payload ? json_encode($payload) : '',
            // 'EmailAddressDeptContact' => $this->email_address_dept_contact,
            'AuthorizationAttemptLimit' => '2', // must be string
            'BillingAddress' => '',
            'BillingCity' => '',
            'BillingState' => '',
            'BillingZipCode' => '',
            'BillingCountry' => '',
            'StyleSheetKey' => '', // unused field, but still required
            'profileSeqNum' => '',
        ]);

        if (!empty($result)) {
            return new PaymentRequestResponse($result);
        }

        return null;
    }

    /**
     * @param $guid
     *
     * @return AuthCaptureResponse|null
     */
    public function AuthCaptureResponse($guid)
    {
        $result = $this->fetch($this->url . '/AuthCapResponse', ['RequestGUID' => $guid]);

        if (!empty($result)) {
            return new AuthCaptureResponse($result);
        }

        return null;
    }

    /**
     * Returns the transaction details for the passed GUID
     *
     * @param string $guid
     *
     * @return PaymentAuthorization|null
     */
    public function ReadPaymentAuthorization(string $guid)
    {
        $result = $this->fetch($this->url . '/ReadPaymentAuthorization', ['PaymentAuthorizationGUID' => $guid]);

        if (!empty($result)) {
            return new PaymentAuthorization($result);
        }

        return null;
    }

    /**
     * Fetch data via POST
     *
     * @param $url
     * @param $params
     * @return mixed
     */
    private function fetch(string $url, array $params)
    {
        $response = $this->post($url, ['form_params' => $params]);

        $xml = $response->getBody()->getContents();
        $xml = str_replace('> <', '><', $xml);

        $result = json_decode(json_encode(@simplexml_load_string($xml)));

        foreach ($result as $property => $value) {
            if (is_object($value)) {
                if ($value == new \stdClass()) { //covert empty objects to empty strings
                    $result->$property = '';
                }
            } else {
                $result->$property = trim($value);
            }
        }

        return $result;
    }
}
