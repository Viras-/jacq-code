<?php
/**
 * Description of JacqImageServer
 *
 * @author wkoller
 */
class JacqImageServer extends jsonRPCClient {
    /**
     *
     * @var string
     */
    private $key = '';
    
    /**
     *
     * @var string 
     */
    private $url = '';
    
    /**
     * Construct new JSON-RPC client by fetching the required properties
     * @param string $imgserver_IP Address of image server
     * @throws Exception 
     */
    public function __construct($p_base_url, $p_key) {
        // Setup properties
        $this->url = $p_base_url . '/jacq-servlet/ImageServer';
        $this->key = $p_key;
        
        // Finally call parent constructor
        parent::__construct($this->url, false);
    }
    
    /**
     * Call a JSON-RPC function, but add key as first parameter
     * @param string $method
     * @param array $params 
     */
    public function __call($method, $params) {
        // Always add key as first parameter
        array_unshift($params, $this->key);
        
        // Finally call the method
        return parent::__call($method, $params);
    }
}
