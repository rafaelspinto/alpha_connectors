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
