# Dashboard

![Tests](https://github.com/vincentchalamon/dashboard/workflows/Tests/badge.svg)

This project provides a dashboard to follow your GitHub repositories workflows.

![Dashboard](doc/screenshot.png)

# Requirements

- [PHP](https://www.php.net/) >= 8.1
- [Symfony](https://symfony.com/download)
- [GitHub Personal Access Token][github-pat]

# Install locally

```shell
git clone git@github.com:vincentchalamon/dashboard.git dashboard
cd dashboard
symfony composer install
symfony composer dump-env prod
symfony server:start
```

## Configuration

Configure a [GitHub Personal Access Token][github-pat] on the `GITHUB_API_TOKEN` environment variable.

Create the `repositories.yaml` file at the root of your project, as following:

```yaml
repositories:
    Default:
        - https://github.com/GregoireHebert/docusign-bundle/
    API Platform:
        - api-platform/demo
```

_Note: your repository can be named as `https://github.com/owner/repo` or even `owner/repo`._

# Install on Heroku

```shell
heroku create
git push heroku master
```

## Configuration

Configure a Config Var `GITHUB_API_TOKEN` with your [GitHub Personal Access Token][github-pat].

Configure a Config Var `APP_REPOSITORIES` with a JSON containing your repositories:

```json
{"Default": ["https://github.com/GregoireHebert/docusign-bundle/"], "API Platform": ["api-platform/demo"]}
```

_Note: your repository can be named as `https://github.com/owner/repo` or even `owner/repo`._

# Cache

To prevent too many requests to the provider API, data are stored in cache. To refresh them, just clear the pool:

```shell
bin/console cache:pool:clear cache.repository
```

[github-pat] https://github.com/settings/tokens/new?scopes=repo&description=GitHub+Dashboard
