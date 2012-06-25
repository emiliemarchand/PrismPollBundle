A simple poll bundle for symfony2.

**Features:**

- List of all published polls
- Uses Ajax for voting and showing results
- Results displayed using bar graphs
- Uses cookies to prevent multiple voting
- A backend interface
- Easily overridable

**Screenshots:**

- [Frontend list and results](https://github.com/emiliemarchand/PrismPollBundle/tree/master/Resources/doc/frontend.png)
- [Backend list](https://github.com/emiliemarchand/PrismPollBundle/tree/master/Resources/doc/backend_list.png)
- [Backend edit page](https://github.com/emiliemarchand/PrismPollBundle/tree/master/Resources/doc/backend_edit.png)

**Note:** The backend doesn't come with an authentication system.

**TODO:**

- Functional tests
- Composer support

## Installation

### Add the bundle in the `deps` file:

``` ini
[PrismPollBundle]
    git=http://github.com/emiliemarchand/PrismPollBundle.git
    target=/bundles/Prism/PollBundle
```

You will also need to add DoctrineExtensions for the timestampable and sluggable features:

``` ini
[gedmo-doctrine-extensions]
    git=git://github.com/l3pp4rd/DoctrineExtensions.git

[DoctrineExtensionsBundle]
    git=git://github.com/stof/StofDoctrineExtensionsBundle.git
    target=/bundles/Stof/DoctrineExtensionsBundle
```

Install the vendors:

``` bash
$ bin/vendors install
```

### Configure the autoloader

``` php
<?php
// app/autoload.php

$loader->registerNamespaces(array(
    // ...
    'Prism'            => __DIR__.'/../vendor/bundles',
    'Stof'             => __DIR__.'/../vendor/bundles',
    'Gedmo'            => __DIR__.'/../vendor/gedmo-doctrine-extensions/lib',
));
```

### Configure the kernel

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Prism\PollBundle\PrismPollBundle(),
        new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
    );
}
```

### Import routing configuration

``` yaml
# app/config/routing.yml
PrismPollBundle_backend:
    prefix: /poll/backend
    resource: "@PrismPollBundle/Resources/config/routing/backend.yml"
```


### Add configuration for DoctrineExtensions

``` yaml
# app/config/config.yml
stof_doctrine_extensions:
    orm:
        default:
            timestampable: true
            sluggable: true
```

### Generate the tables

``` bash
$ app/console doctrine:schema:update --force
```

This will create the PrismPoll and PrismPollOpinion tables


## Overriding the bundle

See the [documentation](https://github.com/emiliemarchand/PrismPollBundle/blob/master/Resources/doc/overriding.md)