const $ = require('jquery');

require('bootstrap');
require('../css/global.scss');
require('symfony-collection');

$(document).ready(function (){
    $("button[data-contest]").click(function () {
        var url = $(this).data('contest');
        loadDataToModal(url);
    })

})

function loadDataToModal(url)
{
    $.get( url, function( data ) {
        $( ".modal-body" ).html( data );
        $('.files-box').collection({
            max: maxFiles,
            add_at_the_end: true,
            init_with_n_elements: 1,
            hide_useless_buttons: true
        });
      });
}
