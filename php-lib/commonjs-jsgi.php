<?php

# http://wiki.commonjs.org/wiki/JSGI/Level0/A/Draft2

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

        $this->method = $_SERVER['REQUEST_METHOD'];
        // $this->scriptName =
        $this->pathInfo = $_SERVER['PATH_INFO'];
        $this->queryString = $_SERVER['QUERY_STRING'];
        $this->headers = new CommonJS_jsgi_headers(true);
        // $this->input = 
        $this->scheme = 'http' . ($_SERVER['HTTPS'] ? 's' : '');
        $this->host = $_SERVER['HTTP_HOST']; // FIXME trustable?
        $this->port = $_SERVER['SERVER_PORT'];
        // $this->env =
        $this->jsgi = new CommonJS_jsgi_jsgi ();
        $this->authType = $_SERVER['AUTH_TYPE'];
        $this->pathTranslated = $_SERVER['PATH_TRANSLATED'];
        $this->remoteHost = $_SERVER['REMOTE_HOST'];
        // $this->remoteIdent =
        $this->remoteUser = $_SERVER['PHP_AUTH_USER'];
        $this->serverSoftware = $_SERVER['PHP_AUTH_USER'];
    }

}

class CommonJS_jsgi_Response {
    public $status; // "MUST be a three-digit integer" but we don't check yet
    public $headers;
    public $body;

    public function __construct () {
        $this->headers = new CommonJS_jsgi_headers ();
        // $this->body =
    }
}

class CommonJS_jsgi_headers {
    private $headers = array();

    public function __get ($prop) {
        return $this->headers[$prop];
    }

    public function __construct ($populate = false) {
        if ($populate) {
            foreach ($_SERVER as $k => $v) {
                if (preg_match('/^HTTP_(.+)$/', $k, $m)) {
                    $this->headers[strtolower($k)] = $m[1];
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
