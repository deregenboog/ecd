require('jquery');
require('bootstrap');

$(function() {
    // create rich-text editors
    $('.ckeditor').each(function(){
        ClassicEditor.create(this, {
            toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote' ],
        });
    });

    // navigate to active tab
    $('.nav-tabs a[href="'+window.location.hash+'"]').tab('show')

    // change urls when activating other tab
    $('.nav-tabs a').on('shown.bs.tab', function(event) {
        window.history.replaceState({}, window.document.title, $(event.target).attr('href'));
        $('a[href*="redirect="]').each(function(index, element) {
//            $(element).attr('href', $(element).attr('href').replace(/(redirect=)([^&]*)/, '$1'+encodeURIComponent(window.location.href)));
            $(element).attr('href', $(element).attr('href').replace(/(redirect=)([^&]*)/, '$1'+window.location.pathname+window.location.hash));
        });
    });

    // make table rows clickable
    $('table.table.table-hover tr').click(function() {
        if (typeof($(this).data('href')) != 'undefined') {
            window.document.location = $(this).data('href');
        }
    });
});

require('../css/app.less');
