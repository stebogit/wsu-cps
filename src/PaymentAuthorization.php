<?php

namespace Wsu\Cps;

class PaymentAuthorization
{
    /** @var \stdClass */
    private $raw_result;

    /** @var int */
    private $return_code;

    /** @var string */
    private $return_message;


    /** @var string */
    public $guid;

    /** @var string */
    public $last_name;

    /** @var string */
    public $first_name;

    /** @var \stdClass|null */
    public $payload;

    /** @var float */
    public $amount;

    /** @var string */
    public $return_url;

    /** @var string */
    public $credit_card_type;

    /** @var string */
    public $masked_credit_card_number;

    // public $client_id;
    // public $type;
    // public $stat_code;
    // public $stat_chng_dttm;
    // public $merchant_id;
    // public $one_step_tran_type;
    // public $browser_ip_addr;
    // public $style_sheet_key;
    // public $attempt_limit;
    // public $cpm_return_code;
    // public $cpm_return_message;
    // public $cpm_sequence_num;
    // public $approval_code;
    // public $record_create_dttm;
    // public $record_create_user_id;
    // public $online_update_dttm;
    // public $online_update_user_id;
    // public $email_addr_dept_contact;
    // public $guid_parent;

    public function __construct(\stdClass $api_result)
    {
        $this->raw_result = $api_result;

        $this->return_code = (int)$api_result->ReadReturnCode;
        $this->return_message = $api_result->ReadReturnMessage;
        $this->guid = $api_result->AuthorizationGUID;
        $this->last_name = $api_result->AuthorizationApplicationIDPrimary;
        $this->first_name = $api_result->AuthorizationApplicationIDSecondary;
        $this->amount = (float)$api_result->AuthorizationAmount;
        $this->return_url = $api_result->AuthorizationReturnURL;
        $this->credit_card_type = $api_result->AuthorizationCreditCardType;
        $this->masked_credit_card_number = $api_result->AuthorizationMaskedCreditCardNumber;

        $this->payload = $api_result->AuthorizationApplication_StateData
            ? json_decode($api_result->AuthorizationApplication_StateData)
            : null;

        // $this->client_id = $api_result->AuthorizationId;
        // $this->type = $api_result->AuthorizationType;
        // $this->stat_code = $api_result->AuthorizationStatCode;
        // $this->stat_chng_dttm = $api_result->AuthorizationStat_Chng_DTTM;
        // $this->merchant_id = $api_result->AuthorizationMerchantID;
        // $this->one_step_tran_type = $api_result->Authorization1StepTranType;
        // $this->browser_ip_addr = $api_result->AuthorizationBrowserIPAddr;
        // $this->style_sheet_key = $api_result->AuthorizationStyleSheetKey;
        // $this->attempt_limit = $api_result->authorizationAttemptLimit;
        // $this->cpm_return_code = $api_result->AuthorizationCPMReturnCode;
        // $this->cpm_return_message = $api_result->AuthorizationCPMReturnMessage;
        // $this->cpm_sequence_num = $api_result->AuthorizationCPMSequenceNum;
        // $this->approval_code = $api_result->AuthorizationApprovalCode;
        // $this->record_create_dttm = $api_result->AuthorizationRecordCreateDttm;
        // $this->record_create_user_id = $api_result->AuthorizationRecordCreateUserID;
        // $this->online_update_dttm = $api_result->AuthorizationOnlineUpdateDttm;
        // $this->online_update_user_id = $api_result->AuthorizationOnlineUpdateUserID;
        // $this->email_addr_dept_contact = $api_result->AuthorizationEmailAddrDeptContact;
        // $this->guid_parent = $api_result->AuthorizationGUIDParent;
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
