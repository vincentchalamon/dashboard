# Dashboard

![Tests](https://github.com/vincentchalamon/dashboard/workflows/Tests/badge.svg)

This project provides a dashboard to follow your repositories workflows. By default, it runs with GitHub, but you can
easily provide a bridge for any provider (GitLab, Bitbucket, etc.).

![Dashboard](doc/screenshot.png)

# Requirements

- [PHP](https://www.php.net/) >= 8.1
- [Symfony](https://symfony.com/download)

## Usage with GitHub

- [GitHub Personal Access Token][github-pat]

# Install

```shell
git clone git@github.com:vincentchalamon/dashboard.git dashboard
cd dashboard
symfony server:start
```

# Configuration

It's recommended to dump the environment variables using composer:

```shell
composer dump-env prod
```

_Note: for a GitHub usage, generate a [GitHub Personal Access Token][github-pat] and change the `GITHUB_API_TOKEN`
environment variable with your own in the `.env.local.php` file._

Create the `repositories.yaml` file at the root of your project, as following:

```yaml
repositories:
    Default:
        - https://github.com/GregoireHebert/docusign-bundle/
    API Platform:
        - https://github.com/api-platform/demo/
```

_Note: to prevent too many requests to the provider API, data are stored in cache. To refresh them, just clear the
pool:_

```shell
bin/console cache:pool:clear cache.repository
```

[github-pat] https://github.com/settings/tokens/new?scopes=repo&description=GitHub+Dashboard
