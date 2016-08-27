<?php
declare(strict_types=1);

namespace WyriHaximus\React\Inspector\PhuninNode;

use React\Promise\PromiseInterface;
use WyriHaximus\PhuninNode\Configuration;
use WyriHaximus\PhuninNode\Metric;
use WyriHaximus\PhuninNode\Node;
use WyriHaximus\PhuninNode\PluginInterface;
use WyriHaximus\React\Inspector\InfoProvider;
use function React\Promise\resolve;

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
    public function getSlug(): string
    {
        return 'event_loop_totals';
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
    public function getCapabilities(): array
    {
        return [];
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
        $this->configuration->setPair('graph_title', 'Totals');
        $this->configuration->setPair('streams_read_total.label', 'Read Streams');
        $this->configuration->setPair('streams_total_total.label', 'Total Streams');
        $this->configuration->setPair('streams_write_total.label', 'Write Streams');
        $this->configuration->setPair('timers_once_total.label', 'One-off Timers');
        $this->configuration->setPair('timers_periodic_total.label', 'Periodic Timers');
        $this->configuration->setPair('ticks_future_total.label', 'Future ticks');
        $this->configuration->setPair('ticks_next_total.label', 'Next ticks');

        return resolve($this->configuration);
    }

    /**
     * {@inheritdoc}
     */
    public function getValues(): PromiseInterface
    {
        $counters = $this->infoProvider->getCounters();
        $this->infoProvider->resetTotals();
        $storage = new \SplObjectStorage();
        $storage->attach(new Metric('streams_read_total', (float)$counters['streams']['read']['total']));
        $storage->attach(new Metric('streams_total_total', (float)$counters['streams']['total']['total']));
        $storage->attach(new Metric('streams_write_total', (float)$counters['streams']['write']['total']));
        $storage->attach(new Metric('timers_once_total', (float)$counters['timers']['once']['total']));
        $storage->attach(new Metric('timers_periodic_total', (float)$counters['timers']['periodic']['total']));
        $storage->attach(new Metric('ticks_future_total', (float)$counters['ticks']['future']['total']));
        $storage->attach(new Metric('ticks_next_total', (float)$counters['ticks']['next']['total']));
        return resolve($storage);
    }
}
