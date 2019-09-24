$(function() {

    init();

    $('#ajaxContainer').on('click', 'table tbody tr', function(event) {
        // do nothing if an A-tag was clicked
        if ('A' === event.target.tagName) {
            return;
        }

        var row = $(this).closest('tr');
        var klant_id = row.attr('data-id');

        row.addClass('info');
        $.get({
            url: '/inloop/registraties/jsonCanRegister/'+klant_id+'/'+locatie_id,
        }).done(function(response) {
            if (response.allow && response.confirm){
                var confirmed = confirm(response.message);
            } else if(response.allow){
                var confirmed = true;
            } else {
                var confirmed = false;
                alert(response.message);
            }

            if (confirmed) {
                checkin(klant_id, locatie_id);
            }
        }).fail(function() {
            alert('Er is een fout opgetreden.')
        }).always(function() {
            row.removeClass('info');
        });
    });
});

function init() {
    showLoader();
    $.get({
        url: window.location.href+'&ajax=1',
    }).done(function(response) {
        $('#ajaxContainer').html(response);
        hideLoader();
    });
}

function resetFilter() {
    $('form.ajaxFilter :input').val('');
}

function checkin(klant_id, locatie_id) {
    $.post({
        url: '/inloop/registraties/ajaxAddRegistratie/'+klant_id+'/'+locatie_id,
    }).done(function(data) {
        resetFilter();
        init();
    }).fail(function() {
        alert('Er is een fout opgetreden.')
    });
};
