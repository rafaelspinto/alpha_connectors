<?php
/**
 * AuthenticationManagerInterface
 * 
 * @author André Aleixo <ajaaleixo@gmail.com>
 */
namespace Connectors\Authenticationmanager;

use Alpha\Connector\ConnectorInterface;

/**
 * Describes an interface for Authentication Management.
 */
interface AuthenticationManagerInterface extends ConnectorInterface
{
    /**
     * Retrieve authenticated entity details.
     * 
     * @return mixed
     */
    public function getDetails();
    
    /**
     * Saves an authenticated entity.
     * 
     * @param mixed $entity The authenticated entity.
     * 
     * @return mixed
     */
    public function save($entity);

    /**
     * Returns an authenticated state.
     * 
     * @return boolean
     */
    public function isAuthenticated();

    /**
     * Resets the authenticated state.
     * 
     * @return void
     */
    public function reset();
}