# Betfair Exchange API Wrapper

## Introduction

*This is a pre-alpha package* designed to provide a simple interface to the Betfair Exchange API. It's not yet suitable for production use and its API will change (guaranteed).

If you're looking for something usable now, try [Daniel D'Angeli's full blooded package](github.com/danieledangeli/betfair-php) or [Dan Cotora's very lightweight wrapper](github.com/dcro/simple-betfair-php-api).


## Installation

At the command line run

```
composer require petercoles/betfair-exchange
```


## Usage

Currently the package supports only the UK exchange and a very limited (but growing) range of requests for that exchange. You will need to have a [Betfair developer account](https://developer.betfair.com/) with that Exchange and have obtained a APP_KEY from it - there's no charge for this, or indeed using the API generally.

Each Betfair request starts with a static call to one of the packages subsystems. Currently there are two: "auth" and "betting".

All requests require to you be logged in to the Betfair Exchange, otherwise it won't talk to you. Currently your application has to do this manually, though that's likely to change in later versions of the package.

So your first call will always be
```
$sessionToken = Betfair::auth()->login(<YOUR_APP_KEY>, <YOUR_USERNAME>, <YOUR_PASSWORD>);
```
All future calls will require your APP_KEY and this session token (though again, that's likely to change fairly soon).

Three authentication methods are supplied:
```
. login(<YOUR_APP_KEY>, <YOUR_USERNAME>, <YOUR_PASSWORD>);
. logout(<YOUR_APP_KEY>, <YOUR_SESSION_TOKEN>);
. keepAlive(<YOUR_APP_KEY>, <YOUR_SESSION_TOKEN>);
```
The Keep alive method extends your Betfair session. Betfair sessions for the UK exchange are currently 4 hours long, so usually just logging in is good enough.

Betting information can be obtained from the betting subsystem. Currently only the following methods are supported (which is of very limited practical use), but more are coming
```
. listCompetitions(<YOUR_APP_KEY>, <YOUR_SESSION_TOKEN>, (optional) <ARRAY_OF_FILTERS>, (optional) <LOCALE>);
. listCountries(<YOUR_APP_KEY>, <YOUR_SESSION_TOKEN>, (optional) <ARRAY_OF_FILTERS>, (optional) <LOCALE>);
. listEvents(<YOUR_APP_KEY>, <YOUR_SESSION_TOKEN>, (optional) <ARRAY_OF_FILTERS>, (optional) <LOCALE>);
. listEventTypes(<YOUR_APP_KEY>, <YOUR_SESSION_TOKEN>, (optional) <ARRAY_OF_FILTERS>, (optional) <LOCALE>);
. listMarketTypes(<YOUR_APP_KEY>, <YOUR_SESSION_TOKEN>, (optional) <ARRAY_OF_FILTERS>, (optional) <LOCALE>);
. listVenues(<YOUR_APP_KEY>, <YOUR_SESSION_TOKEN>, (optional) <ARRAY_OF_FILTERS>, (optional) <LOCALE>);
```


## Testing

The package comes with a test suite. Some of the tests will simulate http activity (i.e. won't hit the Betfair servers), but some will test connectivity and will need valud credentials. These should be placed in a file called .env.php which can be created by copying the .env.example.php file in the package's root folder.


## Issues

This package was developed to meet a specific need and then generalised for wider use. If you have a use case not currently met, or see something that appears to not be working correctly, please raise an issue at the [github repo](https://github.com/petercoles/betfair/issues).


## License

This package is licensed under the [MIT license](http://opensource.org/licenses/MIT).
