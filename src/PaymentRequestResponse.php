<?php

namespace Wsu\Cps;

class PaymentRequestResponse
{
    /** @var int */
    private $return_code;

    /** @var string */
    private $return_message;

    /** @var \stdClass */
    private $raw_result;


    /** @var string */
    public $guid;

    /** @var string */
    public $redirect_url;

    public function __construct(\stdClass $api_response)
    {
        $this->raw_result = $api_response;

        $this->return_code = (int)$api_response->RequestReturnCode;
        $this->return_message = $api_response->RequestReturnMessage;

        $this->guid = $api_response->RequestGUID;
        $this->redirect_url = $api_response->WebPageURLAndGUID;
    }

    public function isValid()
    {
        // 0 is a good response
        return $this->return_code == 0;
    }

    public function getReturnCode(): int
    {
        return (int)$this->return_code;
    }

    public function getReturnGuid(): string
    {
        return $this->guid;
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
