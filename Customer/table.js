document.addEventListener("DOMContentLoaded", function() {
    const tableContainer = document.getElementById('tableContainer');
    const continueBtn = document.getElementById('continueBtn');
    let selectedTable = null;

    // Create 10 tables dynamically
    for (let i = 1; i <= 10; i++) {
        const table = document.createElement('div');
        table.className = 'table-circle';
        table.textContent = `Table ${i}`;
        table.addEventListener('click', () => selectTable(i, table));
        tableContainer.appendChild(table);
    }

    // Select a table
    function selectTable(tableNumber, element) {
        document.querySelectorAll('.table-circle').forEach(t => t.classList.remove('selected'));
        element.classList.add('selected');
        selectedTable = tableNumber;
    }

    // Continue button click
    continueBtn.addEventListener('click', () => {
        if (!selectedTable) {
            alert('Please select a table before continuing.');
            return;
        }

        // Store table in sessionStorage
        sessionStorage.setItem('selectedTable', selectedTable);

        // Redirect to menu.php
        window.location.href = `menu.php?table=${selectedTable}`;
    });
});
