<?php

# http://wiki.commonjs.org/wiki/JSGI/Level0/A/Draft2

class CommonJS_jsgi {
    public function start ($fn) {
        $req = new CommonJS_jsgi_Request ();
        $r = $fn($req);

        $resp = new CommonJS_jsgi_Response ();
        $resp->status = $r['status'];
        $resp->headers->_populate($r['headers']);
        $resp->body = $r['body'];
        $resp->_send();
    }
}

class CommonJS_jsgi_Request {

    public $version;
    public $method;
    public $scriptName;
    public $pathInfo;
    public $queryString;
    public $headers; // "lowercased by otherwise unmangled"
    public $input; // request body stream
    public $scheme;
    public $host;
    public $port;
    public $env; // "this is the place where Server and Middleware put keys"
    public $jsgi;
    public $authType;
    public $pathTranslated;
    public $remoteAddr;
    public $remoteHost;
    public $remoteIdent;
    public $remoteUser;
    public $serverSoftware;

    public function __construct () {
        if (preg_match('/HTTP\/(\d+)\.(\d+)/', $_SERVER['SERVER_PROTOCOL'], $m)) {
            $this->version = new PHECMA_Array($m[1], $m[2]);
        }
        else {
            throw new Exception('Not running under a web server!');
        }

        $this->method = new PHECMA_String($_SERVER['REQUEST_METHOD']);
        // $this->scriptName =
        $this->pathInfo = new PHECMA_String($_SERVER['PATH_INFO']);
        $this->queryString = new PHECMA_String($_SERVER['QUERY_STRING']);
        $this->headers = new CommonJS_jsgi_headers ();
        $this->headers->_populate();
        // $this->input = 
        $this->scheme = new PHECMA_String('http' . ($_SERVER['HTTPS'] ? 's' : ''));
        $this->host = new PHECMA_String($_SERVER['HTTP_HOST']); // FIXME trustable?
        $this->port = new PHECMA_String($_SERVER['SERVER_PORT']);
        // $this->env =
        $this->jsgi = new CommonJS_jsgi_jsgi ();
        $this->authType = new PHECMA_String($_SERVER['AUTH_TYPE']);
        $this->pathTranslated = new PHECMA_String($_SERVER['PATH_TRANSLATED']);
        $this->remoteHost = new PHECMA_String($_SERVER['REMOTE_HOST']);
        // $this->remoteIdent =
        $this->remoteUser = new PHECMA_String($_SERVER['PHP_AUTH_USER']);
        $this->serverSoftware = new PHECMA_String($_SERVER['PHP_AUTH_USER']);
    }

}

class CommonJS_jsgi_Response {
    public $status; // "MUST be a three-digit integer" but we don't check yet
    public $headers;
    public $body;

    public $http_codes = array(
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => 'Switch Proxy',
        307 => 'Temporary Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        418 => "I'm a teapot",
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        425 => 'Unordered Collection',
        426 => 'Upgrade Required',
        449 => 'Retry With',
        450 => 'Blocked by WIndows Parental Controls',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gatewayu Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        509 => 'Bandwidth Limit Exceeded',
        510 => 'Not Extended'
    );

    public function _send () {
        header('HTTP/1.0 ' . $this->status . ' ' . $this->http_codes[$this->status], true, $this->status);
        $this->headers->_send();
        foreach ($this->body->value as $bodypart) {
            echo $bodypart;
        }
    }

    public function __construct () {
        $this->headers = new CommonJS_jsgi_headers ();
    }
}

class CommonJS_jsgi_headers {
    private $headers = array();

    public function __get ($prop) {
        return $this->headers[$prop];
    }

    public function _send () { // FIXME different name?
        foreach ($this->headers as $k => $v) {
            header("$k: $v");
        }
    }

    public function _populate ($populate = false) {
        if (is_array($populate)) {
            foreach ($populate as $k => $v) {
                $this->headers[strtolower($k)] = $v;
            }
        }
        else { // populate from the environment
            foreach ($_SERVER as $k => $v) {
                if (preg_match('/^HTTP_(.+)$/', $k, $m)) {
                    $this->headers[strtolower($m[1])] = $v;
                }
            }
        }
    }
}

class CommonJS_jsgi_jsgi {

    public $version;
    public $errors;
    public $multithread = false;
    public $multiprocess = false; // ?
    public $runOnce = true; // ?
    public $async = false; // because
    public $cgi;

    public function __construct () {
        $this->version = new PHECMA_Array(0, 3);
        // $this->errors = 
        if (preg_match('/CGI\/(\d+)\.(\d+)/', $_SERVER['GATEWAY_INTERFACE'], $m)) {
            $this->cgi = new PHECMA_Array($m[1], $m[2]);
        }
        else {
            $this->cgi = false;
        }
    }
}

?>
