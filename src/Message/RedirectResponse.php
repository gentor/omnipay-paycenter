<?php
/**
 * Viva Payments Redirect (REST) Response
 */

namespace Omnipay\Paycenter\Message;

use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\Common\Message\RequestInterface;
use Symfony\Component\HttpFoundation\RedirectResponse as HttpRedirectResponse;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class RedirectResponse implements RedirectResponseInterface
{
    /** @var string */
    // protected $baseEndpoint;
    protected $testEndpoint = 'https://paycenter.piraeusbank.gr/redirection/pay.aspx';
    protected $liveEndpoint = 'https://paycenter.piraeusbank.gr/redirection/pay.aspx';

    public function __construct(
        RequestInterface $request,
                         $data,
                         $statusCode = 200
    )
    {
        $this->data = $data;
    }


    public function isRedirect()
    {
        // The gateway returns errors in several possible different ways.
        if ($this->getRedirectMethod() != 'POST') {
            return false;
        }
        return true;
    }


    public function getRedirectUrl()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }


    public function getRedirectMethod()
    {
        return 'POST';
    }


    public function getRedirectData()
    {
        return array(
            'AcquirerId' => $this->data['AcquirerId'],
            'MerchantId' => $this->data['MerchantId'],
            'PosId' => $this->data['PosId'],
            'MerchantReference' => $this->data['MerchantReference'],
            'User' => $this->data['User'],
            'LanguageCode' => $this->data['LanguageCode'],
            'ParamBackLink' => $this->data['ParamBackLink'],
        );
    }

    /**
     * Automatically perform any required redirect
     *
     * This method is meant to be a helper for simple scenarios. If you want to customize the
     * redirection page, just call the getRedirectUrl() and getRedirectData() methods directly.
     *
     * @return void
     */
    public function redirect()
    {
        $this->getRedirectResponse()->send();
    }

    /**
     * @return HttpRedirectResponse|HttpResponse
     */
    public function getRedirectResponse()
    {
        if ('GET' === $this->getRedirectMethod()) {
            return new HttpRedirectResponse($this->getRedirectUrl());
        }

        $hiddenFields = '';
        foreach ($this->getRedirectData() as $key => $value) {
            $hiddenFields .= sprintf(
                    '<input type="hidden" name="%1$s" value="%2$s" />',
                    htmlentities($key, ENT_QUOTES, 'UTF-8', false),
                    htmlentities($value, ENT_QUOTES, 'UTF-8', false)
                ) . "\n";
        }

        $output = '<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Redirecting...</title>
</head>
<body onload="document.forms[0].submit();">
    <form action="%1$s" method="post">
        <p>Redirecting to payment page...</p>
        <p>
            %2$s
            <input type="submit" value="Continue" />
        </p>
    </form>
</body>
</html>';
        $output = sprintf(
            $output,
            htmlentities($this->getRedirectUrl(), ENT_QUOTES, 'UTF-8', false),
            $hiddenFields
        );

        return new HttpResponse($output);
    }

    public function getRequest()
    {
        return null;
    }


    public function isSuccessful()
    {
        return null;
    }


    public function isCancelled()
    {
        return null;
    }


    public function getMessage()
    {
        return 'Not a valid redirect';
    }


    public function getCode()
    {
        return null;
    }


    public function getTransactionReference()
    {
        return $this->data['TranTicket'];
    }


    public function getData()
    {
        return $this->data;
    }


    public function getTestMode()
    {
        return $this->data['testMode'];
    }

}
