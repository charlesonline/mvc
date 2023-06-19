<?php

namespace App\Http;

class Response{
    /**
     * @var int
     */
    private $httpCode = 200;

    /**
     * @var array
     */
    private $headers = [];

    /**
     * @var string
     */
    private $contentType = 'text/html';

    /**
     * @var mixed
     */
    private $content;

    /**
     * @param int $httpCode
     * @param mixed $content
     * @param string $contentType
     */
    public function __construct($httpCode,$content,$contentType = 'text/html')
    {
        $this->setHttpCode($httpCode);
        $this->setContent($content);
        $this->setContentType($contentType);
    }

    /**
     * Get the value of httpCode
     *
     * @return  int
     */ 
    public function getHttpCode()
    {
        return $this->httpCode;
    }

    /**
     * Set the value of httpCode
     *
     * @param  int  $httpCode
     *
     * @return  self
     */ 
    public function setHttpCode(int $httpCode)
    {
        $this->httpCode = $httpCode;

        return $this;
    }

    /**
     * Get the value of headers
     *
     * @return  array
     */ 
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Set the value of headers
     *
     * @param  array  $headers
     *
     * @return  self
     */ 
    public function setHeaders(array $headers)
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * Get the value of contentType
     *
     * @return  string
     */ 
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * Set the value of contentType
     *
     * @param  string  $contentType
     *
     * @return  self
     */ 
    public function setContentType(string $contentType)
    {
        $this->contentType = $contentType;
        $this->addHeader('content-Type',$contentType);

        return $this;
    }

    /**
     * Get the value of content
     *
     * @return  mixed
     */ 
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set the value of content
     *
     * @param  mixed  $content
     *
     * @return  self
     */ 
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @param mixed $key
     * @param mixed $value
     * 
     * @return [type]
     */
    public function addHeader($key,$value){
        $this->headers[$key] = $value;
    }

    /**
     * @return [type]
     */
    private function sendHeaders(){
        //STATUS
        http_response_code($this->getHttpCode());

        //ENVIAR HEADERS
        foreach ($this->getHeaders() as $key => $value) {
            header($key.': '.$value);
        }
    }

    /**
     * @return [type]
     */
    public function sendResponse(){
        $this->sendHeaders();
        
        switch ($this->getContentType()) {
            case 'text/html':
                echo $this->getContent();
                break;
            case 'application/json':
                echo json_encode($this->getContent(),JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                break;
        }
    }
}