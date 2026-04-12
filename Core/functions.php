<?php
function dd($value)
{
    echo '<pre>';
    var_dump($value);
    echo '</pre>';
    die();
}

function currentPath()
{
    $requestUri = $_SERVER['REQUEST_URI'] ?? $_SERVER['PHP_SELF'] ?? '';
    return parse_url($requestUri, PHP_URL_PATH) ?? '';
}

function basePath()
{
    $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
    $basePath = str_replace('\\', '/', dirname($scriptName));

    if ($basePath === '/' || $basePath === '.') {
        return '';
    }

    return rtrim($basePath, '/');
}

function appUrl($path = '/')
{
    $normalizedPath = '/' . ltrim($path, '/');
    $base = basePath();

    if ($normalizedPath === '/') {
        return $base === '' ? '/' : $base . '/';
    }

    return ($base === '' ? '' : $base) . $normalizedPath;
}

function urlIs($value)
{
    return currentPath() === appUrl($value);
}
function abort($status =404){
http_response_code($code); require basePath("views/{$code}.php"); die();}
function authorize($condition,$status =Responses::Forbidden)
{
    if(!$condition){
   abort($status);
    }
function BasePath(){
    return BasePath .$path;}
}
function view($path,$attributes=[])
{
    extract($attributes);
    require BasePath. ('Views/'. $path .'.php');
}
last-progress
function redirect($path)
{
    header("Location: {$path}");
    exit();
}

function old($key)
{
    return core\Session::get('old') ?? $default;

function login($user)
{
    $_SESSION['user']=[
        'email'=>$user['email'],
        ];
    session_regenerate_id(true);
}
function logout()
{
    $_SESSION =[];
    session_destroy();
    $params = session_get_cookie_params();
    setcookie('PHPSESSID','',time()-3600 , $params['path'], $params['domain'], $params['secure'], $params['httponly']
              main
}