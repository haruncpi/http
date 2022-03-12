<?php

namespace Haruncpi\Http;

class Http
{
    /**
     * GET constant value.
     */
    const TYPE_GET = 'GET';

    /**
     * POST constant value.
     */
    const TYPE_POST = 'POST';

    /**
     * Hold response raw body.
     *
     * @var mixed
     */
    private $body;

    /**
     * Hold response code
     *
     * @var int
     */
    private $statusCode;

    /**
     * Hold response raw headers.
     *
     * @var string
     */
    private $headers;

    /**
     * Common curl request method for GET and POST request
     *
     * @param string    $type          HTTP verb GET, POST.
     * @param string    $url           Request URL.
     * @param array     $data          Request data.
     * @param array     $headers       Headers data.
     * @param array     $curlOptions   curl options.
     * @return void
     */
    private function curlRequest($type, $url, $data = [], $headers = [], $curlOptions = [])
    {
        $curl = curl_init();
        $type = strtoupper($type);

        if (self::TYPE_GET === $type && count($data)) {
            $url = $url . '?' . http_build_query($data);
        }

        $default = array(
            CURLOPT_URL             => $url,
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_ENCODING        => "",
            CURLOPT_MAXREDIRS       => 10,
            CURLOPT_TIMEOUT         => 30,
            CURLOPT_CUSTOMREQUEST   => $type,
            CURLOPT_HTTPHEADER      => $headers,
        );

        $finalCurlOptions = array_replace($default, $curlOptions);

        // Must required for get headers and body
        $finalCurlOptions[CURLOPT_HEADER] = 1;

        if (self::TYPE_POST === $type && count($data)) {
            $finalCurlOptions[CURLOPT_POSTFIELDS] = json_encode($data);
        }

        curl_setopt_array($curl, $finalCurlOptions);

        $response = curl_exec($curl);
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $resonseHeaders = substr($response, 0, $header_size);
        $body = substr($response, $header_size);

        curl_close($curl);

        $this->body = $body;
        $this->statusCode = $statusCode;
        $this->headers = $resonseHeaders;
    }

    /**
     * GET request
     *
     * @param   string    $url
     * @param   array     $data
     * @param   array     $headers
     * @param   array     $curlOptions
     * @return  object
     */
    public static function get($url, $data = [], $headers = [], $curlOptions = [])
    {
        $obj = new self;
        $obj->curlRequest(self::TYPE_GET, $url, $data, $headers, $curlOptions);
        return $obj;
    }

    /**
     * POST request
     *
     * @param   string  $url
     * @param   array   $data
     * @param   array   $headers
     * @param   array   $curlOptions
     * @return  object
     */
    public static function post($url, $data = [], $headers = [], $curlOptions = [])
    {
        $obj = new self;
        $obj->curlRequest(self::TYPE_POST, $url, $data, $headers, $curlOptions);
        return $obj;
    }

    /**
     * Get response status code
     *
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Get response headers
     *
     * @return array
     */
    public function getHeaders()
    {
        $headers = array();
        $arrRequests = explode("\r\n\r\n", $this->headers);
        for ($index = 0; $index < count($arrRequests) - 1; $index++) {

            foreach (explode("\r\n", $arrRequests[$index]) as $i => $line) {
                if ($i === 0)
                    $headers[$index]['http_code'] = $line;
                else {
                    list($key, $value) = explode(': ', $line);
                    $headers[$index][$key] = $value;
                }
            }
        }

        return isset($headers[0]) ? $headers[0] : null;
    }

    /**
     * Get response header's specific key's value
     *
     * @param string $key
     * @return mixed
     */
    public function getHeader($key)
    {
        $headers = $this->getHeaders();
        if (is_array($headers)) {
            return $headers[$key];
        } else {
            return null;
        }
    }

    /**
     * Get response raw body
     *
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Get response body as JSON array.
     *
     * @return array|mixed
     */
    public function getJson()
    {
        return json_decode($this->body, true);
    }

    /**
     * Get response as standard PHP object
     *
     * @return object
     */
    public function getObject()
    {
        return json_decode($this->body);
    }
}
