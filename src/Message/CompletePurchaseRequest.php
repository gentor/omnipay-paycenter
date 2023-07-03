<?php


namespace Omnipay\Paycenter\Message;


use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\AbstractRequest;

class CompletePurchaseRequest extends AbstractRequest
{
    /**
     * @return array
     */
    public function getData()
    {
        $this->validate('PosId', 'AcquirerId', 'TranTicket');

        parse_str($this->httpRequest->getContent(), $data);
        $data['PosId'] = $this->getParameter('PosId');
        $data['AcquirerId'] = $this->getParameter('AcquirerId');
        $data['TranTicket'] = $this->getParameter('TranTicket');

        return $data;
    }

    /**
     * Set PosId
     *
     * Use the PosId assigned by Paycenter.
     *
     * @param string $value
     * @return $this
     */
    public function setPosId($value)
    {
        return $this->setParameter('PosId', $value);
    }

    /**
     * Set AcquirerId
     *
     * Use the AcquirerId assigned by Paycenter.
     *
     * @param string $value
     * @return $this
     */
    public function setAcquirerId($value)
    {
        return $this->setParameter('AcquirerId', $value);
    }

    public function setTranTicket($value)
    {
        return $this->setParameter('TranTicket', $value);
    }

    /**
     * Send the request
     *
     * @return CompletePurchaseResponse
     */
    public function send()
    {
        return parent::send();
    }

    /**
     * @param mixed $data
     * @return CompletePurchaseResponse
     * @throws InvalidRequestException
     */
    public function sendData($data)
    {
        return $this->response = new CompletePurchaseResponse($this, $data);
    }
}