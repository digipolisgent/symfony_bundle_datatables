(function($){
    $('table.data-tables').each(function () {
        $(this).DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": $(this).data('uri')
        });
    });
});
