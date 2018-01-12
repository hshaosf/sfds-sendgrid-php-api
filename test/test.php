<?php

require '../vendor/autoload.php';
require '../src/SendGridApi.php';

$request_body = json_decode('{
  "to": [
    {
      "name": "Recipient Name",
      "email": "<Email>"
    }
  ],
  "cc": [
    {
      "name": "CC Recipient Name",
      "email": "<Email>"
    }
  ],
  "subject": "Test Subject",
  "body_text": "Hello: This is a test",
  "body_html": "<h1>Hello</h1><p>This is a test.</p> ",
  "from_name": "Your Name",
  "from_email": "<Email>",
  "reply_to_name": "Reply To Name",
  "reply_to_email": "<Email>",
  "tags": ["welcome", "hello world"]
}', TRUE);

$dotenv = new Dotenv\Dotenv('../');
$dotenv->load();

$sendgrid = new \SendGridApi();
$response = $sendgrid->send($request_body);

echo $response->statusCode();
echo $response->body();
print_r($response->headers());
