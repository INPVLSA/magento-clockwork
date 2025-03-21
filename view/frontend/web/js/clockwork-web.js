(function () {
    'use strict';

    function onToggleDbView(e) {
        let target = e.target;

        if (target.tag !== 'A') {
            target = target.closest('a');
        }

        if (target.classList.contains('active')) {
            for (const row of document.querySelectorAll('tr[data-row-type="db"]')) {
                row.style.display = 'table-row';
            }
        } else {
            for (const row of document.querySelectorAll('tr[data-row-type="db"]')) {
                row.style.display = 'none';
            }
        }
    }

    function initialize() {
        for (const dbRecord of document.querySelectorAll('tr .ui-icon[data-name="database"]')) {
            const row = dbRecord.closest('tr');

            if (row.getAttribute('data-row-type') === 'db') {
                continue;
            }
            row.setAttribute('data-row-type', 'db');
        }
        const dbSwitchButton = document.querySelector('a.header-item[title="Database"]');

        if (dbSwitchButton) {
            dbSwitchButton.addEventListener('click', onToggleDbView);
        }

        document.querySelectorAll('a.details-header-tab').forEach((tab) => {
            tab.addEventListener('click', initWithWait)

            if (tab.getAttribute('data-tab-initialized') === 'true') {
                return;
            }

            tab.setAttribute('data-tab-initialized', 'true');
        });

        document.querySelectorAll('#requests tr').forEach((requestRow) => {
            requestRow.addEventListener('click', initWithWait)

            if (requestRow.getAttribute('data-request-initialized') === 'true') {
                return;
            }
            requestRow.setAttribute('data-request-initialized', 'true');
        });
    }

    function initWithWait() {
        setTimeout(initialize, 1000);
    }

    // Main component not dispatching any events, so need to wait a bit
    initWithWait();
})();
