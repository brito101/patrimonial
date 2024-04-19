$("#table1 tbody").on("click", "tr", function () {
    $(this).toggleClass("selected bg-dark");
    let rows = $("#table1")[0].rows;
    let ids = [];
    $.each(rows, function (i, el) {
        if ($(el).hasClass("selected")) {
            ids.push(el.children[0].textContent);
        }
    });
    $(".ids").val(ids);
});

$("#batch-delete").on("click", function (e) {
    if (!confirm($(this).data("confirm"))) {
        e.stopImmediatePropagation();
        e.preventDefault();
    }
});

$("#change-status").on("click", function (e) {
    if (!confirm($(this).data("confirm"))) {
        e.stopImmediatePropagation();
        e.preventDefault();
    }
});
