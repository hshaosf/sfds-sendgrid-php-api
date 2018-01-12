<?php

require 'vendor/autoload.php';
require 'src/SendGridApi.php';

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

$sendgrid = new \SendGridApi();

$request_body = $event['request']['payload'];

$app_name = '';
$app =  $platform['api']->get->__invoke('system/app/'.$platform['session']['app']['id']);
if($app['status_code']==200){
    $app_name = $app['content']['name'];
}

!isset($request_body['tags']) $request_body['tags'] = array();
array_push($request_body['tags'], 'dreamfactory '.$app_name);

$response = $sendgrid->send($request_body);

$event['response']['status_code'] = $response->statusCode();
$event['response']['content'] = $response->body();



