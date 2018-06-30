<?php

namespace bootstrap;

use nikcherr\parser\StringParser;

class SocketServer {

    public static function run($address = '127.0.0.1', $port = 7777) {

        $parser = new StringParser();
        set_time_limit(0);
        ob_implicit_flush();

        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        socket_bind($socket, $address, $port);
        socket_listen($socket, 0);
        printf("Listening on %s:%d...\n", $address, $port);

        while (true) {
            $connection = socket_accept($socket);
            $client = new SocketClient($connection);

            $pid = pcntl_fork();
            if ($pid == -1) {
                die('can not fork');
            } elseif ($pid == 0) {        
                $msg = PHP_EOL . "Connection ok" . PHP_EOL;
                $client->send($msg);
                
                printf("Create child [pid = %d]\n", getmypid());
                
                $response = '';
                while (true) {
                    $read = $client->read();
                    $read = trim($read);
                    
                    switch ($read) {

                        case 'exit':
                            printf("Child [pid = %d] disconnect\n", getmypid());
                            $client->close();
                            break 2;

                        default :
                            try {
                                $result = $parser->roundBracket($read);
                                if ($result) {
                                    $response = "String id correctly" . PHP_EOL;
                                } else {
                                    $response = "String is incorrectly" . PHP_EOL;
                                }
                            } catch (\InvalidArgumentException $e) {
                                $response = $e->getMessage() . PHP_EOL;
                            }
                            $client->send($response);
                            break;
                    }
                    printf("Child [pid = %d] say '%s'. Answer: %s", getmypid(), $read, $response);
                }
            }
        }
        exit(1);
    }
}
