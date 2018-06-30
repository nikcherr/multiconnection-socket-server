<?php

namespace bootstrap;

use nikcherr\parser\StringParser;

class Client {

    private $address;
    private $port;
    private $connection;

    public function __construct($connection) {
        socket_getsockname($connection, $address, $port);
        $this->address = $address;
        $this->port = $port;
        $this->connection = $connection;
        $this->listenLoop = false;
    }

    public function read() {
        return socket_read($this->connection, 2048, PHP_BINARY_READ);
    }

    public function send($msg) {
        socket_write($this->connection, $msg, strlen($msg));
    }

    public function close() {
        socket_shutdown($this->connection);
        socket_close($this->connection);
    }

}

class Sockets {

    public static function run($address = '127.0.0.1', $port = 7777) {

        $parser = new StringParser();
        set_time_limit(0);
        ob_implicit_flush();

        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        socket_bind($socket, $address, $port);
        socket_listen($socket, 0);


        while (true) {
            $connection = socket_accept($socket);
            $client = new Client($connection);

            $pid = pcntl_fork();
            if ($pid == -1) {
                die('can not fork');
            } elseif ($pid == 0) {        
                $msg = "\nConnection ok\n";
                $client->send($msg);
                
                printf("child pid = %d parent pid = %d\n", getmypid(), posix_getppid());
                
                $response = '';
                while (true) {
                    $read = $client->read();
                    $read = trim($read);
                    switch ($read) {

                        case 'exit':
                            $client->close();
                            break 2;

                        default :
                            try {
                                $result = $parser->roundBracket($read);
                                if ($result) {
                                    $response = "Correctly" . PHP_EOL;
                                } else {
                                    $response = "Incorrectly" . PHP_EOL;
                                }
                            } catch (\InvalidArgumentException $e) {
                                $response = $e->getMessage() . PHP_EOL;
                            }
                            $client->send($response);
                            break;
                    }
                }
            }
        }
    }

}
