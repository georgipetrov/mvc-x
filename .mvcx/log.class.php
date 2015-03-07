<?php
class Log {
    private $file;
    private $fh;
    private $debug = array();

    public function __construct($file = '') {
        $this->file = str_replace('/', DS, $file);
    }

    private function mkDir() {
        $dir = dirname($this->file);
        if (!file_exists($dir)) {
            if (!@mkdir($dir, 0755, true)) {
                throw new PermissionDeniedException('Cannot create log directory ' . $dir);
            }
        }
    }

    private function openFile() {
        $this->mkDir();
        $this->fh = fopen($this->file, 'a');
    }

    private function put($msg) {
        if (flock($this->fh, LOCK_EX)) {
            fwrite($this->fh, date('[Y-m-d H:i:s] ') . $msg . "\n");
            fflush($this->fh);
            flock($this->fh, LOCK_UN);
        } else {
            throw new PermissionDeniedException('Could not acquire exclusive lock over the log file');
        }
    }

    private function releaseFile() {
        fclose($this->fh);
    }

    public function write($msg) {
        try {
            $this->openFile();
            $this->put($msg);
            $this->releaseFile();
        } catch (Exception $e) {
            $this->releaseFile();
            //TODO: Display the error if error reporting is enabled
        }
    }

    public function debug($key, $value = '') {
        if (is_array($key)) {
            foreach ($key as $k=>$v) {
                $this->debug[$k] = $v;
            }
        } else {
            $this->debug[$key] = $value;
        }
    }

    public function getDebugLogs() { return $this->debug; }
}

class PermissionDeniedException extends Exception {}
