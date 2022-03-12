# Simple HTTP client for PHP

First import the class  
```php
use Haruncpi\Http;
```

### GET request
```php
$data = [
    'user_id' => 123
];

$response = HTTP::get( $url, $data);
```
### POST request
```php
$data = [
    'user_id' => 123
];

$response = HTTP::post( $url, $data);
```

### More options
```php
$response = HTTP::post( $url, $data, $headers, $curlOptions);
```

### Useful methods
```php
$response->getStatusCode(); // to get response code
$response->getHeaders(); // to get all headers as array
$response->getHeader($name); // to get specific header
$response->getBody(); // to get raw response body
$response->getJson(); // to get body as assoc array
$response->getObject(); // to get body as object
```