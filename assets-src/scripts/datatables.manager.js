(function($) {
    var Manager = {

        register: function (alias, table) {
            Manager.tables[alias] = table;
        },

        has: function (alias) {
            return Manager.tables.hasOwnProperty(alias);
        },

        fetch: function (alias) {
            if (Manager.has(alias)) {
                return Manager.tables[alias];
            }
        },

        tables: {}
    };

    $.extend($, {DatatablesManager: Manager});
})(jQuery);
