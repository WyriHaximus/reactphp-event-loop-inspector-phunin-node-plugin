<?php

namespace WyriHaximus\React\Tests\Inspector\PhuninNode;

use Phake;
use WyriHaximus\PhuninNode\Tests\Plugins\AbstractPluginTest;
use WyriHaximus\React\Inspector\InfoProvider;
use WyriHaximus\React\Inspector\PhuninNode\Ticks;

class TicksTest extends AbstractPluginTest
{
    public function setUp()
    {
        $infoProvider = Phake::mock(InfoProvider::class);
        Phake::when($infoProvider)->getCounters()->thenReturn([
            'streams' => [
                'read' => [
                    'min'       => 0,
                    'current'   => 0,
                    'max'       => 0,
                    'total'     => 0,
                    'ticks'     => 0,
                ],
                'total' => [
                    'min'       => 0,
                    'current'   => 0,
                    'max'       => 0,
                    'total'     => 0,
                    'ticks'     => 0,
                ],
                'write' => [
                    'min'       => 0,
                    'current'   => 0,
                    'max'       => 0,
                    'total'     => 0,
                    'ticks'     => 0,
                ],
            ],
            'timers' => [
                'once' => [
                    'current'   => 0,
                    'total'     => 0,
                    'ticks'     => 0,
                ],
                'periodic' => [
                    'current'   => 0,
                    'total'     => 0,
                    'ticks'     => 0,
                ],
            ],
            'ticks' => [
                'future' => [
                    'current'   => 0,
                    'total'     => 0,
                    'ticks'     => 0,
                ],
                'next' => [
                    'current'   => 0,
                    'total'     => 0,
                    'ticks'     => 0,
                ],
            ],
        ]);
        $this->plugin = new Ticks($infoProvider);

        parent::setUp();

        $this->node->addPlugin($this->plugin);
    }
}
