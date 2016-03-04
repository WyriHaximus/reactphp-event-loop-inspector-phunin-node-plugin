<?php

namespace WyriHaximus\React\Inspector\PhuninNode;

use WyriHaximus\PhuninNode\Configuration;
use WyriHaximus\PhuninNode\Node;
use WyriHaximus\PhuninNode\PluginInterface;
use WyriHaximus\PhuninNode\Value;
use WyriHaximus\React\Inspector\InfoProvider;

class Totals implements PluginInterface
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
        return 'event_loop_totals';
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
        $this->configuration->setPair('graph_category',              'event_loop');
        $this->configuration->setPair('graph_title',                 'Totals');
        $this->configuration->setPair('streams_read_total.label',    'Read Streams');
        $this->configuration->setPair('streams_total_total.label',   'Total Streams');
        $this->configuration->setPair('streams_write_total.label',   'Write Streams');
        $this->configuration->setPair('timers_once_total.label',     'One-off Timers');
        $this->configuration->setPair('timers_periodic_total.label', 'Periodic Timers');
        $this->configuration->setPair('ticks_future_total.label',    'Future ticks');
        $this->configuration->setPair('ticks_next_total.label',      'Next ticks');

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
        $storage->attach(new Value('streams_read_total',    $counters['streams']['read']['total']));
        $storage->attach(new Value('streams_total_total',   $counters['streams']['total']['total']));
        $storage->attach(new Value('streams_write_total',   $counters['streams']['write']['total']));
        $storage->attach(new Value('timers_once_total',     $counters['timers']['once']['total']));
        $storage->attach(new Value('timers_periodic_total', $counters['timers']['periodic']['total']));
        $storage->attach(new Value('ticks_future_total',    $counters['ticks']['future']['total']));
        $storage->attach(new Value('ticks_next_total',      $counters['ticks']['next']['total']));
        return \React\Promise\resolve($storage);
    }
}
