
//Prerequisites:

// require jQuery normally
const $ = require('jquery');
// create global $ and jQuery variables
global.$ = global.jQuery = $;

require('lodash')
require('bootstrap');
require('popper.js')
require('bootstrap/dist/css/bootstrap.min.css')
// require('bootstrap/less/bootstrap.less')

//App specific stuff.
require('../css/global.scss')
require('./global.js')

//External libs.
require('select2')
require('select2/dist/css/select2.min.css')


//Perform something...
$(document).ready(function() {
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
});
$(function () {
    $('[data-toggle="tooltip"]').tooltip()
})
$(document).on('select2:open', (e) => {
    const selectId = e.target.id

    $(".select2-search__field[aria-controls='select2-" + selectId + "-results']").each(function (
        key,
        value,
    ) {
        value.focus()
    })
})