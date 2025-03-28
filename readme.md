# ⚙️ Clockwork for Magento 2

[![PHP 7.4+](https://img.shields.io/badge/PHP-7.4%2B-blue.svg)](#)
[![Magento 2.4.3+](https://img.shields.io/badge/Magento-2.4.3%2B-orange.svg)](#)
[![License MIT](https://img.shields.io/badge/License-MIT-green.svg)](#)
[![Packagist Version](https://img.shields.io/packagist/v/inpvlsa/magento-clockwork)](https://packagist.org/packages/inpvlsa/magento-clockwork)
[![Packagist Downloads](https://img.shields.io/packagist/dt/inpvlsa/magento-clockwork)](#)

A powerful development tool that brings advanced debugging and profiling capabilities to Magento 2.

Track timeline events, database queries, cache operations, template rendering, and more - all through an intuitive interface built on [⚙️ Clockwork](https://github.com/itsgoingd/clockwork).

## 🚀 Key Features

- **Comprehensive Timeline**: View and filter all page events by type or text
- **Interactive Toolbar**: Quick access to debugging tools
- **Detailed Profiling**: Track a wide range of timeline events:
  - Routing processes
  - Layout rendering
  - Event dispatching
  - Observer execution
  - Collection loading
- **Database Monitoring**
  - SQL query inspection with syntax highlighting
  - Sort, search, and analyze database operations
- **Template Insights**: Track template rendering performance
- **Cache Inspection**: Monitor cache operations with identifiers, data, tags, and TTL
- **Request Analysis**: Examine Magento-specific and HTTP request data
- **Search Integration**: Track all OpenSearch/ElasticSearch queries and responses
- **AJAX Support**: Monitor all HTTP requests in the frontend area

## 📋 Requirements

- **PHP 7.4+**
- **Magento 2 (Community Edition)**

> - Tested on versions:
>    - 2.4.3 (PHP 7.4, with 3rd party extensions)
>    - 2.4.6 (PHP 8.1, Hyva, 3rd party extensions)
>    - 2.4.7 (PHP 8.3, clean installation)

> Note: Minimum tested Magento version is 2.4.3. Enterprise Edition compatibility not yet verified.

## 📦 Installation

```bash
# Recommended for development environments only
composer require inpvlsa/magento-clockwork --dev

# For dev/stage environments (see Authentication section)
composer require inpvlsa/magento-clockwork
```

> You are free now to install it to non-local instances (with no `--dev`), check the [Authentication](#-authentication) section. But I strongly recommend not adding packages to production environments and use `--dev` flag.

## ⚙️ Configuration

Enable the module and turn on Clockwork writing mode:

```bash
php bin/magento module:enable Inpvlsa_Clockwork
php bin/magento config:set dev/clockwork/enabled 1
```

### Storage options

By default, data is stored in files. You can change the storage method:

- Via admin panel: Stores → Advanced → Developer → Clockwork
- Or using CLI:

```bash
php bin/magento config:set dev/clockwork/data_storage file|redis 
```

> ### Redis Configuration Note
> - Pre-set configuration of Redis storage requires Magento session storage to be configured for Redis
>   - The module retrieves Redis connection data from Magento deployment config if not a "custom" redis credential is set in module configuration tab
> - Check logs after switching to Redis - errors will cause fallback to file storage

For detailed Redis configuration, see <ins>[Redis Documentation](_doc/Redis.md)</ins>.

## 🔐 Authentication

Authentication is automatically configured for local development environments.

If you can't access the /clockwork URL, add your IP to the maintenance mode whitelist.

For detailed authentication options, see <ins>[Authentication Documentation](_doc/Authentication.md)</ins>.

## 🧩 Usage

1. Navigate to `/clockwork` from your Magento root URL
2. Explore timeline events grouped by type
3. Use text filters to search specific information
4. Utilize the button on the right of the search input for additional sorting options
5. Explore logs, database queries, cache operations, collections tab, enjoy!

## 📸 Feature Showcase

![Web1](https://github.com/INPVLSA/magento-clockwork/blob/assets/repo_asset/Web.png?raw=true)
![Web2](https://github.com/INPVLSA/magento-clockwork/blob/assets/repo_asset/Web2.png?raw=true)
![Collection](https://github.com/INPVLSA/magento-clockwork/blob/assets/repo_asset/Collection.png?raw=true)
![Db](https://github.com/INPVLSA/magento-clockwork/blob/assets/repo_asset/Db.png?raw=true)
![Cache](https://github.com/INPVLSA/magento-clockwork/blob/assets/repo_asset/Cache.png?raw=true)
![Events](https://github.com/INPVLSA/magento-clockwork/blob/assets/repo_asset/Events.png?raw=true)
![Request](https://github.com/INPVLSA/magento-clockwork/blob/assets/repo_asset/Request.png?raw=true)
![OpenSearch](https://github.com/INPVLSA/magento-clockwork/blob/assets/repo_asset/OpenSearch.png?raw=true)
