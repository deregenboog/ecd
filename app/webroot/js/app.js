$(function() {

    $('form button[type="submit"]').on('click', function(event) {
        var button = $(this);
        // disable clicked button almost immediately to prevent double click
        setTimeout(function() {
            button.prop('disabled', true);
            // enabled clicked button again after one second
            setTimeout(function() {
                button.prop('disabled', false);
            }, 1000);
        }, 50);
    });

    var adjustUrls = function(event) {
        window.history.replaceState({}, window.document.title, $(event.target).attr('href'));
        $('a[href*="redirect="]').each(function(index, element) {
//            $(element).attr('href', $(element).attr('href').replace(/(redirect=)([^&]*)/, '$1'+encodeURIComponent(window.location.href)));
            $(element).attr('href', $(element).attr('href').replace(/(redirect=)([^&]*)/, '$1'+window.location.pathname+window.location.hash));
        });
    };

    // navigate to active tab or pill
    $('.nav-tabs a[href="'+window.location.hash+'"]').tab('show');
    $('.nav-pills a[href="'+window.location.hash+'"]').tab('show');

    // adjust urls when activating other tab or pill
    $('.nav-tabs a').on('shown.bs.tab', adjustUrls);
    $('.nav-pills a').on('shown.bs.tab', adjustUrls);

    // make table rows clickable
    $('table.table.table-hover tr').click(function(event) {
        // do nothing when NOT clicked on TD tag (for example: A or INPUT tag inside a table)
        if ('TD' !== event.target.tagName) {
            return;
        }
        if (typeof($(this).data('href')) != 'undefined') {
            window.document.location = $(this).data('href');
        }
    });

    var filter = $('form.ajaxFilter');
    var lastFilterState = null;
    var postFilterForm = _.debounce(function() {
        var currentFilterState = filter.serialize();
        if (currentFilterState != lastFilterState) {
            lastFilterState = currentFilterState;
            filter.submit();
        }
    }, 300);
    filter > $(':input').on('keyup', postFilterForm);
    filter > $(':input').on('change', postFilterForm);
    filter.submit(function(event) {
        var url = window.location.pathname+'?'+filter.serialize();
        showLoader();
        $.get({
            url: url,
        }).done(function(response) {
            $('#ajaxContainer').html(response);
            hideLoader();
            window.history.replaceState({}, window.document.title, url);
        });
        event.preventDefault();
    });
});

var showLoader = function() {
    $("#ajaxContainer").hide();
    $("#loader").show();
}

var hideLoader = function() {
    $("#loader").hide();
    $("#ajaxContainer").show();
}
