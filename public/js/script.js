$(function () {
    var _DEBUG = false;

    $('.sign a.delVote, .sign a.addVote').on('click', function (event) {
        event.preventDefault();
        if (_DEBUG) console.log("Klik opdaget!");

        var mode = $(this).attr('class');
        var ajaxURL = '';
        if (mode === 'addVote') {
            ajaxURL = addUrl;
        }
        else if (mode === 'delVote') {
            ajaxURL = delUrl;
        }
        else {
            console.log("Fejl med &lt;a&gt; class!");
        }

        // Spinning animation!
        $(this).addClass('loadingVote'); // html("Vent...")

        // Saving this element to later usage
        var signDiv = $(this).parent();
        var signID = signDiv.data('id');

        var formData = {
            '_token': $('meta[name="csrf-token"]').attr('content'),
            'sign': signID
        };
        if (_DEBUG) console.log("Token: " + formData['_token'] + " - sign: " + formData['sign']);


        $.ajax({
            url: ajaxURL,
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: [
                function (result) {
                    if (_DEBUG) {
                        console.log("Ajax request gennemført successfuldt!");
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
                        if (_DEBUG) console.log("fra del til add!");
                    }
                    else {
                        // Change the overall text to "done" and unwrap the <a> tag, making them unable to click it again
                        signDiv.find('a.addVote').addClass('delVote').removeClass('addVote loadingVote').removeAttr("href").css({
                            'cursor': 'pointer',
                            'pointer-events': 'none'
                        });
                        if (_DEBUG) console.log("fra add til del!");
                    }

                    // Sorts the divs according to the "data-count" attr inside the divs
                    $('.sign').sort(function (a, b) {
                        if (_DEBUG) console.log("Sorting the divs");
                        var contentA = parseInt($(a).attr('data-count'));
                        var contentB = parseInt($(b).attr('data-count'));
                        return contentA < contentB ? 1 : -1;
                    }).appendTo("#signs");

                }
            ],
            error: function () {
                console.log("FEJL!");
            }

        });

    });
});

/**
 * Oversættelse for CameraTag
 * Af: Troels Madsen
 */

CT_i18n = [];
CT_i18n[0] = "Til at optage med din mobiltelefon, besøg venligst <<url>> i browseren på din mobil.";
CT_i18n[1] = "Din mobiltelefon understøtter ikke uploading af video";
CT_i18n[2] = "Tjek venligst at du har Flash Player 11 eller højere installeret";
CT_i18n[3] = "Ude af stand til at indlejre videooptager. Tjek venligst at du har Flash Player 11 eller højere installeret";
CT_i18n[4] = "Vælg en metode til at sende din tegn";
CT_i18n[5] = "optag fra webcam";
CT_i18n[6] = "upload en fil";
CT_i18n[7] = "optag fra mobil";
CT_i18n[8] = "vink til kameraet";
CT_i18n[9] = "optager om";
CT_i18n[10] = "uploader...";
CT_i18n[11] = "klik for at stop optagelsen";
CT_i18n[12] = "klik for at springe gennemse over";
CT_i18n[13] = "Godkend";
CT_i18n[14] = "Optag igen";
CT_i18n[15] = "Se optagelsen";
CT_i18n[16] = "Vent venligst mens vi flytter pixels rundt";
CT_i18n[17] = "Uploadet"; //Kaldet Udgivet. Ændret så folk vil trykke på "indsend tegn"
CT_i18n[18] = "Skriv din <b>mobuilnummer</b> nedenunder og vi sender dig en link til optagelse fra mobiltelefon.";
CT_i18n[19] = "Send link til mobil";
CT_i18n[20] = "fortryd";
CT_i18n[21] = "Tjek din mobil for instruktioner til at optage";
CT_i18n[22] = "eller henvis din mobil til";
CT_i18n[23] = "smid fil her til at uploade";
CT_i18n[24] = "sender din besked";
CT_i18n[25] = "skriv venligst din mobilnummer!";
CT_i18n[26] = "det ser ikke ud til at være en gyldige mobilnummer";
CT_i18n[27] = "Ude af stand til at sende SMS.";
CT_i18n[28] = "Ingen kamera fundet";
CT_i18n[29] = "Ingen mikrofon fundet";
CT_i18n[30] = "Adgang til kamera afvist";
CT_i18n[31] = "Mistet forbindelse til server";
CT_i18n[32] = "Afspilning mislykkedes";
CT_i18n[33] = "Ude af stand til at forbinde";
CT_i18n[34] = "Ude af stand til at udgive din optagelse.";
CT_i18n[35] = "Ude af stand til at sende formular data.";
CT_i18n[36] = "uploader din video";
CT_i18n[37] = "henter video til afspilning";
CT_i18n[38] = "uploader";
CT_i18n[39] = "forbinder...";
CT_i18n[40] = "forhandler med din firewall...";
CT_i18n[41] = "Ah nej! Det ser ud til at din browser pausede optagelsen";