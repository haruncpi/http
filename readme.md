# Simple HTTP client for PHP

Install  
```
composer require haruncpi/http
```  

Import class  
```php
use Haruncpi\Http\Http;
```

### GET request
```php
$url    = "https://jsonplaceholder.typicode.com/comments";
$data   = [ 'postId' => 1 ];

$response = HTTP::get( $url, $data );
```
### POST request
```php
$url    = "https://jsonplaceholder.typicode.com/posts";
$data   = [ 'title' => 'This is post title' ];

$response = HTTP::post( $url, $data );
```

### More options
```php
$response = HTTP::get( $url, $data, $headers, $curlOptions );
$response = HTTP::post( $url, $data, $headers, $curlOptions );
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