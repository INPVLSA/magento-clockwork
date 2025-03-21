# ⚙️ Clockwork integration for Magento 2

Integration of PHP development tool Clockwork for Magento 2.

Clockwork: [GitHub](https://github.com/itsgoingd/clockwork) - [Website](https://underground.works/clockwork/) 

![Web](https://github.com/INPVLSA/magento-clockwork/blob/assets/repo_asset/Web.png?raw=true)

## 📝 Requirements

- PHP 8.3 or later (I'm working on downgrading minimum PHP version)
- Magento 2 with similar PHP version requirements

## 🔧 Installation

**Important**! Use `--dev` flag to avoid installing Clockwork on live environments

```bash
composer require inpvlsa/magento2-clockwork --dev
```

## 🛠️ Setup

```bash
php bin/magento module:enable Inpvlsa_Clockwork
php bin/magento dev:profiler:enable Inpvlsa\\Clockwork\\Model\\Profiler\\ClockworkProfilerDriver
```

### 🔑 Authentication

> Authenticator should allow you to access Clockwork panel on local environment.

> If you still can't access `/clockwork` URL you can add your IP to maintenance mode whitelist.

#### 🔐 [Detailed description of authenticator](_doc/Authentication.md)

## 🧐 Usage

You can access Clockwork panel in 2 ways:
1. By adding `/clockwork` to the root URL of your Magento instance.
2. By using [Clockwork Chrome](https://chromewebstore.google.com/detail/clockwork/dmggabnehkmmfmdffgajcflpdjlnoemp) or [Clockwork Firefox](https://addons.mozilla.org/en-US/firefox/addon/clockwork-dev-tools/) extension (Tab "Clockwork" in browser developer toolbar).

## ✨ Features

Timeline includes:
- All profiler events:
  - Routing
  - Controller dispatch
  - Layout rendering
  - Events dispatching
  - Observers execution

Additional data from page available in tabs:
- Request data, including Magento-specific data for Http request (`IsSecure`, `PathInfo`, etc.)
- OpenSearch/ElasticSearch queries/responses

## 🏁 Next implementation steps
1. Redis storage
2. DB storage

## 🏞️ More screenshots

![Web2](https://github.com/INPVLSA/magento-clockwork/blob/assets/repo_asset/Web2.png?raw=true)
![Db](https://github.com/INPVLSA/magento-clockwork/blob/assets/repo_asset/Db.png?raw=true)
![Request](https://github.com/INPVLSA/magento-clockwork/blob/assets/repo_asset/Request.png?raw=true)
![OpenSearch](https://github.com/INPVLSA/magento-clockwork/blob/assets/repo_asset/OpenSearch.png?raw=true)
