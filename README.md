# Using Auth0 with WordPress

How to filter WordPress posts by category for subscribers (readers, not authors) using [Auth0](https://auth0.com).

Tags: auth0, wordpress, auth0-plugin

## Requirements
[WordPress](https://wordpress.org/) Version 4.6 is what I used, but 4.5 will be fine too.
[Login by Auth0][https://wordpress.org/plugins/auth0/] Version 3.2.4 is what I used.  
[Auth0](https://auth0.com) Identity System.

## Setup

My sample was designed to work with a custom theme in WordPress, rather than as a plugin.  (That should be changed...)

How you setup your user's details in Auth0 is up to you, but for a quick, demo, I modified the app_metadata for the users I was testing with, to add blogCategories with slugs that they were permitted to view.

* Install WordPress.
* Create some Categories, with slugs, including __free__ and __paid__ (if you want to use my sample)
* Setup an Auth0 account.
* Create a new client in your Auth0 Applications area.
  * I created my Auth0 Client manually as my WordPress site was only available locally.
* Set up your WordPress [Child Theme](https://codex.wordpress.org/Child_Themes).
  * The functions.php file from here should be placed into your Child Theme's directory.
* Switch to that theme within WordPress.  
* Install the Auth0 Login plugin for WordPress.
* In the Auth0 settings in WordPress Admin...
  * Specify your Domain
  * Client ID
  * and Client Secret.  (I think the API token is only needed for the automated setup?)
* Log into the Blog with a new Auth0 based account, whether database or OAuth based.
  * Any posts that are not part included in the paid category will be invisible to you.
* Find the User in the Auth0 Users screen and modify their app_metadata to include...
```
{
  "blogCategories": [
    "paid"
  ]
}
```
* You may will need to logout and login again on the blog to have Auth0 fetch that meta data (I think)
* You should now be able to see the additional posts that were blocked before.
