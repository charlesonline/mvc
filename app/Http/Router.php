<?php

namespace App\Http;

use \Closure;
use \Exception;
use \ReflectionFunction;
use \App\Http\Middleware\Queue as MiddlewareQueue;

class Router{
    /**
     * Url completo do projeto (raiz)
     * @var string
     */
    private $url = '';

    /**
     * Prefixo de todas as rotas
     * @var string
     */
    private $prefix = '';

    /**
     * Indice de rotas
     * @var array
     */
    private $routes = [];

    /**
     * Instancia de Request
     * @var Request
     */
    private $request;

    /**
     * Content type padrão do response
     * @var string
     */
    private $contentType = 'text/html';

    /**
     * Metodo responsavel por iniciar a classe
     * @param string $url
     */
    public function __construct($url)
    {
        $this->request = new Request($this);//retonar par aa minha request a router, pq preciso destas informações lá
        $this->url = $url;
        $this->setPrefix();
    }

    /**
     * reposnsavel por alterar o valor de content type
     * @param mixed $contentType
     */
    public function setContentType($contentType){
        $this->contentType = $contentType;
    }

    /**
     * Set prefixo de todas as rotas
     *
     * @param  string  $prefix  Prefixo de todas as rotas
     *
     * @return  self
     */ 
    public function setPrefix()
    {
        //INFORMAÇÕES DA URL ATUAL
        $parseUrl = parse_url($this->url);

        //DEFINE PREFIXO
        $this->prefix = $parseUrl['path'] ?? '';
    }

    /**
     * Método responsável por adcionar uma rota na classe
     * @param string $method
     * @param string $route
     * @param array $params
     * 
     * @return [type]
     */
    private function addRoute($method,$route,$params = []){
        //VALIDAÇÃO DOS PARAMETROS
        foreach ($params as $key => $value) {
            if ($value instanceof Closure) {
                $params['controller'] = $value;
                unset($params[$key]);
                continue;
            }
        }

        //MIDDLESWARE DA ROTA
        $params['middlewares'] = $params['middlewares'] ?? [];

        //VARIAVEIS DA ROTA
        $params['variables'] = [];

        //PADRÃO DE VALIDAÇÃO DAS VARIAVEIS DAS ROTAS
        $patternVariable = '/{(.*?)}/';
        if ( preg_match_all($patternVariable,$route,$matches) ) {
            $route = preg_replace($patternVariable,'(.*?)',$route);
            $params['variables'] = $matches[1];
        }

        //REMOVE  A BARRA NO FINAL DA ROTA
        $route = rtrim($route,'/');
        
        //PADRÃO DE VALIDAÇÃO DE URL
        $patternRoute = '/^'.str_replace('/','\/',$route).'$/';

        //ADICIONA A ROTA DENTRO DA CLASSE
        $this->routes[$patternRoute][$method] = $params;
    }

    /**
     * Método responsável por definir uma rota de GET
     * @param string $route
     * @param array $params
     */
    public function get($route,$params = []){
        return $this->addRoute('GET',$route,$params);
    }

    /**
     * Método responsável por definir uma rota de POST
     * @param string $route
     * @param array $params
     */
    public function post($route,$params = []){
        return $this->addRoute('POST',$route,$params);
    }

    /**
     * Método responsável por definir uma rota de PUT
     * @param string $route
     * @param array $params
     */
    public function put($route,$params = []){
        return $this->addRoute('PUT',$route,$params);
    }

    /**
     * Método responsável por definir uma rota de DELETE
     * @param string $route
     * @param array $params
     */
    public function delete($route,$params = []){
        return $this->addRoute('DELETE',$route,$params);
    }

    /**
     * Método responsável por retornar a URI desconsiderando o prefixo
     * @return string
     */
    private function getUri(){
        //URI DA REQUEST
        $uri = $this->request->getUri();

        //FATIA A URI COM O PREFIXO
        $xUri = strlen($this->prefix) ? explode($this->prefix,$uri) : [$uri];

        return rtrim(end($xUri),'/');
    }

    /**
     * Método responsável por retornar os dados da rota atual
     * @return array
     */
    private function getRoute(){
        //URI
        $uri = $this->getUri();

        //METHOD
        $httpMethod = $this->request->getHttpMethod();

        //VALIDA AS ROTAS SE A ROTA BATE COM O PADRAO
        foreach ($this->routes as $patternRoute => $methods) {
            if ( preg_match($patternRoute,$uri,$matches) ) {
                //VERICA O MÉTODO
                if ( isset($methods[$httpMethod]) ) {
                    //REMOVO A PRIMEIRA POSIÇÃO
                    unset($matches[0]);

                    //VARIAVEIS PROCESSADAS
                    $keys = $methods[$httpMethod]['variables'];
                    $methods[$httpMethod]['variables'] = array_combine($keys,$matches);
                    $methods[$httpMethod]['variables']['request'] = $this->request;

                    //retorno dos parametros da rota
                    return $methods[$httpMethod];
                } else {
                    //METODO NÃO PERMITIDO
                    throw new Exception("Método não permitido",405);
                }
            }
        }

        //URL NÃO ENCONTRADO
        throw new Exception("Url não encontrada",404);
    }

    /**
     * Método respo por executar a rota atual
     * @return Response
     */
    public function run(){
        try {
            //OBTEM A ROTA ATUAL
            $route = $this->getRoute();

            //VERIFICA O CONTROLADOR
            if ( !isset($route['controller']) ) {
                throw new Exception("Url não pode ser processada",500);
            }

            //ARGUMENTOS
            $args = [];

            //REFLECTION
            $reflection = new ReflectionFunction($route['controller']);
            foreach ($reflection->getParameters() as $parameter) {
                $name = $parameter->getName();
                $args[$name] = $route['variables'][$name] ?? '';
            }

            //RETORNA A EXECUÇÃO DA FILA DE MIDDLEWARES 
            return (new MiddlewareQueue($route['middlewares'],$route['controller'],$args))->next($this->request);

            //EXECUÇÃO - ESSA LINHA ERA ANTES DE IMPLEMENTAR AS MIDDLEWARE
            // return call_user_func_array($route['controller'],$args);
        } catch (Exception $e) {
            return new Response($e->getCode(),$this->getErrorMessage($e->getMessage()),$this->contentType);
        }
    }

    /**
     * Método responsável por retornar a mensagem de erro de acordo com o content type
     * @param string $mensage
     * 
     * @return mixed
     */
    private function getErrorMessage($mensage){
        switch ($this->contentType) {
            case 'application/json':
                return [
                    'error' => $mensage
                ];
                break;
            default:
                return $mensage;
                break;
        }
    }

    /**
     * Reponsável por retornar a url atual
     * @return string
     */
    public function getCurrentUrl(){
        return $this->url.$this->getUri();
    }

    /**
     * Método responsável por redirecionar a URL
     * @param string $route
     */
    public function redirect($route){
        //URL
        $url = $this->url.$route;

        //EXECUTA O REDIRECT
        header('location: '.$url);
        exit;
    }
}