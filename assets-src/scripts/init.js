(function($){
    $(function(){
        $('table.datatable').each(function () {
            var $table = $(this).DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": $(this).data('uri'),
                    "data": function(data){
                        var $form = $('form[datatables="filter"]');

                        if ($form.length > 0) {
                            data.filter = $form.find('input, select').serializeArray();
                        }
                    }
                }
            });

           $.DatatablesManager.register($(this).data('alias'), $table);
        });
    });
})(jQuery);
