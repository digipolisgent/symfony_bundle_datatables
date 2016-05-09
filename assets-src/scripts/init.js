(function($){
    $(function(){
        $('table.datatable').each(function () {
            $(this).DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": $(this).data('uri')
            });
        });
    });
})(jQuery);
