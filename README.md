A simple poll bundle for symfony2.

---

This version is for Symfony 2.4.x projects.

For Symfony 2.0.x projects, you must use a [1.x](https://github.com/emiliemarchand/PrismPollBundle/tree/symfony-2.0.x) release of this bundle.

---

## Features

- List of all published polls
- Uses Ajax for voting and showing results
- Results displayed using bar graphs
- Uses cookies to prevent multiple voting
- A backend interface
- Easily overridable

**Screenshots:**

- [Frontend list and results](https://github.com/emiliemarchand/PrismPollBundle/blob/master/Resources/doc/Screenshots/frontend.png)
- [Backend list](https://github.com/emiliemarchand/PrismPollBundle/blob/master/Resources/doc/Screenshots/backend_list.png)
- [Backend edit page](https://github.com/emiliemarchand/PrismPollBundle/blob/master/Resources/doc/Screenshots/backend_edit.png)

**Note:** The backend doesn't come with an authentication system.

**TODO:**

- Functional tests
- Rewrite "Overriding the bundle" documentation for symfony 2.4.x

## Installation

**1.** Add this to your composer.json:
``` yml
"require": {
    "prism/poll-bundle": "dev-master"
}
```

**2.** Run a composer update:

``` bash
$ composer update
```

**3.** Register the bundle in ``app/AppKernel.php``:

(You also need to add StofDoctrineExtensionsBundle for the timestampable and sluggable features)

``` php
$bundles = array(
    // ...
    new Prism\PollBundle\PrismPollBundle(),
    new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
);
```

**4.** Import routing:

``` yaml
# app/config/routing.yml
PrismPollBundle_backend:
    prefix: /poll/backend
    resource: "@PrismPollBundle/Resources/config/routing/backend.yml"

PrismPollBundle_frontend:
    prefix: /poll
    resource: "@PrismPollBundle/Resources/config/routing/frontend.yml"
```

**5.** Add configuration for DoctrineExtensions:

``` yaml
# app/config/config.yml
stof_doctrine_extensions:
    orm:
        default:
            timestampable: true
            sluggable: true
```

**6.** Generate the tables:

``` bash
$ app/console doctrine:schema:update --force
```

This will create the PrismPoll and PrismPollOpinion tables


## Overriding the bundle

TODO: rewrite the documentation for Symfony 2.4.x projects.

You can still get the general idea by reading the [documentation](https://github.com/emiliemarchand/PrismPollBundle/blob/symfony-2.0.x/Resources/doc/overriding.md) for Symfony 2.0.x projects.