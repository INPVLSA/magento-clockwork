Data storage is set to `file` by default.

- You can change it in the configuration `Stores -> Advanced -> Developer -> Clockwork`
- Or using CLI

```bash
php bin/magento config:set dev/clockwork/data_storage redis
```

> - Redis storage by default requires Magento session storage to be set to Redis (It retrieves redis connection data from the Magento deployment config).
> - After switching to Redis check logs, there might be initialization errors causes fallback to file storage.

You can find more Redis configuration in `Stores -> Advanced -> Developer -> Clockwork`

You can use one of Magento Redis configurations:
- Session
- Cache

Or specify custom credentials in the configuration after switching "Use redis credentials from" to `Custom`  

<hr>

- ⬅️ Return to [Documentation](../readme.md)
- ⬅️ Return to [Repository](https://github.com/INPVLSA/magento-clockwork/blob/master/)
