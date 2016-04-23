<?php

namespace WyriHaximus\React\Inspector\PhuninNode;

use React\Promise\PromiseInterface;
use WyriHaximus\PhuninNode\Configuration;
use WyriHaximus\PhuninNode\Metric;
use WyriHaximus\PhuninNode\Node;
use WyriHaximus\PhuninNode\PluginInterface;
use WyriHaximus\React\Inspector\InfoProvider;
use function React\Promise\resolve;

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
    public function getSlug(): string
    {
        return 'event_loop_ticks';
    }

    /**
     * {@inheritdoc}
     */
    public function getCategorySlug(): string
    {
        return 'event_loop';
    }

    /**
     * {@inheritdoc}
     */
    public function getConfiguration(): PromiseInterface
    {
        if ($this->configuration instanceof Configuration) {
            return resolve($this->configuration);
        }

        $this->configuration = new Configuration();
        $this->configuration->setPair('graph_category', 'event_loop');
        $this->configuration->setPair('graph_title', 'Callbacks Ticks');
        $this->configuration->setPair('streams_read_ticks.label', 'Read Stream ticks');
        $this->configuration->setPair('streams_total_ticks.label', 'Total Stream ticks');
        $this->configuration->setPair('streams_write_ticks.label', 'Write Stream ticks');
        $this->configuration->setPair('timers_once_ticks.label', 'One-off Timer ticks');
        $this->configuration->setPair('timers_periodic_ticks.label', 'Periodic Timer ticks');
        $this->configuration->setPair('ticks_future_ticks.label', 'Future ticks');
        $this->configuration->setPair('ticks_next_ticks.label', 'Next ticks');

        return resolve($this->configuration);
    }

    /**
     * {@inheritdoc}
     */
    public function getValues(): PromiseInterface
    {
        $counters = $this->infoProvider->getCounters();
        $this->infoProvider->resetTicks();
        $storage = new \SplObjectStorage();
        $storage->attach(new Metric('streams_read_ticks', $counters['streams']['read']['ticks']));
        $storage->attach(new Metric('streams_total_ticks', $counters['streams']['total']['ticks']));
        $storage->attach(new Metric('streams_write_ticks', $counters['streams']['write']['ticks']));
        $storage->attach(new Metric('timers_once_ticks', $counters['timers']['once']['ticks']));
        $storage->attach(new Metric('timers_periodic_ticks', $counters['timers']['periodic']['ticks']));
        $storage->attach(new Metric('ticks_future_ticks', $counters['ticks']['future']['ticks']));
        $storage->attach(new Metric('ticks_next_ticks', $counters['ticks']['next']['ticks']));
        return resolve($storage);
    }
}
