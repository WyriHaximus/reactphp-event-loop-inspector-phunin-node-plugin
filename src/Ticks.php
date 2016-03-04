<?php

namespace WyriHaximus\React\Inspector\PhuninNode;

use WyriHaximus\PhuninNode\Configuration;
use WyriHaximus\PhuninNode\Node;
use WyriHaximus\PhuninNode\PluginInterface;
use WyriHaximus\PhuninNode\Value;
use WyriHaximus\React\Inspector\InfoProvider;

class Ticks implements PluginInterface
{
    /**
     * @var InfoProvider
     */
    protected $infoProvider;

    /**
     * @var Configuration
     */
    protected $configuration;

    /**
     * Info constructor.
     * @param InfoProvider $infoProvider
     */
    public function __construct(InfoProvider $infoProvider)
    {
        $this->infoProvider = $infoProvider;
    }

    /**
     * {@inheritdoc}
     */
    public function setNode(Node $node)
    {
        $this->node = $node;
    }

    /**
     * {@inheritdoc}
     */
    public function getSlug()
    {
        return 'event_loop_ticks';
    }

    /**
     * {@inheritdoc}
     */
    public function getCategorySlug()
    {
        return 'event_loop';
    }

    /**
     * {@inheritdoc}
     */
    public function getConfiguration()
    {
        if ($this->configuration instanceof Configuration) {
            return \React\Promise\resolve($this->configuration);
        }

        $this->configuration = new Configuration();
        $this->configuration->setPair('graph_category',        'event_loop');
        $this->configuration->setPair('graph_title',           'Callbacks Ticks');
        $this->configuration->setPair('streams_read_ticks',    'Read Stream ticks');
        $this->configuration->setPair('streams_total_ticks',   'Total Stream ticks');
        $this->configuration->setPair('streams_write_ticks',   'Write Stream ticks');
        $this->configuration->setPair('timers_once_ticks',     'One-off Timer ticks');
        $this->configuration->setPair('timers_periodic_ticks', 'Periodic Timer ticks');
        $this->configuration->setPair('ticks_future_ticks',    'Future ticks');
        $this->configuration->setPair('ticks_next_ticks',      'Next ticks');

        return \React\Promise\resolve($this->configuration);
    }

    /**
     * {@inheritdoc}
     */
    public function getValues()
    {
        $counters = $this->infoProvider->getCounters();
        $this->infoProvider->resetTicks();
        $storage = new \SplObjectStorage();
        $storage->attach(new Value('streams_read_ticks',    $counters['streams']['read']['ticks']));
        $storage->attach(new Value('streams_total_ticks',   $counters['streams']['total']['ticks']));
        $storage->attach(new Value('streams_write_ticks',   $counters['streams']['write']['ticks']));
        $storage->attach(new Value('timers_once_ticks',     $counters['timers']['once']['ticks']));
        $storage->attach(new Value('timers_periodic_ticks', $counters['timers']['periodic']['ticks']));
        $storage->attach(new Value('ticks_future_ticks',    $counters['ticks']['future']['ticks']));
        $storage->attach(new Value('ticks_next_ticks',      $counters['ticks']['next']['ticks']));
        return \React\Promise\resolve($storage);
    }
}
