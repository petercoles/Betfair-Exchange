# Betfair Exchange API Wrapper

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/1b24740e-5b91-467e-8d44-3a2c158fafaa/mini.png)](https://insight.sensiolabs.com/projects/1b24740e-5b91-467e-8d44-3a2c158fafaa)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/petercoles/Betfair-Exchange/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/petercoles/Betfair-Exchange/?branch=master)
[![License](http://img.shields.io/:license-mit-blue.svg)](http://doge.mit-license.org)


## Introduction

This is a simple wrapper to the Betfair Exchange API. It's main role is to simplify the initiation and maintenance of Betfair sessions. It requires that you understand the requests that the Betfair API accepts and the parameters that each uses. These can be found on the [Betfair Developer Site](http://docs.developer.betfair.com/docs). These can be a bit daunting at first, but when used for a while, the underlying structure becomes clear.

If this isn't to your taste, then you might like to consider [Daniel D'Angeli's full blooded package](https://github.com/danieledangeli/betfair-php) or [Dan Cotora's very lightweight wrapper](https://github.com/dcro/simple-betfair-php-api).


## Installation

At the command line run

```
composer require petercoles/betfair-exchange
```


## Usage

### Authenticating a Single Process (simple)

Currently the package has been tested against the UK exchange only. Most methods are now in place, but order placement and similar still need testing.

You will need to have a [Betfair developer account](https://developer.betfair.com/) with that Exchange and have obtained a APP_KEY from it - there's no charge for this, or indeed using the API for personal use.

Each Betfair request starts with a static call to one of the packages subsystems. There are three of these:  "auth", "account" and "betting".

All requests require to you be logged in to the Betfair Exchange, otherwise it won't talk to you. So your first call will always be:
```
Betfair::init(string <app-key>, string <username>, string <password>);
```
On a first call it will store your APP_KEY, then login and retrieve a SESSION_TOKEN and finally store that token. The app key and session token will be used to authenticate all subsequent requests. It's safe, indeed recommended, to call init() before each group of requests. If there's an active session already, it will simply extend this session rather than logging in again.

Authentication information will only exist for the current process (i.e. web page request or CLI command) has completed, but will be available for as many Betfair accesses as you attempt within that request.

### Authenticating Multiple Processes (recommended)

To share the the authentication credentials across multiple requests, e.g. avoid the need to login on each ajax request, the package offers the following options:
```
string Betfair::auth()->persist(string <app-key>, string <session-token>);
string Betfair::auth()->login(string <app-key>, string <username>, string <password>);
```
This assumes that you are storing the credentials in a PHP session, a cache or a database, indeed anywhere where it can be accessed by a request and passed as a parameter to this method.

An effective approach for this would be:
```
try {
    // persist Betfair session, injecting Betfair session token (or NULL)
    $token = Cache::get('betfairToken', null);
    Betfair::auth()->persist(<app-key>, $token);
} catch (Exception $e) {
    // or retrieve new Betfair session token and 
    $token = Betfair::auth()->login(<app-key>, <username>, <password>);
    Cache::put('betfairToken', $token, \PeterColes\Betfair\Api\Auth::SESSION_LENGTH);
}
```
(this example uses Laravel's caching features - substitute an approach appropriate for your application).

If a null session token is received, the package will not make a call to Betafir. Instead it will immediately throw an exception that can be caught (as in the example above) to login and obtain a token that can be persisted for subsequent requests. This can be useful for the first request, or subsequent requests where the token may have expired.

Three other authentication methods are available, though it's unlikely that you'll need to use them
```
. logout();
. keepAlive();
. sessionremaining();
```

If you're manually managing your Betfair sessions, you could use the keep alive method to extend your Betfair session, though the persist method described above is usually a better soltion. Betfair sessions for the UK exchange are currently 4 hours long.

### Obtaining Data

Once authenticated, betting information can be obtained from the betting subsystem. All calls have the same structure:
```
Betfair::betting(string <name-of-method>, array <params-for-method>);

```
The available methods and their parameters, mandatory or optional, are defined in the [Betfair API documentation](https://developer.betfair.com/exchange-api/).

Account API calls follow the same pattern:
```
Betfair::account(string <name-of-method>, array <params-for-method>);
```


## Example

To make this real, here's a simple example, where we'll first initialise a connection to the API and then request a list of all current events listed for the Italian Serie A soccer league:

```
Betfair::init('BetfairAppKeyHere', 'you@example.com', 'your-password');
$events = Betfair::betting('listEvents', ['filter' => ['textQuery' => 'Serie A']]);
```


## Testing

The package comes with two test suites (or at least soon will). The "unit" test suite (when written) simulate http activity (i.e. won't hit the Betfair servers), however the "integration" test suite will test connectivity and the acceptability of requests so will need valid credentials. These should be placed in a file called .env.php which can be created by copying the .env.example.php file in the package's root folder.

To run the test suites:
```
phpunit --testsuite=unit (coming soon)
phpunit --testsuite=integration
```

It's recommended that you only run the tests via the test suites, as some tests are deliberately excluded to avoid unintended placement of orders or movement of funds.


## Issues

This package was developed to meet a specific need and then generalised for wider use. If you have a use case not currently met, or see something that appears to not be working correctly, please raise an issue at the [github repo](https://github.com/petercoles/betfair-exchange/issues).


## License

This package is licensed under the [MIT license](http://opensource.org/licenses/MIT).
