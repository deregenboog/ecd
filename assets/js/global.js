$(function () {

    $('select').select2();

    // add select-all and select-none to multi-select fields
    $('select[multiple]').each(function() {
        let selectElm = $(this);

        let enableSelectAll = (typeof selectElm.attr('select-all') !== 'undefined')
        let enableSelectNone = (typeof selectElm.attr('select-none') !== 'undefined')

        if (enableSelectAll) {
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

        if (enableSelectNone) {
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

$(function() {
    // copy title from input to label
    $('input[type="radio"], input[type="checkbox"]').each(function() {

        if ($(this).attr('title')) {
            $(this).closest('label').attr('title', $(this).attr('title'));
        }
        if ($(this).attr('data-toggle')) {
            let labelElm = $(this).closest('label');
            labelElm.attr('data-toggle', 'tooltip');
            labelElm.attr('title', $(this).attr('data-original-title') );
            labelElm.tooltip();
            $(this).tooltip('disable');
        }
    })
});

$(function() {
    const $formToConfirm = $('#afsluiting'); // Target form ID
    const $afsluitingRedenDropdown = $('#afsluiting_reden'); // Dropdown ID for onchange event

    if ($formToConfirm.length) {
        // Define IDs for the modal and confirm button
        const modalId = 'afsluitingConfirmModal';
        const confirmButtonId = 'afsluitingConfirmBtn';

        const $modalElement = $('#' + modalId);
        const $confirmButton = $('#' + confirmButtonId);

        // Proceed only if the dropdown, modal, and confirm button exist for the onchange logic
        if ($afsluitingRedenDropdown.length && $modalElement.length && $confirmButton.length) {
            // Event listener for the dropdown change
            $afsluitingRedenDropdown.on('change', function() {
                // Check if the selected option's value is '5' (for "Foutieve invoer")
                if ($(this).find('option:selected').text() === 'Foutieve invoer'){
                    // Optional: populate modal body with specific text
                    $modalElement.find('.modal-body').html('<p>Wanneer ‘Foutieve invoer’ als afsluitreden wordt gekozen, verdwijnt deze gegevens en de daaraan gekoppelde gegevens uit beeld.<br/>Ze zijn niet weg, maar voor een gebruiker niet meer tevoorschijn te halen.<br/>Deze afsluitreden mag alleen gebruikt worden als iets verkeerd is ingevoerd (bv. bij een verkeerde naam). Anders niet.</p>');
                    $modalElement.modal('show'); // Show the modal
                }
            });
            // Click handler for the modal's confirm button
            // This submits the form when the modal is confirmed.
            $confirmButton.on('click', function() {
                $formToConfirm.get(0).submit(); // Submit the form programmatically
            });
        }
    }
});


showLoader = function () {
    $("#ajaxContainer").hide();
    $("#loader").show();
}

hideLoader = function () {
    $("#loader").hide();
    $("#ajaxContainer").show();
}
