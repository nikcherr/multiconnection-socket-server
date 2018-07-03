#!/usr/bin/php
<?php
require_once 'vendor/autoload.php';

use bootstrap\SocketServer;
use nikcherr\parser\StringParser;

function onConnect($client) {

    $pid = pcntl_fork();
    $child_processes = [];
    if ($pid == -1) {
        die('could not fork');
    } else if ($pid) {
        $child_processes[$pid] = true;
    } else {
        $read = '';
        printf("Create child handler with pid = %d\n", getmypid());
        $parser = new StringParser();

        while (true) {
            $read = trim($client->read());
            if (preg_replace('/[^a-z]/', '', $read) == 'exit') {
                break;
            } elseif ($read != '') {
                try {
                    if ($result = $parser->roundBracket($read)) {
                        $response = "String is correctly" . PHP_EOL;
                    } else {
                        $response = "String is incorrectly" . PHP_EOL;
                    }
                } catch (\InvalidArgumentException $e) {
                    $response = $e->getMessage() . PHP_EOL;
                }
                $client->send($response);
                printf("Child [pid = %d] say '%s'. Answer: %s", getmypid(), $read, $response);
            } else {
                $client->send("String is empty");
            }
        }
        $client->close();
        printf("Child [pid = %d] disconnect\n", getmypid());
        exit(0);
    }
    pcntl_signal(SIGCHLD, SIG_IGN);
}

error_reporting(E_ALL);
$opt = bootstrap\Command::getOptsByName('address', 'port');
if ($opt) {
    $server = new SocketServer($opt['address'], $opt['port']);
    $server->init();
    $server->setHandler('onConnect');
    $server->listen();
} else {
    echo 'Введите адрес и порт.' . PHP_EOL;
} 