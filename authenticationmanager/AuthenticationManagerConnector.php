<?php
/**
 * AuthenticationManagerConnector
 * 
 * @author André Aleixo <ajaaleixo@gmail.com>
 */
namespace Connectors\Authenticationmanager;

use Alpha\Core\Connectors;
use Connectors\Authenticationmanager\AuthenticationManagerInterface;

/**
 * Authentication Manager Connector.
 * 
 * @package AuthenticationManagerConnector
 */
class AuthenticationManagerConnector implements AuthenticationManagerInterface 
{
    private $_sessionConnectorName, $_expirationTime, $_authKey;
    private $_prefix         = 'alphauth_';
    private $_loggedSinceKey = 'logged_since';

    /**
     * Returns the auth entity key.
     * 
     * @return string
     */
    private function getAuthEntityKey()
    {
        return $this->_prefix.$this->_authKey;
    }

    /**
     * Returns the auth logged key.
     * 
     * @return string
     */
    private function getAuthLoggedKey()
    {
        return $this->_prefix.$this->_loggedSinceKey;
    }
    /**
     * Retrieve authenticated entity details.
     * 
     * @return mixed
     */
    public function getDetails()
    {
        if ($this->isAuthenticated()) {
            $sessionConnector = Connectors::get($this->_sessionConnectorName);
            return $sessionConnector->get($this->getAuthEntityKey());    
        }
        return [];
    }
    
    /**
     * Saves an authenticated entity.
     * 
     * @param mixed $entity The authenticated entity.
     * 
     * @return mixed
     */
    public function save($entity)
    {
        $sessionConnector = Connectors::get($this->_sessionConnectorName);
        $sessionConnector->set($this->getAuthEntityKey(), $entity);
        $sessionConnector->set($this->getAuthLoggedKey(), time());
    }

    /**
     * Resets the authenticated state.
     * 
     * @return void
     */
    public function reset()
    {
        $sessionConnector = Connectors::get($this->_sessionConnectorName);
        $sessionConnector->destroy();
    }

    /**
     * Returns an authenticated state.
     * 
     * @return boolean
     */
    public function isAuthenticated()
    {
        $sessionConnector   = Connectors::get($this->_sessionConnectorName);
        $authenticatedSince = $sessionConnector->get($this->getAuthLoggedKey());
        if ((int) $authenticatedSince > 0) {
            if ((int) $this->_expirationTime > 0 && ((int) $authenticatedSince + (int) $this->_expirationTime <= time())) {
                // expired authenticated state
                return false;
            }
            return true;
        }
        return false;
    }

    /**
     * Setup the Connector.
     * 
     * @param array $configuration The configuration array.
     * 
     * @return void
     */
    public function setup(array $configuration)
    {
        $this->_sessionConnectorName = isset($configuration['session']['connector']) ? $configuration['session']['connector'] : null;
        $this->_expirationTime       = isset($configuration['session']['expires']) ? $configuration['session']['expires'] : null;
        $this->_authKey              = isset($configuration['session']['auth_entity']) ? $configuration['session']['auth_entity'] : null;
    }
}