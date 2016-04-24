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

class Streams implements PluginInterface
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
        return 'event_loop_streams';
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
        $this->configuration->setPair('graph_title', 'Streams');
        $this->configuration->setPair('current_read_streams.label', 'Current Read Streams');
        $this->configuration->setPair('current_write_streams.label', 'Current Write Streams');

        return resolve($this->configuration);
    }

    /**
     * {@inheritdoc}
     */
    public function getValues(): PromiseInterface
    {
        $counters = $this->infoProvider->getCounters();
        $storage = new \SplObjectStorage();
        $storage->attach(new Metric('current_read_streams', (float)$counters['streams']['read']['current']));
        $storage->attach(new Metric('current_write_streams', (float)$counters['streams']['write']['current']));
        return resolve($storage);
    }
}
