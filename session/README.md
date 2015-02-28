# Session Connector
A Simple PHP Session Connector for Alpha Framework.

## Settings
Session.plug sample is setted to Not regenerate and to Not delete on each request.

```
[regenerate]
each_request = 0 		// Disabled: 0 Enabled: 1
delete_old   = 0 		// Disabled: 0 Enabled: 1
[session]
name     = 'alpha_session' 	// Change this to anything to identify your session name
expires  = 3600			// Number of seconds that a session can exist
path  	 = '/'			// The session path
domain   = '.alpha.dev'		// The session domain. Change regarding your domain
secure   = 0			// Use PHP secure param. Disabled: 0 Enabled: 1
httponly = 0			// Use PHP HttpOnly param.  Disabled: 0 Enabled: 1
```

## Usage
Just move the Session.plug to Webapp/plugs folder and change the settings.

* Using the SessionConnector in a Controller Action to save some content.
```
function getComment($PARAM_comment)
{
  Connectors::get('Session')->set('last_comment', $PARAM_comment);
  // Do your stuff because we've saved the comment information on session using 'last_comment' key
}
```

* Using the SessionConnector in a Controller Action while you want to clear the entire session.
```
function get()
{
  // you stuff
  Connectors::get('Session')->clear();
}
```
