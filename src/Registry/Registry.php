<?php

namespace Utopia\Registry;

use Exception;

class Registry {

    /**
     * List of all callbacks
     *
     * @var callable[]
     */
    protected $callbacks = [];

    /**
     * List of all connections
     *
     * @var array
     */
    protected $registry = [];

    /**
     * Set a new connection callback
     *
     * @param string $name
     * @param callable $callback
     * @return $this
     * @throws Exception
     */
    public function set(string $name, callable $callback): self
    {
        if(\array_key_exists($name, $this->callbacks)) {
            throw new Exception('Callback with the name "' . $name . '" already exists');
        }

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
        if(!\array_key_exists($name, $this->registry) || $fresh) {
            if(!\array_key_exists($name, $this->callbacks)) {
                throw new Exception('No callback named "' . $name . '" found when trying to create connection');
            }

            $this->registry[$name] = $this->callbacks[$name]();
        }

        return $this->registry[$name];
    }
}