;(function($) {
    $(document).ready(function() {
        $('.categories').select2();
    });
    $(document).on('click', '.delete-address', function(e) {
        e.preventDefault();

        if (!confirm(addressBook.confirm)) {
            return;
        }

        var self = $(this);
        var id = self.data('id');

        wp.ajax.post('delete-address', {
            id: id,
            _wpnonce: addressBook.nonce
        }).done(function(response){
            self.closest('tr')
                .css('background', '#f00')
                .hide(400, function(){
                    $(this).remove();
                });
        }).fail(function(error){
            console.log(error);
        });

    })
})(jQuery);