Betfair API Wrapper Changlelog
==============================

## 0.2 More request types and better code

Expanding range of supported requests, Betfair::init() alias and much cleaner code under the hood

## 0.1 Better API session handling

Removes the need to supply the API key and session token on every request. Credentials are now supplied once through a new Betfair::Auth()->init() method and the session token is managed and refreshed by the package.

## 0.0 Initial Release

Supports manual authentication to Betfair exchange via login, logout and keepAlive API calls and and basic list calls for competitions, countries, events, event types, market types and venues only.
