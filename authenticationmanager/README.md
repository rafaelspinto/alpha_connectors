# Authentication Manager Connector
A Simple Authentication Management Connector for Alpha Framework.
With this Connector you can save the entity that you've authenticated, using a session connector that you've defined in plugs folder. After this, you can check the authenticated state for any entity.

## Settings
AuthenticationManager.plug sample is setted to use the SessionConnector and the authenticated entity will be a User.

```
[session]
connector    = "Session"	// The Connector name implementing a SessionInterface
expires      = 0;			// Seconds that an authenticated state can last. 0 if it lasts for 
auth_entity  = "user"		// The authenticated entity name to be identifyed in a Session Connector
```

## Usage
Just move the AuthenticationManager.plug to Webapp/plugs folder and change the settings.

* Using the AuthenticationManagerConnector in a controller to show the authenticated entity, as a user.
```
public function getDetails()
{
    $this->data['user'] = Connectors::get('AuthenticationManager')->getDetails();
}
```
* Using the AuthenticationManagerConnector to check if the entity is authenticated in a controller action.
```
public function postComment($PARAM_comment)
{
  $authConnector = Connectors::get('AuthenticationManager');
  if ($authConnector->isAuthenticated()) {
    // Do your stuff
  } else {
    // Redirect for login page
    Router::redirectTo('/login');
  }
}
```
* Using the AuthenticationManagerConnector in a controller action with the AuthenticationConnector to process a login and save the authenticated user.
```
public function postLogin($PARAM_email, $PARAM_password)
{
  try {
    $authenticator = Connectors::get('Authentication');
    if ($authenticator->authenticate($PARAM_email, $PARAM_password)) {
      // Now that the user is authenticated, lets save it
      Connectors::get('AuthenticationManager')->save($authenticator->retrieveEntity($PARAM_email));
      Router::redirectTo('/dashboard');
    }
    throw new \Exception('invalid_credentials_exception');
  } catch (\Exception $ex) {
    // Handle the exceptions
  }
}
```
