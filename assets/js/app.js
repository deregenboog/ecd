
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