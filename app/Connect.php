<?php

class Connect
{
    public const API_HOST = 'http://host.docker.internal:8080/';

    private string $queryMethod;
    private string $queryPath;
    private array $queryFormParams;
    private int $httpResponseCode;
    private string $httpResponseBody;

    /**
     * @return string
     */
    public function getQueryMethod(): string
    {
        return $this->queryMethod;
    }

    /**
     * @param string $queryMethod
     * @return Connect
     */
    public function setQueryMethod(string $queryMethod): Connect
    {
        $this->queryMethod = $queryMethod;
        return $this;
    }

    /**
     * @return string
     */
    public function getQueryPath(): string
    {
        return $this->queryPath;
    }

    /**
     * @param string $queryPath
     * @return Connect
     */
    public function setQueryPath(string $queryPath): Connect
    {
        $this->queryPath = $queryPath;
        return $this;
    }

    /**
     * @return array
     */
    public function getQueryFormParams(): array
    {
        return $this->queryFormParams;
    }

    /**
     * @param array $queryFormParams
     * @return Connect
     */
    public function setQueryFormParams(array $queryFormParams): Connect
    {
        $this->queryFormParams = $queryFormParams;
        return $this;
    }

    /**
     * @return int
     */
    public function getHttpResponseCode(): int
    {
        return $this->httpResponseCode;
    }

    /**
     * @param int $httpResponseCode
     * @return Connect
     */
    protected function setHttpResponseCode(int $httpResponseCode): Connect
    {
        $this->httpResponseCode = $httpResponseCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getHttpResponseBody(): string
    {
        return $this->httpResponseBody;
    }

    /**
     * @param string $httpResponseBody
     * @return Connect
     */
    protected function setHttpResponseBody(string $httpResponseBody): Connect
    {
        $this->httpResponseBody = $httpResponseBody;
        return $this;
    }

    /**
     * Check returned HTTP code is successfully response.
     * @return bool
     */
    public function isResponseCodeSuccess(): bool {
        return in_array($this->getHttpResponseCode(), [200, 201, 204]);
    }

    /**
     * Process API query.
     * @return $this
     * @throws Exception
     */
    public function process(): Connect
    {
        if(empty($this->getQueryPath())) {
            throw new Exception('Provide query first');
        }

        if(empty($this->getQueryMethod())) {
            $this->setQueryMethod('GET');
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_VERBOSE, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_URL,self::API_HOST . $this->getQueryPath());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        if($this->getQueryMethod() == 'POST') {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($this->getQueryFormParams()));
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        }

        $resultBody = curl_exec($ch);
        $this->setHttpResponseBody($resultBody);
        $this->setHttpResponseCode(curl_getinfo($ch, CURLINFO_HTTP_CODE));
        curl_close($ch);

        return $this;
    }
}