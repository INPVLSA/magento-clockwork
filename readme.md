# ⚙️ Clockwork integration for Magento 2

Integration of PHP development tool Clockwork for Magento 2.

- Clockwork: [GitHub](https://github.com/itsgoingd/clockwork) - [Website](https://underground.works/clockwork/) 

![Web](https://github.com/INPVLSA/magento-clockwork/blob/assets/repo_asset/Web.png?raw=true)

## 📝 Requirements

- PHP 8.3 or later (I'm working on downgrading minimum PHP version)
- Magento 2 with similar PHP version requirements

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

Data storage is set to `file` by default. You can change it in configuration `Stores` -> `Advanced` -> `Developer` -> `Clockwork`

Or using CLI 
```bash
php bin/magento config:set dev/clockwork/data_storage file|redis
```

- Redis storage requires session storage to be set to Redis (It retrieves redis connection data from Magento deployment config).

### Authentication

> Authenticator should allow you to access Clockwork panel on local environment.

> If you still can't access `/clockwork` URL you can add your IP to maintenance mode whitelist.

#### [Detailed description of authenticator](_doc/Authentication.md)

## 🧐 Usage

You can access Clockwork panel in 2 ways:
1. By adding `/clockwork` to the root URL of your Magento instance.
2. By using [Clockwork Chrome](https://chromewebstore.google.com/detail/clockwork/dmggabnehkmmfmdffgajcflpdjlnoemp) or [Clockwork Firefox](https://addons.mozilla.org/en-US/firefox/addon/clockwork-dev-tools/) extension (Tab "Clockwork" in browser developer toolbar).

## ✨ Features

- CSV? No.
- Maybe open separate page which will contain only last page data? No. You don't need to reload page to see new data


- Timeline with filters by type/text
  - All profiler events. All filterable by types (not all implemented yet, but)
    - Routing
    - Controller dispatch
    - Layout rendering
    - Events dispatching
    - Observers execution
- Collections. With theirs SQL queries and time of loading
- All database requests executed on page. Sortable, searchable, prettified ✨
- Request data
  - Magento-specific data (`IsSecure`, `PathInfo`, etc.)
  - Http request data
- All OpenSearch/ElasticSearch queries/responses

## 🏞️ More screenshots

![Web2](https://github.com/INPVLSA/magento-clockwork/blob/assets/repo_asset/Web2.png?raw=true)
![Db](https://github.com/INPVLSA/magento-clockwork/blob/assets/repo_asset/Db.png?raw=true)
![Request](https://github.com/INPVLSA/magento-clockwork/blob/assets/repo_asset/Request.png?raw=true)
![OpenSearch](https://github.com/INPVLSA/magento-clockwork/blob/assets/repo_asset/OpenSearch.png?raw=true)
