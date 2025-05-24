function initializeTables() {
    new DataTable("#example"),
        new DataTable("#scroll-vertical", {
            scrollY: "210px",
            scrollCollapse: !0,
            paging: !1,
        }),
        new DataTable("#scroll-horizontal", { scrollX: !0 }),
        new DataTable("#alternative-pagination", {
            pagingType: "full_numbers",
        }),
        new DataTable("#fixed-header", { fixedHeader: !0 }),
        new DataTable("#model-datatables", {
            responsive: {
                details: {
                    display: $.fn.dataTable.Responsive.display.modal({
                        header: function (a) {
                            a = a.data();
                            return "Details for " + a[0] + " " + a[1];
                        },
                    }),
                    renderer: $.fn.dataTable.Responsive.renderer.tableAll({
                        tableClass: "table",
                    }),
                },
            },
        }),
        new DataTable("#buttons-datatables", {
            paging: !0,
            // pagingType: "full_numbers",
            dom: "Blfrtip",
        //     buttons: ["copy", "csv", "excel",
        //         {
        //             extend: "pdf",
        //             text: "PDF",
        //             orientation: "landscape",
        //             pageSize: "A3",
        //             exportOptions: {
        //                 columns: ':visible'
        //             },
        //             customize: function (doc) {
        //                 doc.defaultStyle.fontSize = 20;
        //                 doc.styles.tableHeader.fontSize = 20;
        //                 doc.styles.tableHeader.alignment = 'center';
        //             }
        //         }
        // ],

        buttons: [
            "copy", "csv", "excel",
            {
                extend: "pdf",
                text: "PDF",
                orientation: "landscape",
                pageSize: "A3",
                exportOptions: {
                    columns: ':visible'
                },
                customize: function (doc) {
                    let columnsCount = doc.content[1].table.body[0].length;

                    // 1. Auto column widths (jitne columns, utna adjust karega)
                    let columnWidths = [];
                    for (let i = 0; i < columnsCount; i++) {
                        columnWidths.push('*');  // Each column equal width
                    }

                    doc.content[1].table.widths = columnWidths;

                    // 2. Dynamic font size (columns count ke hisaab se font size kam zyada)
                    if (columnsCount > 15) {
                        doc.defaultStyle.fontSize = 7;  // More columns, smaller font
                        doc.styles.tableHeader.fontSize = 8;
                    } else {
                        doc.defaultStyle.fontSize = 10;
                        doc.styles.tableHeader.fontSize = 11;
                    }

                    // 3. Center align headers, left align body data
                    doc.styles.tableHeader.alignment = 'center';
                    doc.content[1].table.body.forEach(function(row, rowIndex) {
                        row.forEach(function(cell, colIndex) {
                            if (rowIndex === 0) {
                                cell.alignment = 'center';  // Header
                            } else {
                                cell.alignment = 'left';  // Data
                                cell.noWrap = false;  // Allow text wrapping
                            }
                        });
                    });

                    // 4. Optional - Add Title and Footer
                    doc.content.unshift({
                        text: 'My Dynamic DataTable Report',
                        fontSize: 14,
                        bold: true,
                        margin: [0, 0, 0, 10],
                        alignment: 'center'
                    });

                    doc.footer = function (currentPage, pageCount) {
                        return {
                            text: 'Page ' + currentPage + ' of ' + pageCount,
                            alignment: 'center',
                            fontSize: 8
                        };
                    };
                }
            }
        ]

            // buttons: ["csv", "excel"],
        }),
        new DataTable("#ajax-datatables", {
            ajax: "assets/json/datatable.json",
        });
    var a = $("#add-rows").DataTable(),
        e = 1;
    $("#addRow").on("click", function () {
        a.row
            .add([
                e + ".1",
                e + ".2",
                e + ".3",
                e + ".4",
                e + ".5",
                e + ".6",
                e + ".7",
                e + ".8",
                e + ".9",
                e + ".10",
                e + ".11",
                e + ".12",
            ])
            .draw(!1),
            e++;
    }),
        $("#addRow").trigger("click");
}
document.addEventListener("DOMContentLoaded", function () {
    initializeTables();
});
