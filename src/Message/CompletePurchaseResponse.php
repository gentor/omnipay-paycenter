<?php


namespace Omnipay\Paycenter\Message;


use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\NotificationInterface;

class CompletePurchaseResponse extends AbstractResponse implements NotificationInterface
{
    public function isSuccessful()
    {
        return $this->getCode() == 0 &&
            $this->getStatus() == 'Success' &&
            $this->hasValidSiganture();
    }

    public function getCode()
    {
        return $this->data['ResultCode'] ?? null;
    }

    public function getStatus()
    {
        return $this->data['StatusFlag'] ?? null;
    }

    public function getTransactionId()
    {
        return $this->data['TransactionId'] ?? null;
    }

    public function getTransactionReference()
    {
        return $this->data['SupportReferenceID'] ?? null;
    }

    public function getTransactionStatus()
    {
        if ($this->isSuccessful()) {
            return self::STATUS_COMPLETED;
        }

        return self::STATUS_FAILED;
    }

    public function getMessage()
    {
        if (!empty($this->data['ResultDescription'])) {
            return $this->data['ResultDescription'];
        }

        return $this->data['ResponseDescription'] ?? null;
    }

    public function hasValidSiganture()
    {
        $attributes = [
            'TranTicket',
            'PosId',
            'AcquirerId',
            'MerchantReference',
            'ApprovalCode',
            'Parameters',
            'ResponseCode',
            'SupportReferenceID',
            'AuthStatus',
            'PackageNo',
            'StatusFlag',
        ];

        $messaage = '';
        foreach ($attributes as $attribute) {
            $messaage .= ($this->data[$attribute] ?? '') . ';';
        }

        $messaage = trim($messaage, ';');
        $hash = strtoupper(hash_hmac('sha256', $messaage, $this->data['TranTicket']));

        return $hash == $this->data['HashKey'] ?? null;
    }
}