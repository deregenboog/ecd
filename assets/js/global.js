//Originated from app.js
var ajaxInProgress = false;

$(function() {

    // console.log('start');

    var hash = window.location.hash;

    if(hash) {
        try {
            const id = hash.substr(1);
            const yourElement = document.getElementById(id);
            const y = yourElement.getBoundingClientRect().top + window.pageYOffset;
            window.scrollTo({top: y, behavior: 'smooth'});
        } catch(e)
        {
          //catch hash to non existent element. do nothing. #1096
          //   console.log(e);
        }

    }

    $(document).ajaxStart(function() {
        ajaxInProgress = true;
    });

    $( document ).ajaxStop(function() {
        ajaxInProgress = false;
    });

    var requiredCkEditor = function( evt ) {
        alert( 'Article content is required.' );
        evt.cancel();
    };

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

        makeCkEditorRequired();

    });
    /**
     * By default, a required attribute on a textarea is rendered by the browsers validation thing
     * But since CKEDITOR replaces the textarea and set it to display:none, the required attribute is gone.
     * Thus practically makes it bypass validation.
     *
     * Wo dont want that. Therefore we have to implement it manually via this function.
     * IMO this should be done default by CKEDITOR but who am i...
     *
     */
    var makeCkEditorRequired = function(){
        if( typeof CKEDITOR  !== "undefined" ) {
            for (var instanceName in CKEDITOR.instances) {
                var instance = CKEDITOR.instances[instanceName]
                instance.on('required', function (evt) {
                    evt.editor.showNotification('Dit is een verplicht veld.', 'info');//warning has to be removed manually while info dissapears after 5 seconds... which is more friendly.
                    evt.cancel();
                });
            }
        }
    };

    var adjustUrls = function(event) {
        window.history.replaceState({}, window.document.title, $(event.target).attr('href'));
        $('a[href*="redirect="]').each(function(index, element) {
           $(element).attr('href', $(element).attr('href').replace(/(redirect=)([^&]*)/, '$1'+encodeURIComponent(window.location.href)));
            console.log('Redirect set');
            $(element).attr('href', $(element).attr('href').replace(/(redirect=)([^&]*)/, '$1'+window.location.pathname+window.location.hash));
        });
    };

    // make table rows clickable
    $('table.table.table-hover tr').on('click', function(event) {
        // do nothing when clicked on a link or input element
        if ('A' == event.target.tagName || $(event.target).is(':input')) {
            return;
        }
        if (typeof($(this).data('href')) != 'undefined') {
            window.document.location = $(this).data('href');
        }
    });

    // navigate to active tab or pill
    $('.nav-tabs a[href="'+window.location.hash+'"]').tab('show');
    $('.nav-pills a[href="'+window.location.hash+'"]').tab('show');

    // adjust urls when activating other tab or pill
    $('.nav-tabs a').on('shown.bs.tab', adjustUrls);
    $('.nav-pills a').on('shown.bs.tab', adjustUrls);


    var filter = $('form.ajaxFilter');
    var lastFilterState = null;
    var postFilterForm = _.debounce(function() {
        var currentFilterState = filter.serialize();
        if (currentFilterState != lastFilterState) {
            lastFilterState = currentFilterState;
            filter.trigger('submit');
        }
    }, 300);
    filter > $(':input').on('keyup', postFilterForm);
    filter > $(':input').on('change', postFilterForm);
    filter.on('submit', function(event) {
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
global.showLoader = showLoader;
global.hideLoader = hideLoader;


//////////////////////////////////////////////////
//Originated from scripts.js
//////////////////////////////////////////////////

/*
 * used to store globals
 */
ecd_sandbox = {};
global.ecd_sandbox = ecd_sandbox;

//namespace declaration
var Ecd = {};
global.Ecd = Ecd;

/*
 * function used in /verslagen/add
 * to move around a hidden dropdown of metadata
*/
function treeRadioChanged(radioId, catId, doorverwijzerType, doNotclearValue){
    //finding the dropdown:
    var dropdown = $(radioId).closest('fieldset').find('.movableDropdown');

    // deciding if the checkbox concerns a field that allows the dropdown
    // (by checking if there is a span prepared to hold the dropdown
    containerSpanCount = $(radioId).closest('div').children('.dropdownContainer').size();
    if(containerSpanCount == 1 ){//append and show dropdown
        dropdown.fadeOut('fast', function(){
            $(radioId).closest('div').find('.dropdownContainer').append(dropdown);
            //enabling the proper dropdown
            toTurnOn = dropdown.children('select.'+doorverwijzerType);
            toTurnOn.removeAttr('disabled');
            toTurnOn.show();
            //disabling all other dropdowns:
            toTurnOff = dropdown.children('select[class!="'+doorverwijzerType+'"]');
            toTurnOff.attr('disabled', 'disabled');
            toTurnOff.hide();
            if (!doNotclearValue) {
                dropdown.children('select').val(''); //reseting the dropdown
            }
            dropdown.fadeIn();
        });
    }else{
        //radio with no dropdown available: hide & restart dropdown
        dropdown.fadeOut();
        if (!doNotclearValue) {
            dropdown.children('select').val('');
        }
        dropdown.children('select').attr('disabled', 'disabled');
    }
}
global.treeRadioChanged = treeRadioChanged;

//comparison function:
function checkboxAndValuesComparison(cellA,cellB){
    //first getting the values for the cells that contain numbers not checkboxes:
    valA = cellA.children('div').html();
    valB = cellB.children('div').html();
    //in case both values are null - both fields are checkboxes - compare their 'checked' attribute
    if(valA === null && valB === null){
        valA = cellA.find('input[type="checkbox"]').attr('checked');
        valB = cellB.find('input[type="checkbox"]').attr('checked');
        if (valA > valB) return -1;
        if (valA < valB) return 1;
        return 0;
    }//in case both values are not null - cast them to int and compare
    else if(valA !== null && valB !== null){
        valA = parseInt(valA);
        valB = parseInt(valB);
        if (valA < valB) return -1;
        if (valA > valB) return 1;
        return 0;
    }//if only one of the values is null the comparison is obvious
    else{
        if (valB === null) return -1;
        if (valA === null) return 1;
        return 0;
    }
}
global.checkboxAndValuesComparison = checkboxAndValuesComparison;

//sorting the shower list
ecd_sandbox.sortTableOnShowerOrder = function(){
    var sort_function = function(a,b){
        //getting the cells:
        cellA = $(a).children('td.doucheCol');
        cellB = $(b).children('td.doucheCol');
        //comparing the cells' contents
        return checkboxAndValuesComparison(cellA,cellB);
    }
    var rows = $('table tbody.active_registraties tr').get();
    rows.sort(sort_function);
    $('table tbody.active_registraties').append(rows);
    rows = $('table tbody.gebruikers tr').get();
    rows.sort(sort_function);
    $('table tbody.gebruikers').append(rows);

    //setting the last_sorted object:
    ecd_sandbox.last_sorted = {
        method: ecd_sandbox.sortTableOnShowerOrder
    }
}

//sorting the Maatschapelijk werk queue
ecd_sandbox.sortTableOnMwOrder = function(){
    var sort_function = function(a,b){
        //getting the cells:
        cellA = $(a).children('td.mwCol');
        cellB = $(b).children('td.mwCol');
        //comparing the cells' contents
        return checkboxAndValuesComparison(cellA,cellB);
    };

    var rows = $('table tbody.active_registraties tr').get();
    rows.sort(sort_function);
    $('table tbody.active_registraties').append(rows);

    rows = $('table tbody.gebruikers tr').get();
    rows.sort(sort_function);
    $('table tbody.gebruikers').append(rows);

    //setting the last_sorted object:
    ecd_sandbox.last_sorted = {
        method: ecd_sandbox.sortTableOnMwOrder
    }
}

//sorting the gebruikersruimte queue
ecd_sandbox.sortTableOnGbrvOrder = function(){
    var rows = $('table tbody.gebruikers tr').get();
    rows.sort(function(a,b){
        //getting the cells:
        cellA = $(a).children('td.gbrvCol');
        cellB = $(b).children('td.gbrvCol');
        //comparing the cells' contents
        return checkboxAndValuesComparison(cellA,cellB);
    });
    $('table tbody.gebruikers').append(rows);

    //setting the last_sorted object:
    ecd_sandbox.last_sorted = {
        method: ecd_sandbox.sortTableOnGbrvOrder
    }
}

/*
 * sorts achternaam and voornaam columns
 *
 * @no_order_swapping - use this when the function is an AJAX callback so that
 *                      it doesn't swap the sorting order on every ajax call
 *
 * @order             - used only by the applyLastSorting() to determine the
 *                      previous sorting order after the update (when the
 *                      classes are gone), use css class name string
 *
 * (NOTE! parameter column doesn't include the class '.',
 * but it's always a column CLASS)
 *
*/


ecd_sandbox.alphaSortTable = function(column, no_order_swapping, order){

    //dealing with classes and sorting order
    var ascending = true;
    var header_cell = $('table.sortable th.'+column);

    var asc_class_present = header_cell.hasClass('sort-order-asc');
    $('table.sortable th').removeClass('sort-order-desc');
    $('table.sortable th').removeClass('sort-order-asc');

    if(order){
        header_cell.addClass(order);
        if(order === 'sort-order-asc'){
            ascending = true;
        }else{
            ascending = false;
        }
    }else{
        if(!no_order_swapping){
            if(asc_class_present){
                ascending = false;
                header_cell.addClass('sort-order-desc');
                order = 'sort-order-desc';
            }else{
                ascending = true;
                header_cell.addClass('sort-order-asc');
                order = 'sort-order-asc';
            }

        }else{
            //if there's no order swapping - put the asc sorting class
            header_cell.addClass('sort-order-asc');
            order = 'sort-order-asc';
        }
    }
    //the sort function:
    var sort_function = function(a, b){
        if(ascending){
            var keyA = $(a).children('td.'+column).text().toUpperCase();
            var keyB = $(b).children('td.'+column).text().toUpperCase();
        }else{
            //swapping the operands to revert the sorting order
            keyA = $(b).children('td.'+column).text().toUpperCase();
            keyB = $(a).children('td.'+column).text().toUpperCase();
        }
        keyA = keyA.replace('(', '');
        keyB = keyB.replace('(', '');

        if (keyA < keyB) return -1;
        if (keyA > keyB) return 1;
        return 0;
    }

    //sorting (3 lists separately):
    var rows = $('table.sortable tbody.active_registraties tr').get();
    rows.sort(sort_function);
    $('table.sortable tbody.active_registraties').append(rows);

    rows = $('table tbody.gebruikers tr').get();
    rows.sort(sort_function);
    $('table.sortable tbody.gebruikers').append(rows);

    rows = $('table tbody.deregistered tr').get();
    rows.sort(sort_function);
    $('table.sortable tbody.deregistered').append(rows);

    //setting the last_sorted object:
    ecd_sandbox.last_sorted = {
        method: ecd_sandbox.alphaSortTable,
        args: [column, no_order_swapping, order]
    }
}

/*
 * applies the last used sort method on the registratielijst
 */
function applyLastSorting(){
    //when there was no sorting so far:
    if( typeof(ecd_sandbox.last_sorted) === 'undefined' )
        return;
    //if the method of sorting was set to something else than a function
    if(typeof(ecd_sandbox.last_sorted.method) !== 'function')
        return;

    //checking the arguments
    if(typeof(ecd_sandbox.last_sorted.args) === 'undefined')
        args = [];
    else
        args = ecd_sandbox.last_sorted.args;

    ecd_sandbox.last_sorted.method.apply(null, args);
}
global.applyLastSorting = applyLastSorting;

//timed ajax table filtering

var ajax_filter_call = null;
var ajax_filter_serialzed = null;
var timer = new Date();
var ajax_timer = timer.getTime();

//the function that should be called on event
function ajaxFilter(remote_function){
    if(ajax_filter_call != null){
        ajax_filter_call.abort(); //cancel the previous calls
    }
    timer = new Date();
    ajax_timer = timer.getTime();
    var local_timer = ajax_timer;
    setTimeout("if(check_time("+local_timer+"))ajax_filter_call = ajaxCall('"+remote_function+"');",500);
}
global.ajaxFilter = ajaxFilter;

//wrap of the jQuery ajax call
function ajaxCall(remote_function){
    // don't send request again if values have not changed
    if (ajax_filter_serialzed != null) {
        if ($('#filters').serialize() == ajax_filter_serialzed) {
            return;
        }
    }
    ajax_filter_serialzed = $('#filters').serialize();

    $.ajax({
        beforeSend:function (XMLHttpRequest) {
            $('#clientList').css('color','#999');
        },
        data:$('#filters').serialize(),
        dataType:'html',
        evalScripts:true,
        success:function (data, textStatus) {
            $('#contentForIndex').html(data);
        },
        type:'post',
        url: remote_function
    });
}
global.ajaxCall = ajaxCall;

//used inline in the setTimeout in the ajaxFilter
function check_time(local_timer){
    if(local_timer == ajax_timer) return true;
    else return false;
}
global.check_time = check_time;

//eo timed ajax table filtering

function set_checkbox(checkbox,data){
    if(data.new_val === 'undefined')
        return;

    if(data.new_val === data.prev_val)
        return;

    var checkbox = $('#'+checkbox);

    if(data.new_val === 0){
        checkbox.removeAttr('checked');
    }else{
        checkbox.attr('checked', 'checked');
    }
}
global.set_checkbox = set_checkbox;

/*
 * when editing/creating an intake this function takes care of disabling certain
 * fields when zonder verblijfstatus is selected
 *
 * @this - jQuery object containing the input
 *          (should be the recieved from $('#the_input').)
 * @changes - object:
 *      {
 *          input_value1: {
 *              element: element(s)_to_manipulate,
 *              action: on/off,
 *          },
 *          input_value2: {
 *              element: element(s)_to_manipulate,
 *              action: on/off,
 *          }
 *      }
 *
 * TODO: figure out a better name!!!!
 */

toggle_elements_on_input_change = function(changes){

    string2bool = function(input){
        string2bool = {'on':true, 'off':false};
        return string2bool[input];
    }

    //value of the input:
    val = this.val();

    //checking if the value exists in the changes "array"
    if(typeof(changes[val]) !== 'undefined'){

        the_element = $(changes[val]['element']);
        toggle = string2bool( changes[val]['action'] );


        if(typeof(the_element) !== 'undefined' && typeof(toggle) === 'boolean'
        ){
            the_element.attr('disabled', !toggle);
            return toggle;
        }
    }//changes[val] defined
}//toggle_elements_on_input_change
global.toggle_elements_on_input_change = toggle_elements_on_input_change;

//enhancing jquery objects with our function:
jQuery.prototype.toggle_elements_on_input_change =
    toggle_elements_on_input_change;

/*
 * Uses toggle_elements_on_input_change to disable/enable ondersteuning section
 * when particular options are selected from the Verblijfstatus dropdown.
 * It should be both: called at document load, and attached to the change event
 * of the dropdown.
 */

function verblijfstatus_toggle(input){
    var options = {
        1:{element: "#ondersteuning input", action: "on"},
        3:{element: "#ondersteuning input", action: "off"},
        4:{element: "#ondersteuning input", action: "on"},
        6:{element: "#ondersteuning input", action: "on"},
        7:{element: "#ondersteuning input", action: "off"},
        8:{element: "#ondersteuning input", action: "off"},
        "":{element: "#ondersteuning input", action: "on"},
    };

    result = $(input).toggle_elements_on_input_change(options);
    if (!result) {
        // When disabled, select 'Nee' by default.
        $('#ondersteuning :radio[value="0"]').attr('checked', 'checked');
    }
}
global.verblijfstatus_toggle = verblijfstatus_toggle;


/*
 * Shows and hides the e-mail addresses for ondersteuning radios.
 * See usage at intakes/add intakes/edit
 */

function ondersteuning_toggle_addresses(){
    $('#ondersteuning input[type="radio"]').bind('change', function(){
        var th = $(this);
        var parent_div = th.closest('div.input');

        //if the hidden field "ignore" is set to true ("1") we do not toggle
        //anything because the mail is not going to be sent

        //finding the hidden 'ignore' field
        var th_id = th.attr('id');
        var ignore_field = $('#' + th_id.substr(0, th_id.length-1) + 'Ignore');

        //when the "ignore" field doesn't exist we show the e-mail addresses
        //(this happens when adding new intake)
        if( !(ignore_field.length) ){
            var ignore = false;
        }else if(ignore_field.val() == 0){
            var ignore = false;
        }else{
            //when the 'ignore' field is present and its value is 1
            var ignore = true;
        }

        //if e-mail is not ignored toggle-show it on the event
        if(!ignore){
            if( th.val() === '1' ){
                parent_div.find('small').show();
            }else if( th.val() === "0" ){
                parent_div.find('small').hide();
            }
        }
    });
}
global.ondersteuning_toggle_addresses = ondersteuning_toggle_addresses;

/*
 * Asks the server whether the last checkout of the client with given @id was
 * within @h hours.
 * Returns:
 * true - user has been checked out within last @h hours
 * false - user has not been checked out recently
 */

function ajax_check_can_register(id, locatie_id, url){
    var result = undefined;
    $.ajax({
        async: false, //important - we have to wait for the ajax result!
        beforeSend:function (XMLHttpRequest) {
            $("#loading").css("display","block")
        },
        complete:function (XMLHttpRequest, textStatus) {
            $("#loading").css("display","none")
        },
        dataType:"json",
        evalScripts:true,
        success:function (data, textStatus) {
            result = data; //set the result
        },
        type:"get",
        url:url+"/"+id+"/"+locatie_id
    });
    return result;
}
global.ajax_check_can_register = ajax_check_can_register;



function update_indication_status(data, indicatie_button_id){
    if(data["aangevraagd"]){
        var button = $("#"+indicatie_button_id);
        //find the row of the button
        var tr = button.parents('tr');
        //move the row to the other tables
        $('#tableAppliedIndications > tbody').append(tr);
        //change the columns according to the specification of the other table
        $('td:eq(2)', tr).html(data['date']);
        $('td:eq(3)', tr).html(data['naam']);
        $('td:eq(4)', tr).html('<a href="../awbz/view/' + $('td:eq(0)', tr).html().trim() + '">Indicatie invoeren</a>');

    }
}
global.update_indication_status = update_indication_status;

/**
 * Removes the indication from the list. Ajax callback.
 *
 * @param object data       Data returned (success field shows whether the action succeeded)
 * @param string button_i9  Id of the button that was pressed
 *
 */
function delete_indication(data, button_id) {
    //not successful
    if (! data.success) return;

    //hide the row containing the button, when animation finished remove it
    $('#' + button_id).parents('tr').hide('fast', function() {$(this).remove()});
}
global.delete_indication = delete_indication;

var bedrijfSectorChange = function (){
    var idOfSector = this.id.match(/[0-9]+/gi)[1];
    if(this.value !== ''){
        //First take the option list and then populate the options we need after that select the value.
        var optionList = $('#Hi5IntakeBedrijfItems' + this.value).html();
        var oldValue = $('input#Hi5IntakeBedrijfitem'+idOfSector+'Id').val();
        $('select#Hi5IntakeBedrijfitem'+idOfSector+'Id').html(optionList).val(oldValue);
        $('#BedrijfItems'+idOfSector).show();
    }else{
        $('select#Hi5IntakeBedrijfitem'+idOfSector+'Id').html();
        $('#BedrijfItems'+idOfSector).hide();
    }
}
global.bedrijfSectorChange = bedrijfSectorChange;

//DEPRECATED REMOVE LATER.
// var hi5DropdownChange = function(){
//     var idOfDropdown = this.id.match(/[0-9]+/gi)[1]; //hi5whatever_{id} and we need only the id but we have that 5 thing.
//     $('#Hi5Answer_hidden_'+idOfDropdown).attr('name','data[Hi5Answer]['+this.value+']' ).val(1);
// }
// function instantiateHi5Intake(){
//     // Trigger the events after setting the callbacks for them
//     $('#Hi5IntakeBedrijfsector1').change(bedrijfSectorChange).change();
//     $('#Hi5IntakeBedrijfsector2').change(bedrijfSectorChange).change();
//     $('fieldset#survey select').change(hi5DropdownChange).change();
// }


function startManagementReports() {
    console.log('startManagementReports');
    $('form input[type=submit]').on('click', function(e) {
        console.log('click');
        var form = $(this).parents('form');
        if ($('#optionsExcel').is(':checked')) {
            form.attr('target', 'iframeExcel');
        } else {
            e.preventDefault();
            $('#divAjaxLoading').show();
            $(this).attr('disabled', 'disabled');
            button=$(this);
            $('#divManagementReport').html('');
            $.ajax({
                type: 'post',
                url: form.attr('action'),
                data: form.serialize(),
                success: function(data) {
                    $('#divManagementReport').html(data);
                    //$('#optionsManagementForm input[type=submit]').removeAttr('disabled');
                    button.removeAttr('disabled');
                    $('#divAjaxLoading').hide();
                }
            });
        }

    });
}
global.startManagementReports = startManagementReports;

function startVeegploegReports() {
    console.log('startVeegploegReports');
    $('form input[type=submit]').on('click', function(e) {
        console.log('click');
        var form = $(this).parents('form');
        if ($('#optionsExcel').is(':checked')) {
            form.attr('target', 'iframeExcel');
        } else {
            e.preventDefault();
            $('#divAjaxLoading').show();
            $(this).attr('disabled', 'disabled');
            button=$(this);
            $('#divVeegploegReport').html('');
            $.ajax({
                type: 'post',
                url: form.attr('action'),
                data: form.serialize(),
                success: function(data) {
                    $('#divVeegploegReport').html(data);
                    //$('#optionsManagementForm input[type=submit]').removeAttr('disabled');
                    button.removeAttr('disabled');
                    $('#divAjaxLoading').hide();
                }
            });
        }

    });
}
global.startVeegploegReports = startVeegploegReports;


/**
 * Page loaders for any page. Just look up the id of the body on a page
 * (which is a contact of the controller and the action) and add a function
 * to this object on this name. When the page is loaded your function will be
 * called.
 *
 *
 */

Ecd.pageLoaders = {
    init: function () {
        var bodyId = $(document.body).attr('id');
        var func = Ecd.pageLoaders[bodyId];
        if (func && typeof func == 'function') {
            func();
        }

    }
};

$(Ecd.pageLoaders.init);


Ecd.pageLoaders.klantenAdd = function () {
    showHideWarning();
    $('#KlantLandId').change(showHideWarning);
    $('#KlantDoorverwijzenNaarAmoc').click(showHideWarning);

    function showHideWarning() {
        var selectedCountry = parseInt($('#KlantLandId').val());
        var toAmoc = $('#KlantDoorverwijzenNaarAmoc').is(':checked');
        if (amocCountries.indexOf(selectedCountry) > -1 || toAmoc) {
            $('.amocLandWarning').show();
        } else {
            $('.amocLandWarning').hide();
        }
    }
}

Ecd.pageLoaders.klantenEdit = Ecd.pageLoaders.klantenAdd;

Ecd.pageLoaders.registratiesIndex = function() {
    //button stays in place when scrolling
    $('#imgScrollup').fixFloat();

    //only show the button if the page header is not visible (scrolled down)
    $(window).scroll(function() {
        if ($(window).scrollTop() > 0) {
            $('#imgScrollup').show();
        } else {
            $('#imgScrollup').hide();
        }
    });

    //clicking it will scroll up
    $('#imgScrollup').on('click', function () {
        //scroll to the top using animation
        $('html,body').animate({scrollTop: 0}, 400);
    });
}

Ecd.pageLoaders.schorsingenAdd = function() {
    //show-hide textbox on checkbox check
    $('#RedenReden100').on('click', function () {
        if ($(this).is(':checked')) {
            $('.overig_reden').slideDown('fast');
        } else {
            $('.overig_reden').slideUp('fast');
        }
    });
    //show the textbox on load if the checkbox is checked
    if ($('#RedenReden100').is(':checked')) {
        $('.overig_reden').show();
    }
}

//use the same as the add
Ecd.pageLoaders.schorsingenEdit = Ecd.pageLoaders.schorsingenAdd;

function printLetter(url) {
    window.open(url);
}
global.printLetter = printLetter;

Ecd.supportgroup = function() {
    init();
    function init() {
        $('#hoofdclient_toggle').unbind();
        $('#hoofdclient_toggle').click(hoofdclient_toggle);
    }
    function hoofdclient_toggle(event) {
        if ( $('#hoofdclient_toggle').attr('checked')) {
            $('.section_hoofdclient').show();
            $('.section_supportclient').hide();
            $('#hoofdclient_toggle_hidden').val(1)
        } else {
            $('.section_hoofdclient').hide();
            $('.section_supportclient').show();
            $('#hoofdclient_toggle_hidden').val(0)
        }
    }

}

Ecd.supportgroup_list = function() {
    var options = Ecd.supportgroup_list.options;
    init();

    function init() {
        console.log(options['remove']);
        $('#select_clienten').change(function(){
            id=$('#'+options.dropdown).val();
            if( id != "") {
                text=$('#'+options.dropdown+" option:selected").text();
                options.selected[id] = text;
                write();
            }
        });
        write();
    }

    function removeTableRow(e){
        removeid=$(this).attr('id');
        var myRe = new RegExp('removelist', "gim");
        id=removeid.replace(myRe , '');
        delete options.selected[id];
        write()
        $("#select_clienten").val('');
        return false;
    }


    function write() {
        data="";
        cnt=0;
        more = false
        for(var k in options.selected) {
            more = true;
            if(options.selected.hasOwnProperty(k)) {
                more = true;
                removeid='removelist'+k;
                data+='<span id="selectlist'+k+'">';
                data+='<input type="hidden" name="data[PfoClientenSupportgroup]['+cnt+'][pfo_supportgroup_client_id]" value="'+k+'"/>';
                data+=options.selected[k]+"&nbsp;";
                data+='<a id="'+removeid+'">'+options['remove']+'</a> &nbsp;';
                data+="</span>";
                cnt++;
            }
        }
        $('#selected_list').html(data);
        for(var k in options.selected) {
            if(options.selected.hasOwnProperty(k)) {
                removeid='removelist'+k;
                $('#'+removeid).click(removeTableRow);
            }
        }
        if(more) {
            console.log('disable checkbox');
            $("#hoofdclient_toggle").attr("disabled", true);
        } else {
            console.log('enable checkbox');
            $("#hoofdclient_toggle").removeAttr("disabled");
        }
    }
}



Ecd.verslag = function(tag,url) {
    addhandlers(tag);
    function addhandlers(tag) {
        save='verslag_save'+tag;
        cancel='verslag_cancel'+tag;
        toevoegen='toevoegen'+tag;

        $('#'+save).on('click', function(){
            tag=$(this).attr('id');
            var myRe = new RegExp('verslag_save', "gim");
            tag = tag.replace(myRe , '');
            form="form_verslag"+tag;
            var data = $('#'+form).find('*:input').serialize();
            $.post(url, data, function (data) {
                div="verslag"+tag;
                $('#'+div).html(data);
            });
        });
        $('#'+toevoegen).on('click', function(){
            tag=$(this).attr('id');
            var myRe = new RegExp('toevoegen', "gim");
            tag = tag.replace(myRe , '');
            $('#view_verslag'+tag).hide();
            $('#edit_verslag'+tag).show();
        });
        $('#'+cancel).on('click', function(){
            tag=$(this).attr('id');
            var myRe = new RegExp('verslag_cancel', "gim");
            tag = tag.replace(myRe , '');
            $('#view_verslag'+tag).show();
            $('#edit_verslag'+tag).hide();
        });
    }
}

Ecd.zrm_widget = function(module) {

    var options = Ecd.zrm_widget.options;

    set_required(module);
    function set_required(module) {
        $('.zrm').find('tr').attr('class','');
        if(options.hasOwnProperty(module)) {

            for (index = 0; index < options[module].length; index++) {
                $("#tr_"+options[module][index]).attr('class','zrmedit');
            }
        }
    }

    $('#ZrmReportRequestModule').change(function(e) {
        set_required($('#ZrmReportRequestModule').val());
    });

}

Ecd.intake = function() {
    $('#IntakeLocatie1Id').change(function() {
        $('input[type="checkbox"][name="data[Intake][toegang_inloophuis]"]').attr( "checked", true );
    });
    $('input[type="checkbox"][name="data[Intake][toegang_inloophuis]"]').change(function() {
        if($('input[type="checkbox"][name="data[Intake][toegang_inloophuis]"]').attr( "checked" ) == false) {
            $('#IntakeLocatie1Id').val('');
        }
    });
    console.log('ecd.intake');
}
Ecd.schorsing = function() {
    options = Ecd.schorsing.options;

    function clear_betrokkenen() {
        $('#SchorsingAggressieDoelwit').val('');
        $('#SchorsingAggressieDoelwit2').val('');
        $('#SchorsingAggressieDoelwit3').val('');
        $('#SchorsingAggressieDoelwit4').val('');

        $('input[type="radio"][name="data[Schorsing][aggressie_tegen_medewerker]"]').each(function(i) {
            this.checked = false;
        });
        $('input[type="radio"][name="data[Schorsing][aggressie_tegen_medewerker2]"]').each(function(i) {
            this.checked = false;
        });
        $('input[type="radio"][name="data[Schorsing][aggressie_tegen_medewerker3]"]').each(function(i) {
            this.checked = false;
        });
        $('input[type="radio"][name="data[Schorsing][aggressie_tegen_medewerker4]"]').each(function(i) {
            this.checked = false;
        });

    }
    function update() {
        show=false;
        if($('#RedenReden'+options['violent_options'][0].toString()).attr('checked')) {
            show=true;
        }
        if($('#RedenReden'+options['violent_options'][1].toString()).attr('checked')) {
            show=true;
        }
        if(show) {
            $('#agressie').show();
        } else {
            $('#agressie').hide();
            clear_betrokkenen();
            $('input[type="radio"][name="data[Schorsing][agressie]"]').each(function(i) {
                this.checked = false;
            });
        }
        if($('input[name="data[Schorsing][agressie]"]:checked').val() == 1 ) {
            $('#betrokkenen').show();
        } else {
            $('#betrokkenen').hide();
            clear_betrokkenen();
        }
    }
    $('input[name="data[Schorsing][agressie]"]').on('click', function() {
        update();
    });
    $('#RedenReden'+options['violent_options'][0].toString()).change(function() {
        update();
    });
    $('#RedenReden'+options['violent_options'][1].toString()).change(function() {
        update();
    });
    update();
}

