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

Currently the package supports only the UK exchange and a very limited (but growing) range of requests for that exchange. You will need to have a [Betfair developer account](https://developer.betfair.com/) with that Exchange and have obtained a APP_KEY from it - there's no charge for this, or indeed using the API generally.

Each Betfair request starts with a static call to one of the packages subsystems. Currently there are two: "auth" and "betting".

All requests require to you be logged in to the Betfair Exchange, otherwise it won't talk to you. So your first call will always be:
```
$sessionToken = Betfair::auth()->init(<YOUR_APP_KEY>, <YOUR_USERNAME>, <YOUR_PASSWORD>);
```
On a first call it will store your APP_KEY, login, retrieve a SESSION_TOKEN and store it. the session token will be used to authenticate all subsequent requests. It's safe, indeed recommended, to call init() before each group of requests. If there's an active session already, it will simply extend this session rather than logging in again.

Four other authentication methods are available, though its unlikely that you'll need to use them
```
. login(<YOUR_APP_KEY>, <YOUR_USERNAME>, <YOUR_PASSWORD>);
. logout();
. keepAlive();
. sessionremaining();
```
The Keep alive method extends your Betfair session. Betfair sessions for the UK exchange are currently 4 hours long, so usually just logging in is good enough.

Betting information can be obtained from the betting subsystem. Currently only the following methods are supported (which is of very limited practical use), but more are coming
```
. listCompetitions(<ARRAY_OF_FILTERS>, <LOCALE>);
. listCountries(<ARRAY_OF_FILTERS>, <LOCALE>);
. listEvents(<ARRAY_OF_FILTERS>, <LOCALE>);
. listEventTypes(<ARRAY_OF_FILTERS>, <LOCALE>);
. listMarketTypes(<ARRAY_OF_FILTERS>, <LOCALE>);
. listVenues(<ARRAY_OF_FILTERS>, <LOCALE>);
```
Both the parameters are optional.

## Testing

The package comes with a test suite. Some of the tests will simulate http activity (i.e. won't hit the Betfair servers), but some will test connectivity and will need valud credentials. These should be placed in a file called .env.php which can be created by copying the .env.example.php file in the package's root folder.


## Issues

This package was developed to meet a specific need and then generalised for wider use. If you have a use case not currently met, or see something that appears to not be working correctly, please raise an issue at the [github repo](https://github.com/petercoles/betfair-exchange/issues).


## License

This package is licensed under the [MIT license](http://opensource.org/licenses/MIT).
