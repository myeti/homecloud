$(document).ready(function(){

    // dismiss alert
    $('.alert').on('click', function(){
        $(this).slideUp(300, function(){
            $(this.remove());
        });
    });

    // modal auto focus
    $('.modal').on('shown.bs.modal', function () {
        $('input:text:visible:first', this).focus();
    });

    // rename
    $('.rename').on('click', function() {
        var name = $(this).data('name');
        $('#modalRename input[name=name]').val(name);
        $('#modalRename input[name=to]').val(name).focus(function(){
            this.select();
        });
    });

    // delete
    $('.delete').on('click', function() {
        var name = $(this).data('name');
        $('#modalDelete input[name=name]').val(name);
        $('#modalDelete .str_name').text(name);
    });

    // dropzone
    Dropzone.options.uploadZone = {
        init: function() {
            this.on('queuecomplete', function() {
                document.location.reload(true);
            });
        }
    };

});