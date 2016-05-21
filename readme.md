# Betfair Exchange API Wrapper

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/1b24740e-5b91-467e-8d44-3a2c158fafaa/mini.png)](https://insight.sensiolabs.com/projects/1b24740e-5b91-467e-8d44-3a2c158fafaa)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/petercoles/Betfair-Exchange/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/petercoles/Betfair-Exchange/?branch=master)
[![License](http://img.shields.io/:license-mit-blue.svg)](http://doge.mit-license.org)


## Introduction

*This is a pre-alpha package* designed to provide a simple interface to the Betfair Exchange API. It's not yet suitable for production use and its API will change (guaranteed).

If you're looking for something usable now, try [Daniel D'Angeli's full blooded package](https://github.com/danieledangeli/betfair-php) or [Dan Cotora's very lightweight wrapper](https://github.com/dcro/simple-betfair-php-api).


## Installation

At the command line run

```
composer require petercoles/betfair-exchange
```


## Usage

Currently the package has been tested against the UK exchange only. Most methods are now in place, but order placement and similar still need testing.

You will need to have a [Betfair developer account](https://developer.betfair.com/) with that Exchange and have obtained a APP_KEY from it - there's no charge for this, or indeed using the API for personal use.

Each Betfair request starts with a static call to one of the packages subsystems. There are three of these:  "auth", "account" and "betting".

All requests require to you be logged in to the Betfair Exchange, otherwise it won't talk to you. So your first call will always be:
```
Betfair::init(string <app-key>, string <username>, string <password>);
```
On a first call it will store your APP_KEY, then login and retrieve a SESSION_TOKEN and finally stire that token. The app key and session token will be used to authenticate all subsequent requests. It's safe, indeed recommended, to call init() before each group of requests. If there's an active session already, it will simply extend this session rather than logging in again.

Four other authentication methods are available, though it's unlikely that you'll need to use them
```
. login(string <app-key>, string <username>, string <password>);
. logout();
. keepAlive();
. sessionremaining();
```
If you're manually managing your sessions (why?), use the keep alive method to extend your Betfair session. Betfair sessions for the UK exchange are currently 4 hours long.

Betting information can be obtained from the betting subsystem. All calls have the same structure:
```
Betfair::betting(string <name-of-method>, array <params-for-method>);

```
The available methods and their parameters, mandatory or optional, are defined in the [Betfair API documentation](https://developer.betfair.com/exchange-api/).

Account API calls follow the same pattern:
```
Betfair::account(string <name-of-method>, array <params-for-method>);
```

## Testing

The package comes with two test suite (or at least soon will). The "unit" test suite will simulate http activity (i.e. won't hit the Betfair servers), however the "integration" test suite will test connectivity and the acceptability of requests so will need valid credentials. These should be placed in a file called .env.php which can be created by copying the .env.example.php file in the package's root folder.

To run the test suites:
```
phpunit --testsuite=unit
phpunit --testsuite=integration
```

It's recommended that you only run the tests via the test suites, as some tests are deliberately excluded to avoid unintended placement of orders or moving funds around.

## Issues

This package was developed to meet a specific need and then generalised for wider use. If you have a use case not currently met, or see something that appears to not be working correctly, please raise an issue at the [github repo](https://github.com/petercoles/betfair-exchange/issues).


## License

This package is licensed under the [MIT license](http://opensource.org/licenses/MIT).
