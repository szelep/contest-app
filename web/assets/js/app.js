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
        var url = $(this).data('contest');
        loading();
        loadDataToModal(url, 'contest');
    })

    $('[data-post]').click(function () {
        var url = $(this).data('post');
        loading();
        loadDataToModal(url, 'post');
    })
})

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

function loading()
{
    $('.modal-body').html(html.loading);
}
