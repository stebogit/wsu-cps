<?php

namespace Wsu\Cps;

class AuthCaptureResponse
{
    /** @var int */
    private $return_code;

    /** @var string */
    private $return_message;

    /** @var \stdClass */
    private $raw_result;


    /** @var string */
    public $guid;

    /** @var float */
    public $amount;

    /** @var string */
    public $last_name;

    /** @var string */
    public $first_name;

    /** @var \stdClass|null */
    public $payload;

    /** @var string */
    public $credit_card_type;

    /** @var string */
    public $masked_credit_card_number;


    // public $authorization_type;
    // public $merchant_id;
    // public $one_step_tran_type;
    // public $cpm_return_code;
    // public $cpm_return_message;
    // public $approval_code;
    // public $cpm_sequence_num;
    // public $attempt_limit;
    // public $style_sheet_key;

    public function __construct(\stdClass $api_result)
    {
        $this->raw_result = $api_result;

        $this->return_code = (int)$api_result->ResponseReturnCode;
        $this->return_message = $api_result->ResponseReturnMessage;
        $this->guid = $api_result->ResponseGUID;
        $this->amount = (float)$api_result->AuthorizationAmount;
        $this->last_name = $api_result->ApplicationIDPrimary;
        $this->first_name = $api_result->ApplicationIDSecondary;
        $this->credit_card_type = $api_result->CreditCardType;
        $this->masked_credit_card_number = $api_result->MaskedCreditCardNumber;

        $this->payload = $api_result->ApplicationStateData
            ? json_decode($api_result->ApplicationStateData)
            : null;

        // $this->authorization_type = $api_result->AuthorizationType;
        // $this->merchant_id = $api_result->MerchantID;
        // $this->one_step_tran_type = $api_result->OneStepTranType;
        // $this->cpm_return_code = $api_result->CPMReturnCode;
        // $this->cpm_return_message = $api_result->CPMReturnMessage;
        // $this->approval_code = $api_result->ApprovalCode;
        // $this->cpm_sequence_num = $api_result->CPMSequenceNum;
        // $this->attempt_limit = $api_result->AuthorizationAttemptLimit;
        // $this->style_sheet_key = $api_result->StyleSheetKey;
    }

    public function isApproved(): bool
    {
        return $this->return_code == 0;
    }

    public function resourceNotFound(): bool
    {
        return $this->return_code == 1;
    }

    public function transactionClosed(): bool
    {
        return $this->return_code == 2;
    }

    public function getReturnCode(): int
    {
        return $this->return_code;
    }

    public function getReturnMessage(): string
    {
        return $this->return_message;
    }

    public function getRawResult(): \stdClass
    {
        return $this->raw_result;
    }
}
