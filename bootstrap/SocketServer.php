<?php

namespace bootstrap;

class SocketServer {

    protected $socket;
    protected $address;
    protected $port;
    protected $handler;

    public function __construct($address = '127.0.0.1', $port = 7777) {
        $this->address = $address;
        $this->port = $port;
    }

    protected function createSocket() {
        $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    }

    protected function bindSocket() {
        socket_bind($this->socket, $this->address, $this->port);
    }

    public function setHandler($nameOfFunction) {
        $this->handler = $nameOfFunction;
    }

    public function init() {
        $this->createSocket();
        $this->bindSocket();
    }

    public function listen() {
        socket_listen($this->socket, 0);
        printf("listening on %s:%d...\n", $this->address, $this->port);
        $this->listening();
        socket_close($this->socket);
    }

    protected function listening() {
        while (true) {
            $connection = socket_accept($this->socket);
            $client = new SocketClient($connection);
            $function = $this->handler;
            $function($client);
        }
    }
}