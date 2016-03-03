<?php

namespace WyriHaximus\React\Inspector\PhuninNode;

use WyriHaximus\PhuninNode\Configuration;
use WyriHaximus\PhuninNode\Node;
use WyriHaximus\PhuninNode\PluginInterface;
use WyriHaximus\PhuninNode\Value;
use WyriHaximus\React\Inspector\InfoProvider;

class Stream implements PluginInterface
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
        return 'streams';
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
        $this->configuration->setPair('graph_category', 'event-loop');
        $this->configuration->setPair('graph_title', 'Streams');
        $this->configuration->setPair('current_read_streams', 'Current Read Streams');
        $this->configuration->setPair('current_write_streams', 'Current Write Streams');

        return \React\Promise\resolve($this->configuration);
    }

    /**
     * {@inheritdoc}
     */
    public function getValues()
    {
        $counters = $this->infoProvider->getCounters();
        $storage = new \SplObjectStorage();
        $storage->attach(new Value('current_read_streams', $counters['streams']['read']['current']));
        $storage->attach(new Value('current_write_streams', $counters['streams']['write']['current']));
        return \React\Promise\resolve($storage);
    }
}
