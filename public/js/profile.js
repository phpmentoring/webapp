var $country = $('#profile_country');
$country.change(function() {
    var $form = $(this).closest('form');
    var data = {};
    data[$country.attr('name')] = $country.val();
    $.ajax({
        url : $form.attr('action'),
        type: $form.attr('method'),
        data : data,
        success: function(html) {
            $('#profile_state').replaceWith(
                $(html).find('#profile_state')
            );
        }
    });
});