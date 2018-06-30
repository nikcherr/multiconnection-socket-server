<?php

namespace bootstrap;

class SocketClient {
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
