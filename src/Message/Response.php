<?php

namespace Omnipay\Paycenter\Message;

use Omnipay\Common\Message\AbstractResponse;

/**
 * Paycenter Response
 *
 * This is the response class for all Paycenter requests.
 *
 * @see \Omnipay\Paycenter\Gateway
 */
class Response extends AbstractResponse
{
    public function isSuccessful()
    {
        return isset($this->data['success']) && $this->data['success'];
    }

    public function getTransactionReference()
    {
        return isset($this->data['reference']) ? $this->data['reference'] : null;
    }

    public function getMessage()
    {
        return isset($this->data['message']) ? $this->data['message'] : null;
    }
}
