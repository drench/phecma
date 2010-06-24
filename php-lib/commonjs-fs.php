<?php

class CommonJS_fs {

    public function open ($path, $modeloptions = 'r') {
        return new CommonJS_fs_stream($path, $modeloptions);
    }

    public function read ($path, $modeloptions = 'r') {
        return $this->open($path, $modeloptions)->read();
    }

    public function write ($path, $content, $modeloptions = 'w') {
        return $this->open($path, $modeloptions)->write($content)->flush();
    }

    public function copy ($source, $target) {
        return copy($source, $target);
    }

    public function size ($path) {
        return filesize($path);
    }

    public function exists ($path) {
        return file_exists($path);
    }

    public function isFile ($path) {
        return is_file($path);
    }

    public function isDirectory ($path) {
        return is_dir($path);
    }

    public function isLink ($path) {
        return is_link($path);
    }

    public function isReadable ($path) {
        return is_readable($path);
    }

    public function isWriteable ($path) {
        return is_writable($path);
    }

    public function same ($source, $target) {
        throw new Exception('stub!'); // FIXME
    }
}

class CommonJS_fs_stream {

    private $handle       = NULL;
    private $path         = NULL;
    private $modeloptions = NULL;

    public function __construct ($path, $modeloptions) {
        $this->handle = fopen($path, $modeloptions);
        if ($this->handle) {
            $this->path = $path;
            $this->modeloptions = $modeloptions;
        }
        else {
            throw new Exception("fopen $path failed!");
        }
    }

    public function __destruct () {
        fclose($this->handle);
    }

    public function copy ($target, $mode = 'b') {
        return copy($this->path, $target);
    }

    public function read () {
        // don't use the the handle we went through the trouble of opening!
        return file_get_contents($this->path);
    }

    public function write ($content) {
        $r = fwrite($this->handle, $content);
        if ($r == false) throw new Exception('write() failed');
        else return $this; // so we can chain
    }

    public function flush () {
        return fflush($this->handle) ? $this : false;
    }

}

?>
