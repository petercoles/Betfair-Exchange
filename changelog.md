# Betfair API Wrapper Changlelog

## 1.2 Updates to external packages

Update dev packages: PHPUnit and Mockery (they've moved on a long way) and more permissive versions for Guzzle and the Tighten Collections package.
Code changes to deal with approaches deprecated in, or removed from, packages since the older versions (I'm looking at you PHPUnit).
Shouldn't be any significant function changes, but a new tag is added to allow locking onto the previous version if these are incompatible with other parts of your application.

## 1.1

More generic search term for text filtering.
Refactoring (and more robust) authentication.

## 1.0

Improvements to authentication, tests and documentation.

## 0.2 More request types and better code

Expanding range of supported requests, Betfair::init() alias and much cleaner code under the hood

## 0.1 Better API session handling

Removes the need to supply the API key and session token on every request. Credentials are now supplied once through a new Betfair::Auth()->init() method and the session token is managed and refreshed by the package.

## 0.0 Initial Release

Supports manual authentication to Betfair exchange via login, logout and keepAlive API calls and and basic list calls for competitions, countries, events, event types, market types and venues only.
