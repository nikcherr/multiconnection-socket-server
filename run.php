#!/usr/bin/php
<?php
require_once 'vendor/autoload.php';

use bootstrap\SocketServer;
use nikcherr\parser\StringParser;

function onConnect($client) {
    $pid = pcntl_fork();

    if ($pid == -1) {
        die('could not fork');
    } else if ($pid) {
        // parent process
        return;
    }
    $msg = "Connection is OK" . PHP_EOL . "Enter a string from the brackets:" . PHP_EOL;
    $client->send($msg);
    
    printf("Create child handler with pid = %d\n", getmypid());
    $parser = new StringParser();
    $response = '';
    while (true) {
        $read = trim($client->read());
        switch ($read) {
            case 'exit':
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
    printf("Child [pid = %d] disconnect\n", getmypid());
    $client->close();
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

/*
$fh = fopen("text.txt", "r");
var_dump(posix_getpid());
sleep(10);
fclose($fh);
sleep(200);
*/
/*
while($f = fgets(STDIN)){
    echo "line: $f";
}
*/
 