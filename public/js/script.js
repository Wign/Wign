$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(function () {
    const _DEBUG = false;

    $('.sign a.delVote, .sign a.addVote').on('click', function (event) {
        event.preventDefault();
        if (_DEBUG) console.log("Click detected!");

        let mode = $(this).attr('class');
        let ajaxURL = '';
        if (mode === 'addVote') {
            ajaxURL = addUrl;
        }
        else if (mode === 'delVote') {
            ajaxURL = delUrl;
        }
        else {
            console.log("We got a error with &lt;a&gt; class of the add/delete vote button!");
        }

        // Spinning animation!
        $(this).addClass('loadingVote');

        // Saving this element to later usage
        let signDiv = $(this).parent();
        let signID = signDiv.data('id');

        let formData = {
            'sign': signID,
            '_token': $('meta[name="csrf-token"]').attr('content')
        };
        if (_DEBUG) console.log("Sign ID: " + formData['sign']);

        $.ajax({
            url: ajaxURL,
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: [
                function (result) {
                console.log(result);
                    if (result.status = 'success') {
                        if (_DEBUG) {
                            console.log("Ajax request succeed! Result:");
                            console.log(result.msg);
                            console.log(result.votes);
                        }

                        signDiv.attr('data-count', result.votes); // Update data-count in parent div
                        signDiv.find('span.count').text(result.votes); // Change the text inside the div
                        if (signDiv.find('a').hasClass('delVote')) {
                            // Change the overall text to "done" and unwrap the <a> tag, making them unable to click it again
                            signDiv.find('a.delVote').addClass('addVote').removeClass('delVote loadingVote').removeAttr("href").css({
                                'cursor': 'pointer',
                                'pointer-events': 'none'
                            });
                            if (_DEBUG) console.log("CHANGE FROM del TO add!");
                        }
                        else {
                            // Change the overall text to "done" and unwrap the <a> tag, making them unable to click it again
                            signDiv.find('a.addVote').addClass('delVote').removeClass('addVote loadingVote').removeAttr("href").css({
                                'cursor': 'pointer',
                                'pointer-events': 'none'
                            });
                            if (_DEBUG) console.log("CHANGE FROM add TO del!");
                        }

                        // Sorts the divs according to the "data-count" attr inside the divs
                        $('.sign').sort(function (a, b) {
                            if (_DEBUG) console.log("Sorting the divs");
                            let contentA = parseInt($(a).attr('data-count'));
                            let contentB = parseInt($(b).attr('data-count'));
                            return contentA < contentB ? 1 : -1;
                        }).appendTo("#signs");

                    }
                    else {
                        console.log("ERROR!");
                        console.log(result.msg);
                    }
                }
            ],
            error: function (exception) {
                console.log("ERROR!");
                console.log("Exception:" + exception);
            }

        });

    });
});