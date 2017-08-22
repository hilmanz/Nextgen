<?php
/**
 * a wrapper for the built in memcache functionality.
 */
class memcache_helper{
	protected $memcache;
	public function connect(){
		global $CONFIG;
		if($this->memcache==NULL){
			$this->memcache = new Memcache();
		}
		return $this->memcache->connect($CONFIG['memcache_host'], $CONFIG['memcache_port']) or die ("Could not connect memcached");
	}
	public function get($name){
		$key = md5($name);
		return $this->memcache->get($key);
	}
	public function set($name,$val,$ttl=0){
		$key= md5($name);
		return $this->memcache->set($key,$val,MEMCACHE_COMPRESSED,$ttl);
	}
	public function close(){
		return $this->memcache->close();
	}
	public function flush(){
		return $this->memcache->flush();
	}
	public function getInstance(){
		return $this->memcache;
	}
	public function delete($key){
		return $this->memcache->delete();
	}
	
}
?>