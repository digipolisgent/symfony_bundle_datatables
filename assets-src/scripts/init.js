(function ($) {
    $(function () {
        $('table.datatable').each(function () {
            var defaultOptions = {
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": $(this).data('uri'),
                    "data": function (data) {
                        var $form = $('form[datatables="filter"]');

                        if ($form.length > 0) {
                            data.filter = $form.find('input, select').serializeArray();
                        }
                    }
                }
            };

            var $options = $.extend({}, defaultOptions, $(this).data('options'));
            var $table = $(this).DataTable($options);

            $.DatatablesManager.register($(this).data('alias'), $table);
        });
    });
})(jQuery);
