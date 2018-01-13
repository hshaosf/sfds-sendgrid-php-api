<?php

require 'vendor/autoload.php';
require 'src/SendgridApi.php';

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

$sendgrid = new \SendgridApi();

$request_body = $event['request']['payload'];

$app_name = '';
$app =  $platform['api']->get->__invoke('system/app/'.$platform['session']['app']['id']);
if($app['status_code']==200){
    $app_name = $app['content']['name'];
}

if(!isset($request_body['tags'])) $request_body['tags'] = array();
array_push($request_body['tags'], 'dreamfactory '.$app_name);

$response = $sendgrid->send($request_body);
if($response){
  $event['response']['status_code'] = $response->statusCode();
  $event['response']['content'] = $response->body();
}else{
  $event['response']['status_code'] = 500;
  $event['response']['content'] = 'Invalid request';
}

