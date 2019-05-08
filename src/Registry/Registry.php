<?php

namespace Utopia\Registry;

use Exception;

class Registry {

    /**
     * @var array
     */
    public $counter = [];

    /**
     * @var array
     */
    public $created = [];

    /**
     * List of all callbacks
     *
     * @var array
     */
    protected $callbacks = array();

    /**
     * List of all connections
     *
     * @var array
     */
    protected $registry = array();

    /**
     * Set a new connection callback
     *
     * @param string $name
     * @param callable $callback
     * @return $this
     * @throws Exception
     */
    public function set(string $name, callable $callback):Registry
    {
        if(array_key_exists($name, $this->callbacks)) {
            throw new Exception('Callback with the name "' . $name . '" already exists');
        }

        $this->counter[$name] = 0;
        $this->created[$name] = 0;

        $this->callbacks[$name] = $callback;

        return $this;
    }

    /**
     * If connection has been created returns it, otherwise create and than return it
     *
     * @param string $name
     * @param bool $fresh
     * @return mixed
     * @throws Exception
     */
    public function get(string $name, $fresh = false)
    {
        if(!array_key_exists($name, $this->registry) || $fresh) {
            if(!array_key_exists($name, $this->callbacks)) {
                throw new Exception('No callback named "' . $name . '" found when trying to create connection');
            }

            $this->created[$name]++;

            $this->registry[$name] = $this->callbacks[$name]();
        }

        $this->counter[$name]++;

        return $this->registry[$name];
    }
}