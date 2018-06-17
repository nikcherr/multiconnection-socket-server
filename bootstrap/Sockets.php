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
        
        
        while(true){
            $read = socket_read($connection, 2048, PHP_BINARY_READ);
            if($parser->roundBracket($read)){
                $result = "Correctly\n";
            }
            else{
                $result = "Incorrectly\n";
            }
            socket_write($connection, $result);
        }
        socket_close($socket);
    }   
}
