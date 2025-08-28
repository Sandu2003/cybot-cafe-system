document.addEventListener("DOMContentLoaded", function() {
    const tableContainer = document.getElementById('tableContainer');

    // Create 3 tables dynamically
    for (let i = 1; i <= 10; i++) {
        const table = document.createElement('div');
        table.className = 'table-circle';
        table.textContent = `Table ${i}`;

        // Click event redirects immediately
        table.addEventListener('click', () => {
            // Store table in sessionStorage
            sessionStorage.setItem('selectedTable', i);
            // Redirect to menu.php
            window.location.href = `menu.php?table=${i}`;
        });

        tableContainer.appendChild(table);
    }
});
