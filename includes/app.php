<?php
header_remove('X-Powered-By');
header("X-Content-Type-Options: nosniff");
header("Referrer-Policy: strict-origin");
header("Feature-Policy: geolocation *");
header('X-Frame-Options: SAMEORIGIN');
header("X-XSS-Protection: 1; mode=block");

ini_set('default_charset', 'UTF-8');
ini_set('date.timezone', 'America/Sao_Paulo');
mb_internal_encoding("UTF-8");
date_default_timezone_set('America/Sao_Paulo');

setlocale(LC_ALL, "pt_BR", "ptb");
setlocale(LC_MONETARY, 'pt_BR');
setlocale(LC_TIME, 'pt_BR');
setlocale(LC_NUMERIC, 'POSIX');

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__.'/../vendor/autoload.php';

use \App\Utils\View;
use \WilliamCosta\DotEnv\Environment;
use \WilliamCosta\DatabaseManager\Database;
use \App\Http\Middleware\Queue as MiddlewareQueue;

//LOAD ENVIRONMENT VARS FROM FILE ON ROOT
Environment::load(__DIR__.'/../');

//CONFIG DATABASE
Database::config(
    getenv('DB_HOST'),
    getenv('DB_NAME'),
    getenv('DB_USER'),
    getenv('DB_PASS'),
    getenv('DB_PORT')
);

define('URL',getenv('URL'));

//DEFINE O VALOR PADRÃO DAS VARIAVEIS
View::init([
    'URL' => URL
]);

//DEFINE O MAPEAMENTO DE MIDDLEWARE
MiddlewareQueue::setMap([
    'maintenance'           => \App\Http\Middleware\Maintenance::class,
    'required-admin-logout' => \App\Http\Middleware\RequireAdminLogout::class,
    'required-admin-login'  => \App\Http\Middleware\RequireAdminLogin::class,
    'api'                   => \App\Http\Middleware\Api::class,
    'user-basic-auth'       => \App\Http\Middleware\UserBasicAuth::class,
    'jwt-auth'              => \App\Http\Middleware\JWTAuth::class
]);

//DEFINE O MAPEAMENTO DE MIDDLEWARE PADRÕES
MiddlewareQueue::setDefault([
    'maintenance'
]);