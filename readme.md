# ⚙️ Clockwork integration for Magento 2

Development tool for Magento 2 with timeline, database queries, cache, templates, and more. Built on Clockwork.

- Clockwork: [GitHub](https://github.com/itsgoingd/clockwork) - [Website](https://underground.works/clockwork/) 

## ✨ Features

> CSV? No. Maybe open a separate page with only the last page's data? No.


1. Timeline with filters by type/text
2. All profiler events. All filterable by types (not all implemented yet, but)
    1. Routing
    2. Layout
    3. Events dispatching (Note: some layout-related events are not logged due to high amount of almost useless data)
    4. Observers execution
3. Collections. With SQL queries, load time, classname
4. All database requests executed on the page. Sortable, searchable, prettified ✨
5. Templates rendering
6. Cache load/save with identifiers, data, tags, ttl (last 3 on write)
7. Request data
    1. Magento-specific data (`IsSecure`, `PathInfo`, etc.)
    2. Http request data
8. All OpenSearch/ElasticSearch queries/responses
9. Supports AJAX (and all Http in frontend area)

![Web](https://github.com/INPVLSA/magento-clockwork/blob/assets/repo_asset/Web.png?raw=true)

## 📝 Requirements

- **PHP 7.4** or later
- **Community Magento 2** with similar PHP version requirements (tested only on Community, not tested with Enterprise yet)

> Minimal tested Magento version is 2.4.3 
> Tested on:
> - 2.4.3 (PHP74, with a lot of 3rd party extensions)
> - 2.4.6 (PHP81, Hyva, some 3rd party extensions)
> - 2.4.7 (PHP83, clean)

## 🔧 Installation

**Important**! Use `--dev` flag to avoid installing Clockwork on live environments

```bash
composer require inpvlsa/magento2-clockwork --dev
```

## 🛠️ Setup/Configuration

```bash
php bin/magento module:enable Inpvlsa_Clockwork
php bin/magento dev:profiler:enable Inpvlsa\\Clockwork\\Model\\Profiler\\ClockworkProfilerDriver
```

Data storage is set to `file` by default. 

- You can change it in the configuration `Stores -> Advanced -> Developer -> Clockwork`
- Or using CLI 
```bash
php bin/magento config:set dev/clockwork/data_storage file|redis
```

> Redis storage requires session storage to be set to Redis (It retrieves redis connection data from the Magento deployment config).

### Authentication

> Authenticator should allow you to access Clockwork panel on the local environment.

> If you still can't access `/clockwork` URL you can add your IP to the maintenance mode whitelist.

#### [Detailed description of authenticator](_doc/Authentication.md)

## 🧐 Usage

You can access Clockwork panel in 2 ways:
1. By adding `/clockwork` to the root URL of your Magento instance.
2. By using [Clockwork Chrome](https://chromewebstore.google.com/detail/clockwork/dmggabnehkmmfmdffgajcflpdjlnoemp) or [Clockwork Firefox](https://addons.mozilla.org/en-US/firefox/addon/clockwork-dev-tools/) extension (Tab "Clockwork" in browser developer toolbar).

## 🏞️ More screenshots

![Web1](https://github.com/INPVLSA/magento-clockwork/blob/assets/repo_asset/Web.png?raw=true)
![Web2](https://github.com/INPVLSA/magento-clockwork/blob/assets/repo_asset/Web2.png?raw=true)
![Collection](https://github.com/INPVLSA/magento-clockwork/blob/assets/repo_asset/Collection.png?raw=true)
![Db](https://github.com/INPVLSA/magento-clockwork/blob/assets/repo_asset/Db.png?raw=true)
![Cache](https://github.com/INPVLSA/magento-clockwork/blob/assets/repo_asset/Cache.png?raw=true)
![Events](https://github.com/INPVLSA/magento-clockwork/blob/assets/repo_asset/Events.png?raw=true)
![Request](https://github.com/INPVLSA/magento-clockwork/blob/assets/repo_asset/Request.png?raw=true)
![OpenSearch](https://github.com/INPVLSA/magento-clockwork/blob/assets/repo_asset/OpenSearch.png?raw=true)
