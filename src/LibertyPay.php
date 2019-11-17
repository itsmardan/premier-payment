<?php

declare(strict_types=1);

namespace Premier\Payment;

class LibertyPay implements \JsonSerializable
{
    const DEFAULT_GATEWAY = 'https://gateway.charityclear.com/paymentform/';

    protected $merchantId;
    protected $signatureKey;
    protected $currencyCode;
    protected $countryCode;
    protected $gatewayUrl;
    protected $amount;
    protected $action;
    protected $type;
    protected $transactionUnique;
    protected $orderRef;
    protected $captureDelay;
    protected $callbackURL;
    protected $redirectURL;

    public function __construct(string $merchantId, string $signatureKey, int $currencyCode = 826, int $countryCode = 826)
    {
        $this->merchantId = $merchantId;
        $this->signatureKey = $signatureKey;
        $this->currencyCode = $currencyCode;
        $this->countryCode = $countryCode;

        $this->setGatewayUrl(self::DEFAULT_GATEWAY)
            ->setAction('SALE')
            ->setType(1)
        ;
    }

    /**
     * @return string
     */
    public function getSignature(): string
    {
        $fields = $this->getFormFields();
        ksort($fields);

        $signature = http_build_query($fields, '', '&');
        $signature = preg_replace('/%0D%0A|%0A%0D|%0A|%0D/i', '%0A', $signature);
        $hash = hash('SHA512', $signature . $this->signatureKey);

        return $hash;
    }

    /**
     * @return string $gatewayUrl
     */
    public function setGatewayUrl(string $gatewayUrl): self
    {
        $this->gatewayUrl = $gatewayUrl;

        return $this;
    }

    /**
     * @return string
     */
    public function getGatewayUrl(): string
    {
        return $this->gatewayUrl;
    }

    /**
     * @param int $amount
     */
    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @param string $action
     */
    public function setAction(string $action): self
    {
        $this->action = $action;

        return $this;
    }

    /**
     * @param int $type
     */
    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @param mixed $transactionUnique
     */
    public function setTransactionUnique($transactionUnique): self
    {
        $this->transactionUnique = $transactionUnique;

        return $this;
    }

    /**
     * @param mixed $orderRef
     */
    public function setOrderRef($orderRef): self
    {
        $this->orderRef = $orderRef;

        return $this;
    }

    /**
     * @param int $captureDelay
     */
    public function setCaptureDelay(int $captureDelay): self
    {
        $this->captureDelay = $captureDelay;

        return $this;
    }

    /**
     * @param string $callbackURL
     */
    public function setCallbackURL(string $callbackURL): self
    {
        $this->callbackURL = $callbackURL;

        return $this;
    }

    /**
     * @param string $redirectURL
     */
    public function setRedirectURL(string $redirectURL): self
    {
        $this->redirectURL = $redirectURL;

        return $this;
    }

    /**
     * @return array
     */
    public function getFormFields(): array
    {
        return array_filter([
            'merchantID' => $this->merchantId,
            'action' => $this->action,
            'type' => $this->type,
            'amount' => $this->amount,
            'transactionUnique' => $this->transactionUnique,
            'orderRef' => $this->orderRef,
            'captureDelay' => $this->captureDelay,
            'callbackURL' => $this->callbackURL,
            'redirectURL' => $this->redirectURL,
        ]);
    }

    /**
     * @return mixed
     */
    public function jsonSerialize()
    {
        return $this->getFormFields();
    }
}
