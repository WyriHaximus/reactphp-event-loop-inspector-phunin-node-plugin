<?php

use React\EventLoop\Factory;
use React\Socket\Server;
use WyriHaximus\PhuninNode\Node;
use WyriHaximus\React\Inspector\InfoProvider;
use WyriHaximus\React\Inspector\LoopDecorator;
use WyriHaximus\React\Inspector\PhuninNode\Streams;
use WyriHaximus\React\Inspector\PhuninNode\Ticks;
use WyriHaximus\React\Inspector\PhuninNode\Totals;

require dirname(__DIR__) . '/vendor/autoload.php';

// Create eventloop
$loop = new LoopDecorator(Factory::create());

// Create a socket
$socket = new Server($loop);
$socket->listen(12345, '0.0.0.0');

// Bind to IP and port
$node = new Node($loop, $socket);

// Info provider
$infoProvider = new InfoProvider($loop);

// Add plugins
$node->addPlugin(new Streams($infoProvider));
$node->addPlugin(new Ticks($infoProvider));
$node->addPlugin(new Totals($infoProvider));

// Get rolling
$loop->run();