function addColumn1() {
    const table = document.getElementById("editableTable1");
    const headerRow = table.rows[0];
    const newRow = table.rows[1];

    const newHeaderCell = document.createElement("th");
    newHeaderCell.contentEditable = true;
    newHeaderCell.textContent = "New Column";

    const newCell = document.createElement("td");
    newCell.contentEditable = true;
    newCell.textContent = "New Data";

    headerRow.appendChild(newHeaderCell);
    newRow.appendChild(newCell);
}

function addColumn2() {
    const table = document.getElementById("editableTable2");
    const headerRow = table.rows[0];
    const newRow = table.rows[1];

    const newHeaderCell = document.createElement("th");
    newHeaderCell.contentEditable = true;
    newHeaderCell.textContent = "New Column";

    const newCell = document.createElement("td");
    newCell.contentEditable = true;
    newCell.textContent = "New Data";

    headerRow.appendChild(newHeaderCell);
    newRow.appendChild(newCell);
}

function addColumn3() {
    const table = document.getElementById("editableTable3");
    const headerRow = table.rows[0];
    const newRow = table.rows[1];

    const newHeaderCell = document.createElement("th");
    newHeaderCell.contentEditable = true;
    newHeaderCell.textContent = "New Column";

    const newCell = document.createElement("td");
    newCell.contentEditable = true;
    newCell.textContent = "New Data";

    headerRow.appendChild(newHeaderCell);
    newRow.appendChild(newCell);
}

function addRow1() {
    const table = document.getElementById("editableTable1");
    const newRow = table.insertRow(-1);

    const cell = newRow.insertCell(0);
    cell.contentEditable = true;
    cell.textContent = "New Row Data";

    for (let i = 1; i < table.rows[0].cells.length; i++) {
        const newCell = document.createElement("td");
        newCell.contentEditable = true;
        newCell.textContent = "New Data";
        newRow.appendChild(newCell);
    }
}

function addRow3() {
    const table = document.getElementById("editableTable3");
    const newRow = table.insertRow(-1);

    const cell = newRow.insertCell(0);
    cell.contentEditable = true;
    cell.textContent = "New Row Data";

    for (let i = 1; i < table.rows[0].cells.length; i++) {
        const newCell = document.createElement("td");
        newCell.contentEditable = true;
        newCell.textContent = "New Data";
        newRow.appendChild(newCell);
    }
}
