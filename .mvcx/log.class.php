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
            $this->debug('Warning', 'Could not write to log file ' . $this->file, NOTICE_DEBUG_GROUP);
        }
    }

    public function debug($key, $value = '', $group = 'Custom logs') {
        if (!isset($this->debug[$group])) $this->debug[$group] = array();
        $logs = &$this->debug[$group];

        if (is_array($key)) {
            foreach ($key as $k=>$v) {
                $logs[] = array($k, $v);
            }
        } else {
            $logs[] = array($key, $value);
        }
    }

    public function getDebugGroups() { return array_keys($this->debug); }

    public function getDebugLogs($group) { return isset($this->debug[$group]) ? $this->debug[$group] : array(); }
}

class PermissionDeniedException extends Exception {}
