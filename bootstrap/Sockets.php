<?php

namespace bootstrap;
use nikcherr\parser\StringParser;

class Sockets {
    
    public static function run($address = '127.0.0.1', $port = 7777){
        
        $parser = new StringParser();
        set_time_limit(0);
        ob_implicit_flush();
        
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        socket_bind($socket, $address, $port);
        socket_listen($socket, 0);
        $connection = socket_accept($socket);
        
        $msg = "\nConnection ok\n";
        socket_write($connection, $msg, strlen($msg));
        
        $response = '';
        while(true){
            $read = socket_read($connection, 2048, PHP_BINARY_READ);
            $read = trim($read);
            switch ($read){
                
                case 'exit':
                    socket_close($socket);
                    break 2;
                
                default :
                    try{
                        $result = $parser->roundBracket($read);
                        if($result){
                            $response = "Correctly" . PHP_EOL;
                        } else {
                            $response = "Incorrectly" . PHP_EOL;
                        }       
                    }
                    catch(\InvalidArgumentException $e){
                        $response = $e->getMessage() . PHP_EOL;
                    }
                    socket_write($connection, $response);
                    break;  
            } 
        }
    }   
}
