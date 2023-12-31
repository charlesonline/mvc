<?php
include __DIR__.'/includes/app.php';

use \App\Http\Router;

// echo '<pre>';
// print_r($obRequest);
// echo '</pre>'; exit;

//INICIA O ROUTER
$obRouter = new Router(URL);

//INCLUI AS ROTAS DE PAGINAS
include __DIR__.'/routes/pages.php';

//INCLUI AS ROTAS DO PAINEL
include __DIR__.'/routes/admin.php';

//INCLUI AS ROTAS DA API
include __DIR__.'/routes/api.php';

//IMPRIME O RESPONSE DA PÁGINA
$obRouter->run()->sendResponse();
