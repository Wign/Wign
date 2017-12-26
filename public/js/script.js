$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(function () {
    var _DEBUG = false;

    $('.sign a.delVote, .sign a.addVote').on('click', function (event) {
        event.preventDefault();
        if (_DEBUG) console.log("Click detected!");

        var mode = $(this).attr('class');
        var ajaxURL = '';
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
        var signDiv = $(this).parent();
        var signID = signDiv.data('id');

        var formData = {
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
                            var contentA = parseInt($(a).attr('data-count'));
                            var contentB = parseInt($(b).attr('data-count'));
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

/**
 * Translation of CameraTag to Danish
 * @author Troels Madsen
 */

// i18n
if (typeof(CT_i18n) == "undefined") {
    CT_i18n = []
}
CT_i18n = [];
CT_i18n[0] = CT_i18n[0] || "Til at optage med din mobiltelefon, besøg venligst <<url>> i browseren på din mobil.";
CT_i18n[1] = CT_i18n[1] || "Din mobiltelefon understøtter ikke uploading af video";
CT_i18n[2] = CT_i18n[2] || "Tjek venligst at du har Flash Player 11 eller højere installeret";
CT_i18n[3] = CT_i18n[3] || "Ude af stand til at indlejre videooptager. Tjek venligst at du har Flash Player 11 eller højere installeret";
CT_i18n[4] = CT_i18n[4] || "Vælg en metode til at sende din tegn";
CT_i18n[5] = CT_i18n[5] || "optag fra webcam";
CT_i18n[6] = CT_i18n[6] || "upload en fil";
CT_i18n[7] = CT_i18n[7] || "optag fra mobil";
CT_i18n[8] = CT_i18n[8] || "vink til kameraet";
CT_i18n[9] = CT_i18n[9] || "optager om";
CT_i18n[10] = CT_i18n[10] || "uploader...";
CT_i18n[11] = CT_i18n[11] || "klik for at stop optagelsen";
CT_i18n[12] = CT_i18n[12] || "klik for at springe gennemse over";
CT_i18n[13] = CT_i18n[13] || "Godkend";
CT_i18n[14] = CT_i18n[14] || "Optag igen";
CT_i18n[15] = CT_i18n[15] || "Se optagelsen";
CT_i18n[16] = CT_i18n[16] || "Vent venligst mens vi flytter pixels rundt";
CT_i18n[17] = CT_i18n[17] || "Uploadet"; //Called "Udgivet" before. Changed so people want to click on "Send sign" after it.
CT_i18n[18] = CT_i18n[18] || "Skriv din <b>mobuilnummer</b> nedenunder og vi sender dig en link til optagelse fra mobiltelefon.";
CT_i18n[19] = CT_i18n[19] || "Send link til mobil";
CT_i18n[20] = CT_i18n[20] || "fortryd";
CT_i18n[21] = CT_i18n[21] || "Tjek din mobil for instruktioner til at optage";
CT_i18n[22] = CT_i18n[22] || "eller henvis din mobil til";
CT_i18n[23] = CT_i18n[23] || "smid fil her til at uploade";
CT_i18n[24] = CT_i18n[24] || "sender din besked";
CT_i18n[25] = CT_i18n[25] || "skriv venligst din mobilnummer!";
CT_i18n[26] = CT_i18n[26] || "det ser ikke ud til at være en gyldige mobilnummer";
CT_i18n[27] = CT_i18n[27] || "Ude af stand til at sende SMS.";
CT_i18n[28] = CT_i18n[28] || "Ingen kamera fundet";
CT_i18n[29] = CT_i18n[29] || "Ingen mikrofon fundet";
CT_i18n[30] = CT_i18n[30] || "Adgang til kamera afvist";
CT_i18n[31] = CT_i18n[31] || "Mistet forbindelse til server";
CT_i18n[32] = CT_i18n[32] || "Afspilning mislykkedes";
CT_i18n[33] = CT_i18n[33] || "Ude af stand til at forbinde";
CT_i18n[34] = CT_i18n[34] || "Ude af stand til at udgive din optagelse.";
CT_i18n[35] = CT_i18n[35] || "Ude af stand til at sende formular data.";
CT_i18n[36] = CT_i18n[36] || "uploader din video";
CT_i18n[37] = CT_i18n[37] || "henter video til afspilning";
CT_i18n[38] = CT_i18n[38] || "uploader";
CT_i18n[39] = CT_i18n[39] || "forbinder...";
CT_i18n[40] = CT_i18n[40] || "forhandler med din firewall...";
CT_i18n[41] = CT_i18n[41] || "Ah nej! Det ser ud til at din browser pausede optagelsen";
CT_i18n[42] = CT_i18n[42] || "Dette ser ikke ud til at være en valide videofil. Forsæt alligevel?";
CT_i18n[43] = CT_i18n[43] || "Optag eller upload en video";
CT_i18n[44] = CT_i18n[44] || "Tryk for at starte";
CT_i18n[45] = CT_i18n[45] || "Vælg en metod til at indsende din foto";
CT_i18n[46] = CT_i18n[46] || "Tag fra webcam";
CT_i18n[47] = CT_i18n[47] || "Upload en fil";
CT_i18n[48] = CT_i18n[48] || "Vælg hvilken kamera og mikrofon du gerne vil bruge";
CT_i18n[49] = CT_i18n[49] || "Tryk her for at tage billedet, eller upload en billede";
CT_i18n[50] = CT_i18n[50] || "Billedejustering / filtere";
CT_i18n[51] = CT_i18n[51] || "Panorering & Zoom";
CT_i18n[52] = CT_i18n[52] || "Røg";
CT_i18n[53] = CT_i18n[53] || "Amerikanisering";
CT_i18n[54] = CT_i18n[54] || "Lysstyrke / Kontrast";
CT_i18n[55] = CT_i18n[55] || "Nattesyn";
CT_i18n[56] = CT_i18n[56] || "Posterisere";
CT_i18n[57] = CT_i18n[57] || "Zink";
CT_i18n[58] = CT_i18n[58] || "Bær";
CT_i18n[59] = CT_i18n[59] || "Spion Kamera";
CT_i18n[60] = CT_i18n[60] || "Magasin";
CT_i18n[61] = CT_i18n[61] || "Dobbeltskravering";
CT_i18n[62] = CT_i18n[62] || "Lysglimt";
CT_i18n[63] = CT_i18n[63] || "Farvetone / Farvemætning";
CT_i18n[64] = CT_i18n[64] || "Farvedynamik";
CT_i18n[65] = CT_i18n[65] || "Fjern støj";
CT_i18n[66] = CT_i18n[66] || "Sløring";
CT_i18n[67] = CT_i18n[67] || "Støj";
CT_i18n[68] = CT_i18n[68] || "Sepia";
CT_i18n[69] = CT_i18n[69] || "Vignet";
CT_i18n[70] = CT_i18n[70] || "Zoom-sløring";
CT_i18n[71] = CT_i18n[71] || "Triangl-sløring";
CT_i18n[72] = CT_i18n[72] || "Tilt Shift";
CT_i18n[73] = CT_i18n[73] || "Objektiv-sløring";
CT_i18n[74] = CT_i18n[74] || "Swirl";
CT_i18n[75] = CT_i18n[75] || "Bule / Spids";
CT_i18n[76] = CT_i18n[76] || "Blæk";
CT_i18n[77] = CT_i18n[77] || "Kantarbejde";
CT_i18n[78] = CT_i18n[78] || "Sekskantet pixelering";
CT_i18n[79] = CT_i18n[79] || "Plettet skærm";
CT_i18n[80] = CT_i18n[80] || "Farvet halvtone";
CT_i18n[82] = CT_i18n[82] || "Vinkel";
CT_i18n[83] = CT_i18n[83] || "Størrelse";
CT_i18n[84] = CT_i18n[84] || "Skalering";
CT_i18n[85] = CT_i18n[85] || "Radius";
CT_i18n[86] = CT_i18n[86] || "Styrke";
CT_i18n[87] = CT_i18n[87] || "Lysstyrke";
CT_i18n[88] = CT_i18n[88] || "Sløringsradius";
CT_i18n[89] = CT_i18n[89] || "Gradientsradius";
CT_i18n[90] = CT_i18n[90] || "Farvetone";
CT_i18n[91] = CT_i18n[91] || "Farvemætning";
CT_i18n[92] = CT_i18n[92] || "Bevægelse";
CT_i18n[93] = CT_i18n[93] || "Antal farver";
CT_i18n[94] = CT_i18n[94] || "Gamma";
CT_i18n[95] = CT_i18n[95] || "Farve";
CT_i18n[96] = CT_i18n[96] || "Lystæthed";
CT_i18n[97] = CT_i18n[97] || "Kontrast";
CT_i18n[98] = CT_i18n[98] || "Fyldning";
CT_i18n[99] = CT_i18n[99] || "Ude af stand til at aktivere kamera eller mikrofon";
CT_i18n[100] = CT_i18n[100] || "Venter på hardware";
CT_i18n[101] = CT_i18n[101] || "Forsætte optagelsen";
CT_i18n[102] = CT_i18n[102] || "Optagelsen på pause";
CT_i18n[103] = CT_i18n[103] || "vælg en nedenstående metode til at indsende din lyd";
CT_i18n[104] = CT_i18n[104] || "optag fra mikrofon";
CT_i18n[105] = CT_i18n[105] || "Hvilken mikrofon vil du gerne bruge";
CT_i18n[106] = CT_i18n[106] || "denne video er længere end den maksimal tilladt varighed på ## sekunder. Prøv lige igen.";
CT_i18n[107] = CT_i18n[107] || "Det ser ikke ud til at være en valid lydfil. Forsæt alligevel?";
CT_i18n[108] = CT_i18n[108] || 'Vi er ude af stand til at forstå filen du valgte. Prøv lige igen.';
CT_i18n[109] = CT_i18n[109] || 'Denne Chrome version understøtter ikke adgang til kamera fra usikre kilder. Brug venligst https://';