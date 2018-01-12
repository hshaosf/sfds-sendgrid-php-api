<?php

class SendgridApi{
  protected $sg;

  public function __construct(){

    $apiKey = getenv('SENDGRID_API_KEY');

    $this->sg = new \SendGrid($apiKey);

  }

  public function send($request_body){
    $request_body = $this->getSendgridRequestBody($request_body);
    if(empty($request_body)){ return false; }
    return $response = $this->sg->client->mail()->send()->post($request_body);
  }

  protected function getSendgridRequestBody($request_body){
    $required_fields = array('to', 'from_email', 'subject');
    foreach($required_fields as $v){
      if(!isset($request_body[$v])){ return false; }
    }
    if(!isset($request_body['body_text']) && !isset($request_body['body_html'])){
      return false; 
    }
     
    $sendgrid_request = array(
      'personalizations' => array(array()),
      'from' => array(),
      'content' => array()
    );   
    foreach($request_body as $k=>$v){
      switch($k){
        case 'to' : case 'cc' : case 'bcc' : case 'subject' : 
          $sendgrid_request['personalizations'][0][$k] = $v;
          break;
        case 'from_email' : 
          $sendgrid_request['from']['email'] = $v;
          break;
        case 'from_name' : 
          $sendgrid_request['from']['name'] = $v;
          break;
        case 'reply_to_email' : 
          $sendgrid_request['reply_to']['email'] = $v;
          break;
        case 'reply_to_name' : 
          $sendgrid_request['reply_to']['name'] = $v;
          break;
        case 'body_text' : 
          array_push($sendgrid_request['content'], array('type'=>'text/plain', 'value'=>$v));
          break;
        case 'body_html' : 
          array_push($sendgrid_request['content'], array('type'=>'text/html', 'value'=>$v));
          break;
        case 'template_id' : 
          $sendgrid_request[$k] = $v;
          break;
        case 'tags' : 
          $sendgrid_request['categories'] = $v;
          break;
      }

    }

    return $sendgrid_request; 
  }

}