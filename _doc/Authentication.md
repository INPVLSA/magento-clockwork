Default authenticator uses URL and your host parameters.

Is passes by default:
- ~`localhost` requests
- If Magento base URL ends with
    - `.local`
    - `.test`
    - `.wip`
    - `.loc`
    - `.docker`
- If IP website accessed from starts with one of (Docker, Vagrant, small business/home networks):
    - `192.168.`
    - `10.`
    - `172.16`
    - `172.17`
    - `172.18`
    - `172.19`
    - `172.20`
    - `172.21`
    - `172.22`
    - `172.23`
    - `172.24`
    - `172.25`
    - `172.26`
    - `172.27`
    - `172.28`
    - `172.29`
    - `172.30`
    - `172.31`
    - `127.`
- IPs added to Magento maintenance mode whitelist
<hr>

- If you still can't access /clockwork URL you can add your IP to the maintenance mode whitelist.

- If you still can access it for some reason - look into `\Inpvlsa\Clockwork\Model\Clockwork\ClockworkAuthenticator`

<hr>

- ⬅️ Return to [Documentation](../readme.md)
- ⬅️ Return to [Repository](https://github.com/INPVLSA/magento-clockwork/blob/master/)
