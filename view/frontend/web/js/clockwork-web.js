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
            dbRecord.closest('tr').setAttribute('data-row-type', 'db');
        }
        document.querySelector('a.header-item[title="Database"]').addEventListener('click', onToggleDbView);
    }

    setTimeout(initialize, 1000);
})();
