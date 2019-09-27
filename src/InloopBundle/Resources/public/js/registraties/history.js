$(function() {

    init();

    $('#ajaxContainer').on('click', 'table tbody tr', function(event) {
        // do nothing when NOT clicked on SPAN/TD tag (for example: A or INPUT tag inside a table)
        if (-1 === $.inArray(event.target.tagName, ['TD', 'SPAN'])) {
            return;
        }

        var row = $(this).closest('tr');
        var klant_id = row.attr('data-klant-id');

        row.addClass('info');

        $.get({
            url: '/inloop/registraties/jsonCanRegister/'+$klant_id+'/'+locatie_id,
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

    $.each(['douche', 'kleding', 'maaltijd', 'veegploeg', 'activering', 'mw'], function(i, property) {
        $('#ajaxContainer').on('click', 'input.'+property, function(event) {
            var id = $(this).closest('tr').attr('data-registratie-id');
            if ($(this).is(':checked')) {
                enable(id, property, event);
            } else {
                disable(id, property, event);
            }
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

function checkin(klant_id, locatie_id) {
    $.post({
        url: '/inloop/registraties/ajaxAddRegistratie/'+klant_id+'/'+locatie_id,
    }).done(function(data) {
        init();
    }).fail(function() {
        alert('Er is een fout opgetreden.')
    });
};

function enable(id, property, event) {
    $(event.target).hide();
    $.post({
        url: '/inloop/registraties/'+id+'/'+property+'/1',
    }).done(function(data) {
        $(event.target).prop('checked', data[property]);
        $(event.target).show();
    }).fail(function() {
        alert('Er is een fout opgetreden.')
    });
};

function disable(id, property, event) {
    $(event.target).hide();
    $.post({
        url: '/inloop/registraties/'+id+'/'+property+'/0',
    }).done(function(data) {
        $(event.target).prop('checked', data[property]);
        $(event.target).show();
    }).fail(function() {
        alert('Er is een fout opgetreden.')
    });
};
