(function() {
    var e = document.getElementById("sidebar-scroll");
    new SimpleBar(e, { autoHide: !0 });

    var tableContent = document.querySelector('#table-container');
    new SimpleBar(tableContent, { autoHide: true });
})();

