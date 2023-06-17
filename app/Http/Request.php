<?php

namespace App\Http;

class Request{
    /**
     * Instancia do router
     * @var Router
     */
    private $router;

    /**
     * Método
     * @var string
     */
    private $httpMethod;

    /**
     * @var string
     */
    private $uri;

    /**
     * @var array
     */
    private $queryParams = [];

    /**
     * @var array
     */
    private $postVars = [];

    /**
     * @var array
     */
    private $headers = [];

    public function __construct($router)
    {
        $this->router = $router;
        $this->queryParams = $_GET ?? [];
        $this->postVars = $_POST ?? [];
        $this->headers = getallheaders();
        $this->httpMethod = $_SERVER['REQUEST_METHOD'] ?? '';
        $this->setUri();
    }

    /**
     * Get método
     *
     * @return  string
     */ 
    public function getHttpMethod()
    {
        return $this->httpMethod;
    }

    /**
     * Get the value of uri
     *
     * @return  string
     */ 
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Get the value of queryParams
     *
     * @return  array
     */ 
    public function getQueryParams()
    {
        return $this->queryParams;
    }

    /**
     * Get the value of postVars
     *
     * @return  array
     */ 
    public function getPostVars()
    {
        return $this->postVars;
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
     * Get instancia do router
     *
     * @return Router
     */ 
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * Set the value of uri
     *
     * @param  string  $uri
     *
     * @return  self
     */ 
    public function setUri()
    {
        //URI COMPLETA  (COM GETS)
        $this->uri = $_SERVER['REQUEST_URI'] ?? '';

        //REMOVE OS GETS DA URI
        $xURI = explode('?',$this->uri);

        return $this->uri = $xURI[0];
    }
}