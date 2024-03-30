$(function () {

    $('select').select2();

    // add select-all and select-none to multi-select fields
    $('select[multiple]').each(function() {
        let selectElm = $(this);

        let disableSelectAll = (typeof selectElm.attr('disable-select-all') !== 'undefined')
        let disableSelectNone = (typeof selectElm.attr('disable-select-none') !== 'undefined')

        if (!disableSelectAll) {
            let selectAll = $('<a>', {
                text: 'Alles selecteren',
                class: 'btn btn-default btn-xs',
            });
            selectAll.on('click', function(event) {
                let values = [];
                selectElm.find('option').each(function() {
                    values.push($(this).attr('value'));
                })
                selectElm.val(values).trigger('change');
                event.stopPropagation();
            });
            selectElm.parent().append(selectAll);
        }

        if (!disableSelectNone) {
            let selectNone = $('<a>', {
                text: 'Niets selecteren',
                class: 'btn btn-default btn-xs',
            });
            selectNone.on('click', function(event) {
                selectElm.val(null).trigger('change');
                event.stopPropagation();
            });
            selectElm.parent().append(selectNone);
        }
    })

    $('select').on('select2:open', (e) => {
        const selectId = e.target.id
        $(".select2-search__field[aria-controls='select2-" + selectId + "-results']").each(function (_key, value) {
            value.focus()
        })
    })

    $('[data-toggle="tooltip"]').tooltip()

    var hash = window.location.hash;

    if (hash) {
        try {
            const id = hash.substr(1);
            const yourElement = document.getElementById(id);
            const y = yourElement.getBoundingClientRect().top + window.pageYOffset;
            window.scrollTo({ top: y, behavior: 'smooth' });
        } catch (e) {
            //catch hash to non existent element. do nothing. #1096
        }

    }

    $('form button[type="submit"]').on('click', function (_event) {
        var button = $(this);
        // disable clicked button almost immediately to prevent double click
        setTimeout(function () {
            button.prop('disabled', true);
            // enabled clicked button again after one second
            setTimeout(function () {
                button.prop('disabled', false);
            }, 1000);
        }, 50);

        makeCkEditorRequired();
    });

    /**
     * By default, a required attribute on a textarea is rendered by the browsers validation thing
     * But since CKEDITOR replaces the textarea and set it to display:none, the required attribute is gone.
     * Thus practically makes it bypass validation.
     *
     * Wo dont want that. Therefore we have to implement it manually via this function.
     * IMO this should be done default by CKEDITOR but who am i...
     */
    var makeCkEditorRequired = function () {
        if (typeof CKEDITOR !== "undefined") {
            for (var instanceName in CKEDITOR.instances) {
                var instance = CKEDITOR.instances[instanceName]
                instance.on('required', function (evt) {
                    evt.editor.showNotification('Dit is een verplicht veld.', 'info');//warning has to be removed manually while info dissapears after 5 seconds... which is more friendly.
                    evt.cancel();
                });
            }
        }
    };

    var adjustUrls = function (event) {
        window.history.replaceState({}, window.document.title, $(event.target).attr('href'));
        $('a[href*="redirect="]').each(function (index, element) {
            $(element).attr('href', $(element).attr('href').replace(/(redirect=)([^&]*)/, '$1' + encodeURIComponent(window.location.href)));
            $(element).attr('href', $(element).attr('href').replace(/(redirect=)([^&]*)/, '$1' + window.location.pathname + window.location.hash));
        });
    };

    // make table rows clickable
    $('table.table.table-hover tr').on('click', function (event) {
        // do nothing when clicked on a link or input element
        if ('A' == event.target.tagName || $(event.target).is(':input')) {
            return;
        }
        if (typeof ($(this).data('href')) != 'undefined') {
            window.document.location = $(this).data('href');
        }
    });

    // navigate to active tab or pill
    $('.nav-tabs a[href="' + window.location.hash + '"]').tab('show');
    $('.nav-pills a[href="' + window.location.hash + '"]').tab('show');

    // adjust urls when activating other tab or pill
    $('.nav-tabs a').on('shown.bs.tab', adjustUrls);
    $('.nav-pills a').on('shown.bs.tab', adjustUrls);

    var filter = $('form.ajaxFilter');
    var lastFilterState = null;
    var postFilterForm = _.debounce(function () {
        var currentFilterState = filter.serialize();
        if (currentFilterState != lastFilterState) {
            lastFilterState = currentFilterState;
            filter.trigger('submit');
        }
    }, 300);
    filter > $(':input').on('keyup', postFilterForm);
    filter > $(':input').on('change', postFilterForm);
    filter.on('submit', function (event) {
        var url = window.location.pathname + '?' + filter.serialize();
        showLoader();
        $.get({
            url: url,
        }).done(function (response) {
            $('#ajaxContainer').html(response);
            hideLoader();
            window.history.replaceState({}, window.document.title, url);
        });
        event.preventDefault();
    });
});

showLoader = function () {
    $("#ajaxContainer").hide();
    $("#loader").show();
}

hideLoader = function () {
    $("#loader").hide();
    $("#ajaxContainer").show();
}
