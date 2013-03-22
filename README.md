# Crowd Rest Bundle

This bundle provides a simple API for Atlassian Crowd's REST API.

[![Build Status](https://travis-ci.org/seiffert/crowd-rest-bundle.png?branch=master)](https://travis-ci.org/seiffert/crowd-rest-bundle)

## Installation

Require the package via composer:

`composer.json`:

        "require": {
            ...
            "seiffert/crowd-rest-bundle": "dev-master",
            ...
        }

Activate the bundle in your AppKernel:

`app/AppKernel.php`:

        public function registerBundles()
        {
            $bundles = array(
                ...
                new Seiffert\CrowdRestBundle\SeiffertCrowdRestBundle(),
                ...
            );
            ...
        }

## Configuration

To connect to your organization's Crowd instance, you have to add some entries to your project configuration (e.g. in
`app/config/config.yml`):

    seiffert_crowd_rest:
        url: https://<crowd-url>/crowd/rest/usermanagement/1
        application:
            name: <application-name>
            password: <application-password>

* **crowd-url**: Your Crowd instance's Url/Hostname.
* **application-name**: The name of your application registered in Crowd.
* **application-password**: The password of your application registered in Crowd.

## Usage

After installing and configuring the bundle, you can use the Crowd API by injecting the service `seiffert.crowd` into
each client object. The injected object is of type `Seiffert\CrowdRestBundle\Crowd` and currently provides the following
methods:

* `getUser($username)`:
    Returns an instance of `Seiffert\CrowdRestBundle\Crowd\UserInterface` if the username matches a user record in
    Crowd. If not, a `UserNotFoundException` is being thrown.
* `isAuthenticationValid($username, $password)`:
    Returns true if the username and password match a user record in Crowd, false otherwise.

