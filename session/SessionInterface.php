<?php
/**
 * SessionInterface
 * 
 * @author André Aleixo <ajaaleixo@gmail.com>
 */
namespace Connectors\Session;

use Alpha\Connector\ConnectorInterface;

/**
 * Describes an interface for Session Connectors.
 */
interface SessionInterface extends ConnectorInterface
{
    /**
     * Stores a key value pair.
     * 
     * @param string $key   The key.
     * @param mixed  $value The value.
     * 
     * @return void
     */
    public function set($key, $value);
    
    /**
     * Retrieve a value for a key.
     * 
     * @param string $key The key name.
     * 
     * @return mixed
     */
    public function get($key);

    /**
     * Destroys a session.
     * 
     * @return boolean
     */
    public function destroy();

    /**
     * Resets a key.
     * 
     * @throws \Exception
     * 
     * @return void
     */
    public function reset($key);

    /**
     * Resets the entire session.
     * 
     * @throws \Exception
     * 
     * @return boolean
     */
    public function clear();
}