# GitHub Dashboard

![Tests](https://github.com/vincentchalamon/dashboard/workflows/Tests/badge.svg)

This project provides a dashboard to follow GitHub repositories workflows.

![Dashboard](doc/dashboard.png)

# Requirements

- [PHP](https://www.php.net/) >= 7.4
- [Symfony](https://symfony.com/download)

# Install

```shell
git clone git@github.com:vincentchalamon/dashboard.git dashboard
cd dashboard
symfony server:start
```

# Configuration

Create the `repositories.yaml` file at the root of your project, as following:

```yaml
repositories:
  - https://github.com/api-platform/demo/
  - https://github.com/GregoireHebert/docusign-bundle/
```

_Note: to prevent too many requests to the GitHub API, data are stored in cache. To refresh them, just clear the cache:_

```shell
bin/console cache:clear
```
