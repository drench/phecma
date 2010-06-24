<?php

class CommonJS_xhr {

    public $XMLHttpRequest;

    public function __construct () {
        $this->XMLHttpRequest = new CommonJS_xhr_XMLHttpRequest ();
    }

}

class CommonJS_xhr_XMLHttpRequest {
    public $defaultSettings = NULL;

    public function __construct () {
        $this->defaultSettings = new CommonJS_xhr_settings ();
    }

    public function __invoke () {
        return new CommonJS_xhr_client (array('xhr' => $this));
    }

}

class CommonJS_xhr_client {
    public  $xhr     = NULL; // FIXME name subject to change
    private $request = NULL;

    public  $statusText = NULL; // FIXME should be read-only

    public function __construct ($arg) {
        $this->xhr = $arg['xhr'];
    }

    public function open ($method, $url, $async = false, $user = NULL, $password = NULL) {
        $this->request = new CommonJS_xhr_request (array(
            'client'   => $this,
            'method'   => $method,
            'url'      => $url,
            'async'    => false, // because
            'user'     => $user,
            'password' => $password
        ));
        return $this->request;
    }

    public function send () {
        return $this->request->send();
    }

    public function setRequestHeader ($header, $value) {
        return $this->request->setRequestHeader($header, $value);
    }

    public function getResponseHeader ($header) {
        return $this->request->getResponseHeader($header);
    }
}

class CommonJS_xhr_request {

    public $client   = NULL;
    public $method   = NULL;
    public $url      = NULL;
    public $async    = false; // because
    public $user     = NULL;
    public $password = NULL;

    private $curl        = NULL;
    private $xheaders    = array();
    private $respinfo    = array();
    private $respheaders = array();
    private $response    = NULL;

    public function __construct ($arg) {
        $this->client   = $arg['client'];
        $this->method   = $arg['method'];
        $this->url      = $arg['url'];
        $this->async    = false; // because
        $this->user     = $arg['user'];
        $this->password = $arg['password'];
        $this->curl     = curl_init();
    }

    public function setRequestHeader ($header, $value) {
// FIXME : not doing the required checks from:
// http://wiki.commonjs.org/wiki/HTTP_Client/B#setRequestHeader.28.29
        $this->xheaders[$header] = $value;
    }

    private function get_xheaders () {
        $r = array();
        foreach ($this->xheaders as $k => $v) {
            array_push($r, "$k: $v");
        }
        return $r;
    }

    private function parse_response ($rawresp) {
        $this->respinfo = curl_getinfo($this->curl);

        $rawhead = substr($rawresp, 0, $this->respinfo['header_size']);
        $body = substr($rawresp, $this->respinfo['header_size']);

        $hlines = preg_split('/[\r\n]+/', $rawhead, -1, PREG_SPLIT_NO_EMPTY);
        $this->client->statusText = array_shift($hlines); // FIXME chomp?

        foreach ($hlines as $h) {
            $x = preg_split('/:\s+/', $h, 2);
            $this->respheaders[strtolower($x[0])] = $x[1];
        }
        return $body;
    }

    public function send () {
        curl_setopt(
            $this->curl,
            CURLOPT_URL,
            $this->client->xhr->defaultSettings->host . $this->url
        );
        curl_setopt($this->curl, CURLOPT_HEADER, true);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->get_xheaders());
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        $rawresp = curl_exec($this->curl);
        $responseText = $this->parse_response($rawresp);
        $this->response = new CommonJS_xhr_response (array(
            'request'      => $this,
            'responseText' => $responseText
        ));
        curl_close($this->curl);
        return $this->response;
    }

    public function getResponseHeader ($header) {
        return $this->respheaders[strtolower($header)];
    }
}

class CommonJS_xhr_response {

    private $request      = NULL;
    public  $responseText = NULL;

    public function __get($prop) {
        switch ($prop) {

            case 'responseBody':
                # how is this different from responseText?
                break;

            case 'responseObject':
                # convert json to an "object" (probably PHP object, not hash)
# "be aware this may be set to null if the requests failed"
                break;

            case 'reponseXML':
                # convert XML into ... some kind of object?
# "be aware this may be set to null if the requests failed"
                break;

            default:
                throw new Exception("Unknown property '$prop'");
        }
    }

    public function __construct ($arg) {
        $this->request      = $arg['request'];
        $this->responseText = $arg['responseText'];
    }
}

class CommonJS_xhr_settings {
    public $cookies = array();
    public $host = NULL;

    public function __get($prop) {
        switch ($prop) {

            case 'async':
                return false; # Can't be true here
                break;

            default:
                throw new Exception("Unknown property '$prop'");
        }
    }

    public function __set($prop, $value) {
        switch ($prop) {

            case 'async':
                if ($value != false)
                    throw new Exception('async HTTP not supported');
                break;

            default:
                throw new Exception("Unknown property '$prop'");
        }
    }
}

?>
