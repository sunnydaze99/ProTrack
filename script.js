function addColumn1() {
    const table = document.getElementById("editableTable1");
    const headerRow = table.rows[0];
    const newRow = table.rows[1];

    const newHeaderCell = document.createElement("th");
    const newHeaderTextarea = document.createElement("textarea");
    newHeaderCell.appendChild(newHeaderTextarea);
    newHeaderTextarea.name = "requirements_title[]";
    //xnewHeaderTextarea.rows = 4;
    newHeaderTextarea.placeholder = "New Title";

    const newCell = document.createElement("td");
    const newCellTextarea = document.createElement("textarea");
    newCell.appendChild(newCellTextarea);
    newCellTextarea.name = "requirements[]";
    //newCellTextarea.rows = 4;
    newCellTextarea.placeholder = "New Data";

    headerRow.appendChild(newHeaderCell);
    newRow.appendChild(newCell);
}

function addColumn2() {
    const table = document.getElementById("editableTable2");
    const headerRow = table.rows[0];

    const newHeaderCell = document.createElement("th");
    const newHeaderTextarea = document.createElement("textarea");
    newHeaderCell.appendChild(newHeaderTextarea);
    newHeaderTextarea.name = "num_phases[]";
    //newHeaderTextarea.rows = 4;
    newHeaderTextarea.placeholder = "New Date";

    headerRow.appendChild(newHeaderCell);
}

function addColumn3() {
    const table = document.getElementById("editableTable3");
    const headerRow = table.rows[0];
    const newRow = table.rows[1];

    const newHeaderCell = document.createElement("th");
    const newHeaderTextarea = document.createElement("textarea");
    newHeaderCell.appendChild(newHeaderTextarea);
    newHeaderTextarea.name = "rubrics_title[]";
    //newHeaderTextarea.rows = 4;
    newHeaderTextarea.placeholder = "New Title";

    const newCell = document.createElement("td");
    const newCellTextarea = document.createElement("textarea");
    newCell.appendChild(newCellTextarea);
    newCellTextarea.name = "rubrics[]";
    //newCellTextarea.rows = 4;
    newCellTextarea.placeholder = "New Data";

    headerRow.appendChild(newHeaderCell);
    newRow.appendChild(newCell);
}


function addRow1() {
    const table = document.getElementById("editableTable1");
    const newRow = table.insertRow(-1);

    for (let i = 0; i < table.rows[0].cells.length; i++) {
        const newCell = newRow.insertCell(i);
        const newCellTextarea = document.createElement("textarea");
        newCell.appendChild(newCellTextarea);
        newCellTextarea.name = "requirements[]";
        //newCellTextarea.rows = 4;
        newCellTextarea.placeholder = "New Data";
    }
}
function addRow3() {
    const table = document.getElementById("editableTable3");
    const newRow = table.insertRow(-1);

    for (let i = 0; i < table.rows[0].cells.length; i++) {
        const newCell = newRow.insertCell(i);
        const newCellTextarea = document.createElement("textarea");
        newCell.appendChild(newCellTextarea);
        newCellTextarea.name = "rubrics[]";
        //newCellTextarea.rows = 4;
        newCellTextarea.placeholder = "New Data";
    }
}
function generateKey() {
    const keyLength = 8;
    const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    let key = '';

    for (let i = 0; i < keyLength; i++) {
        const randomIndex = Math.floor(Math.random() * characters.length);
        key += characters.charAt(randomIndex);
    }

    document.getElementById('generatedKey').value = key;
}