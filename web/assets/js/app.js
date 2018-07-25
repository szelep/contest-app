const $ = require('jquery');

require('bootstrap');
require('../css/global.scss');
require('../js/custom.js');
require('symfony-collection');

var html = {
    'loading': '<div class="d-flex justify-content-center"><i style="font-size:5em" class="fa fa-circle-o-notch fa-spin"></i></div>',
    'buttonAdd': '<a href="#" class="btn btn-success btn-sm">Dodaj</a>',
    'buttonRemove': '<a href="#" class="d-flex justify-content-center btn btn-danger btn-sm">Usuń</a>'
}

$(document).ready(function (){
    $("button[data-contest]").click(function () {
        setModalWidth('addNew');
        var url = $(this).data('contest');
        loading();
        loadDataToModal(url, 'contest');
    })

    $('[data-post]').click(function () {
        setModalWidth('showPost');
        var url = $(this).data('post');
        loading();
        loadDataToModal(url, 'post');
    })
})

$(document).on('click', 'button[data-send]', function (e){
    e.preventDefault();

    var form = $(this).closest('form');

    $.ajax(
        {
            url: form.find('button[type="submit"]').data('href'),
            type: "POST",
            data: form.serialize(),
            success: function (result)
            {
                cleanInputs(form);
            },
            error: function (result)
            {
                //maybe coś
            }
        })
        .always(function (result)
        {
            cleanErrors();
            alertFadeIn();
            writeErrors(result);
            alertFadeOut();
        });
})


function alertFadeOut()
{
    $('#error-box').delay(2000).fadeOut("fast");
}

function alertFadeIn()
{
    $('#error-box').fadeIn("fast"); 
}

function cleanInputs(item)
{
    item.find('textarea, input[type="text"]').val('');
}

function writeErrors(errors)
{
    var errorHtml = '<div class="alert alert-' + (errors.status === 'error' ? 'danger' : 'success') + '">' + 
    parseErrorArray(errors) + '</div>';
    $('#error-box').html(errorHtml);
}

function parseErrorArray(errors)
{
    var errorString = '';

    for (i = 0; i < errors.message.length ; i++) {
        errorString += errors.message[i] + '<br>';
    }

    return errorString;

}

function cleanErrors()
{
    $('#error-box').html('');
}

/**
 * Zmiana szerokości modalu.
 * 
 * @param {string} state 
 */
function setModalWidth(state)
{
    var modalDialog = $('.modal-dialog');
    switch (state) {
        case 'addNew': modalDialog.removeClass('modal-lg'); break;
        case 'showPost': modalDialog.addClass('modal-lg'); break;
    }
}

/**
 * Ładuje dane do modalu.
 * 
 * @param {string} url 
 * @param {string} item 
 */
function loadDataToModal(url, item)
{
    $.get( url, function( data ) {
        $( ".modal-body" ).html( data );
        if ('contest' === item) {
            $('.files-box').collection({
                max: maxFiles,
                add_at_the_end: true,
                init_with_n_elements: 1,
                hide_useless_buttons: true,
                allow_up: false,
                allow_down: false,
                add: html.buttonAdd,
                remove: html.buttonRemove
            });
        }   
    });
}

/**
 * Pokazuje animację ładowania w modalu.
 */
function loading()
{
    $('.modal-body').html(html.loading);
}
