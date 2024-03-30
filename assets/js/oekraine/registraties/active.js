$(function() {

    init();

    $('#ajaxContainer').on('click', 'table tbody tr', function(event) {
        // do nothing when NOT clicked on TD tag (for example: A or INPUT tag inside a table)
        if ('TD' !== event.target.tagName) {
            return;
        }
        if (typeof($(this).data('href')) != 'undefined') {
            window.document.location = $(this).data('href');
        }
    });

    $('#ajaxContainer').on('click', 'a.checkout-all', function() {
        var id = $(this).attr('data-id');
        checkoutAll(id);
    });

    $('#ajaxContainer').on('click', 'a.checkout', function(event) {
        $(event.target).closest('tr').hide();
        var id = $(this).closest('tr').attr('data-id');
        checkout(id);
    });

    $('#ajaxContainer').on('click', 'a.remove', function() {
        var id = $(this).closest('tr').attr('data-id');
        remove(id);
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

function checkoutAll(locatie_id) {
    var confirmed = confirm('Weet u zeker dat u iedereen wilt uitchecken?');
    if (confirmed) {
        $.post({
            url: '/oekraine/registraties/checkoutAll/'+locatie_id,
        }).done(function(data) {
            init();
            resetFilter();
        }).fail(function() {
            alert('Er is een fout opgetreden.')
        });
    }
};

function checkout(registratie_id) {
    $.post({
        url: '/oekraine/registraties/registratieCheckOut/'+registratie_id,
    }).done(function(data) {
        init();
        resetFilter();
    }).fail(function() {
        alert('Er is een fout opgetreden.')
    });
};

function remove(registratie_id) {
    var confirmed = confirm('Weet u zeker dat u deze registratie wilt verwijderen?');
    if (confirmed) {
        $.post({
            url: '/oekraine/registraties/'+registratie_id+'/delete',
        }).done(function(data) {
            init();
            resetFilter();
        }).fail(function() {
            alert('Er is een fout opgetreden.')
        });
    }
};

function addToQueue(id, property, event) {
    $(event.target).hide();
    $.post({
        url: '/oekraine/registraties/'+id+'/'+property+'/add',
    }).done(function(data) {
        init();
    }).fail(function() {
        alert('Er is een fout opgetreden.')
    });
};

function removeFromQueue(id, property, event) {
    $(event.target).hide();
    $.post({
        url: '/oekraine/registraties/'+id+'/'+property+'/del',
    }).done(function(data) {
        init();
    }).fail(function() {
        alert('Er is een fout opgetreden.')
    });
};

function enable(id, property, event, value) {

    if(value === undefined) {
        value = 1;
    }
    $(event.target).hide();
    $.post({
        url: '/oekraine/registraties/'+id+'/'+property+'/'+value,
    }).done(function(data) {
        if (data.property == 1)
        {
            $(event.target).prop('checked', data[property]);
        }
        else
        {
            init();
        }

        $(event.target).show();
    }).fail(function() {
        alert('Er is een fout opgetreden.')
    });
};

function disable(id, property, event) {
    $(event.target).hide();
    $.post({
        url: '/oekraine/registraties/'+id+'/'+property+'/0',
    }).done(function(data) {
        if($(event.target).attr['type'] == 'checkbox') {
            $(event.target).prop('checked', data[property]);
            $(event.target).show();
        } else {
            init();
        }

    }).fail(function() {
        alert('Er is een fout opgetreden.')
    });
};
