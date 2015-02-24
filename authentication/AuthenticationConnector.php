<?php
/**
 * AuthenticationConnector
 * 
 * @author André Aleixo <ajaaleixo@gmail.com>
 */
namespace Connectors\Authentication;

use Alpha\Core\Buckets;
use Connectors\Authentication\PasswordUtils;
use Connectors\Authentication\AuthenticationRepositoryInterface;

/**
 * Authentication Connector.
 * 
 * @package AuthenticationConnector
 */
class AuthenticationConnector implements AuthenticationRepositoryInterface 
{
    protected $entitySearchKey, $entityCheckKey, $entityBucket;
    
    /**
     * Authenticates an entity.
     * 
     * @param string $entity   The entity.
     * @param string $password The password. 
     * 
     * @throws \Exception
     * 
     * @return boolean
     */
    public function authenticate($entity, $password)
    {
        return PasswordUtils::isValid($password, $this->retrieveEntity($entity)[$this->entityCheckKey]);
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
        $this->entityBucket    = isset($configuration['entity']['bucket']) ? $configuration['entity']['bucket'] : null;
        $this->entitySearchKey = isset($configuration['entity']['search']) ? $configuration['entity']['search'] : null;
        $this->entityCheckKey  = isset($configuration['entity']['check']) ? $configuration['entity']['check'] : null;
    }
    
    /**
     * Retrieve entity.
     * 
     * @param string $entityName The entity name to retrieve.
     * 
     * @throws \Exception
     * 
     * @return mixed
     */
    public function retrieveEntity($entityName)
    {
        $bucket = Buckets::get($this->entityBucket);
        $entity = $bucket::find(array($this->entitySearchKey => $entityName))->current();
        if ($entity === null) {
            throw new \Exception('Authenticable Entity Not Found');
        }
        return $entity;
    }
}