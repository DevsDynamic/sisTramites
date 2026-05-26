export function reloadTable(tableId) {

    const table =
        $(tableId).DataTable();

    table.ajax.reload(null, false);
}