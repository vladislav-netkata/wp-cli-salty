# WP-CLI Salty Commands
================

This package grab new salts from the [WordPress Salt API](https://api.wordpress.org/secret-key/1.1/salt/)

## Generate and save salts to a file

```
wp salty generate --file=develop.wp-cli.yml
```

This will output the salts to a file.

## Generate and save salts for all branches (develop, staging, production)

```
wp salty generate
```

It is equal to:

```
wp salty generate --file=develop.wp-cli.yml
wp salty generate --file=staging.wp-cli.yml
wp salty generate --file=production.wp-cli.yml
```