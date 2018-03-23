<?php

class Cache {

    private static $_instance;

    private function __construct(){
        //set cache conntect
        $this->_instance = new Memcache;
        $this->_instance->connect(MEMCACHED_ADDR, MEMCACHED_PORT);
    }

    public static function getInstance() {
        if(!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function set($key, $value, $expire = 600) {
        return $this->_instance->set($key, $value, MEMCACHE_COMPRESSED, $expire);
    }

    public function get($key) {
        return $this->_instance->get($key);
    }

    public function delete($key) {
        return $this->_instance->delete($key);
    }

}
