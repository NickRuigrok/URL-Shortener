setInterval("updateURLS()", 5000);

$('.url-shorten').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
    $('.url-shorten').removeClass('animated bounceInRight');
});

$('.url-shorten').on('click', function() {
    if($('.url-input').val() === undefined || $('.url-input').val() == "") {
        alert("You need to enter an URL...");
    } else {
        $('.url-input').removeClass('animated bounceInLeft');
        $('.url-input').addClass('animated rubberBand');
        $('.url-shorten').css('display', 'none');
        $('.url-copy').css('display', 'inline-block');
        $('.url-input').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
            $('.url-input').removeClass('animated rubberBand');
        });
        $('.url-input').focus(function () {
            this.select();
        });
    }
});

$('.url-copy').on('click', function() {
    $('.url-input').focus();
    document.execCommand("copy");
    $('.url-copy').css('display', 'none');
    $('.url-input').val("");
    $('.url-shorten').css('display', 'inline-block');
});

$('form.ajax').on('submit', function() {
    var form = $(this),
        url = form.attr('action'),
        type = form.attr('method'),
        data = {};

    form.find('[name]').each(function(){
        var input = $(this),
            name = input.attr('name'),
            value = input.val();

        data[name] = value;
    });

    $.ajax({
        url: url,
        type: type,
        data: data,
        success: function(response) {
            console.log(response);
            $('.url-input').val(response);
        }
    });

    return false;
});

function updateURLS() {
    $('#info-popular-table').load("index.php #info-popular-table");
}