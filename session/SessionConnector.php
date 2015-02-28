<?php
/**
 * SessionConnector
 *
 * @author André Aleixo <ajaaleixo@gmail.com>
 */
namespace Connectors\Session;

use Connectors\Session\SessionInterface;

/**
 * Session Connector.  
 */
class SessionConnector implements SessionInterface
{
    protected $regenerateEachRequest, $deleteOnRegenerate;
    protected $name, $lifetime, $path, $domain, $secure, $httponly;

    /**
     * Initializes session.
     * 
     * @throws \Exception
     * 
     * @return boolean
     */
    private function init()
    {
        // Check PHP Session status
        switch (session_status()) {
            case PHP_SESSION_ACTIVE : 
                $ok = ($this->regenerateEachRequest ? session_regenerate_id($this->deleteOnRegenerate) : session_id()) ? true : false;
                break;
            case PHP_SESSION_NONE :
                $ok = $this->start();
                break;
            case PHP_SESSION_DISABLED :
                throw new \Exception('session_disabled');
        }
        if (!$ok) {
            throw new \Exception('session_init_failed');
        }
        return $ok;
    }

    /**
     * Session start.
     * 
     * @return boolean
     */
    private function start()
    {
        session_set_cookie_params($this->lifetime, $this->path, $this->domain, $this->secure, $this->httponly);
        session_name($this->name);
        session_start();
        return session_id() ? true : false;
    }

    /**
     * Stores a key value pair.
     * 
     * @param string $key   The key.
     * @param mixed  $value The value.
     * 
     * @throws \Exception
     * 
     * @return mixed
     */
    public function set($key, $value)
    {
        if ($this->init()) {
            if (strlen($key)>0) {
                return $_SESSION[$key] = $value;
            }
            throw new \Exception('session_set_failed_empty_key');
        }
    }

    /**
     * Returns a key.
     * 
     * @param string $key   The key.
     * 
     * @throws \Exception
     * 
     * @return void
     */
    public function get($key)
    {
        if ($this->init()) {
            if(isset($_SESSION[$key])) {
                return $_SESSION[$key];
            }
        }
    }

    /**
     * Resets a key on a complete session.
     * 
     * @throws \Exception
     * 
     * @return void
     */
    public function reset($key)
    {
        if ($this->init()) {
            if (isset($key)) {
                unset($_SESSION[$key]);
                return;
            }
            throw new \Exception('session_reset_failed_empty_key');
        }
    }

    /**
     * Clear the entire session.
     * 
     * @throws \Exception
     * 
     * @return boolean
     */
    public function clear()
    {
        if ($this->init()) {
            $_SESSION = array();
            return empty($_SESSION);
        }
        return false;
    }

    /**
     * Destroys a session.
     * 
     * @return boolean
     */
    public function destroy()
    {
        if ($this->init()) {
            $_SESSION = array();
            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000,
                    $params["path"], $params["domain"],
                    $params["secure"], $params["httponly"]
                );
            }
            return session_destroy();
        }
        return false;
    }

    /**
     * Dumps session content.
     * 
     * @throws \Exception
     * 
     * @return mixed
     */
    public function dump($print = true)
    {
        if ($this->init()) {
            if ($print) {
                print '<pre>'.nl2br(print_r($_SESSION, true)).'</pre>';
                return;
            }
            return $_SESSION;
        }
    }

    /**
     * Sets the configuration.
     * 
     * @param array $configuration The array containing the connector configuration.
     * 
     * @return void
     */
    public function setup(array $configuration)
    {
        // defaults
        $this->regenerateEachRequest = false;
        $this->deleteOnRegenerate    = false;
        $this->name                  = 'alpha';
        $this->lifetime              = 0;
        $this->path                  = '/';
        $this->domain                = '';
        $this->secure                = false;
        $this->httponly              = false;

        if (isset($configuration['regenerate']['each_request'])) {
            $this->regenerateEachRequest = (bool) $configuration['regenerate']['each_request'];
        }
        if (isset($configuration['regenerate']['delete_old'])) {
            $this->deleteOnRegenerate = (bool) $configuration['regenerate']['delete_old'];
        }
        if (isset($configuration['session']['name'])) {
            $this->name = $configuration['session']['name'];
        }
        if (isset($configuration['session']['lifetime'])) {
            $this->lifetime = time() + (int) $configuration['session']['lifetime'];
        }
        if (isset($configuration['session']['path'])) {
            $this->path = $configuration['session']['path'];
        }
        if (isset($configuration['session']['domain'])) {
            $this->domain = $configuration['session']['domain'];
        }
        if (isset($configuration['session']['secure'])) {
            $this->secure = (bool) $configuration['session']['secure'];
        }
        if (isset($configuration['session']['httponly'])) {
            $this->httponly = (bool) $configuration['session']['httponly'];
        }
    }
}
