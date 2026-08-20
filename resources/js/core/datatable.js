export function reloadDataTable(selector) {
    const table =
        $(selector).DataTable();

    table.ajax.reload(null, false);
}