# Contributing to this project

## Reporting Bugs

If you happen to find a bug, we kindly request you to report it. You may report it by following these 3 points:

  * Check if the bug is not already reported!
  * A clear title to resume the issue
  * A description of the workflow needed to reproduce the bug

_Note: don't hesitate to give as much information as you can (OS, PHP version, extensions, etc.)._

## Pull Requests

### Sending a Pull Request

When you send a Pull Request, just make sure that:

* You add valid test cases.
* Tests are green.

### Matching Coding Standards

This project follows [Symfony coding standards](https://symfony.com/doc/current/contributing/code/standards.html).
But don't worry, you can fix CS issues automatically using the [PHP CS Fixer](http://cs.sensiolabs.org/) tool:

```shell script
vendor/bin/php-cs-fixer fix
```

And then, add fixed file to your commit before push.
Be sure to add only **your modified files**. If another files are fixed by cs tools, just revert it before commit.

### Code Quality

Before sending a Pull Request, be sure your code is optimized. The following tools ensure the code does not suffer from
any complexity.

PHPStan:

```shell script
vendor/bin/phpstan analyze
```

PHP Mess Detector:

```shell script
vendor/bin/phpmd src text phpmd.xml
```

### Tests

Any modification or adding some code should include at least unit and functional tests. Unit testing is done through
[PHPUnit](https://phpunit.de/), and functional testing through [Behat](https://docs.behat.org/en/latest/).

#### PHPUnit

Run the following command to execute the unit tests:

```shell script
vendor/bin/phpunit
```

#### Behat

Run the following command to execute the functional tests:

```shell script
vendor/bin/behat
```

# License and Copyright Attribution

When you open a Pull Request to this project, you agree to license your code under the [MIT license](../LICENSE)
and to transfer the copyright on the submitted code to [ARTE](https://www.arte.tv).

If you include code from another project, please mention it in the Pull Request description and credit the original
author.
