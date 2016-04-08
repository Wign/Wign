// var search_form = document.getElementById("opret_tegn");
// var textarea = document.getElementById("opret_tegn").getElementsByTagName("textarea");

$(function() {

    $('.sign a.delVote, .sign a.addVote').on( 'click', function() {
        event.preventDefault();
        console.log("Klik opdaget!");

        var mode = $(this).attr('class');
        if(mode == 'addVote') {
            var ajaxURL = addUrl;
        }
        else if(mode == 'delVote') {
            var ajaxURL = delUrl;
        }
        else {
            console.log("Fejl med <a> class!");
        }

        // Spinning animation!
        $(this).addClass('loadingVote'); // html("Vent...")

        // Saving this element to later usage
        var signDiv = $(this).parent();
        var signID = signDiv.data('id');

        var formData = {
            '_token': $('meta[name="csrf-token"]').attr('content'),
            'sign': signID
        }
            console.log("Token: "+formData['_token']+" - sign: "+formData['sign']);


        $.ajax({
            url: ajaxURL,
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(result) {
                console.log("Ajax request gennemført successfuldt!");
                console.log(result.msg);
                console.log(result.votes);
                
                signDiv.attr('data-count', result.votes); // Update data-count in parent div
                signDiv.find('span.count').text(result.votes); // Change the text inside the div
                if(signDiv.find('a').hasClass('delVote')) {
                    signDiv.find('a.delVote').addClass('addVote').removeClass('delVote loadingVote').removeAttr("href").css({'cursor': 'pointer', 'pointer-events' : 'none'}); // Change the overall text to "done" and unwrap the <a> tag, making them unable to click it again
                    console.log("fra del til add!");
                }
                else {
                    signDiv.find('a.addVote').addClass('delVote').removeClass('addVote loadingVote').removeAttr("href").css({'cursor': 'pointer', 'pointer-events' : 'none'}); // Change the overall text to "done" and unwrap the <a> tag, making them unable to click it again
                    console.log("fra add til del!");
                }
        

                // Sorts the divs according to the "data-count" attr inside the divs
                $('.sign').sort(function (a, b) {
                    console.log("Sorting the divs");
                    var contentA = parseInt( $(a).attr('data-count'));
                    var contentB = parseInt( $(b).attr('data-count'));
                    return contentA < contentB ? 1 : -1;
                }).appendTo("#signs");

            },
            error: function() {
                console.log("FEJL!");
            } 

        });
        
    });
    
    /* Removing this for now. Tooltip is a bad solution! 
     * Better with a hidden div which shows if click the link - or something
     * Now it's a redirecting to another page w/ the form

    $('.flagSign')
    .tooltip({
        //content: "Testindhold!"
        items: ".flagSign",
        position: { my: "left+15 top", at: "right center" },
        //show: { effect: "blind", direction: "down", duration: 200 },
        content: function(callback) { //callback
            $.get(flagUrl, {}, function(data) {
                callback( data );
            });
        },
    })
    .off('mouseover')
    .click(function() {
        
        var clicked = this;
        
        $( ".flagSign" ).each(function (i) {
            if (this != clicked) {
                $( this ).tooltip( "close" );
            }
        });
        event.preventDefault();
        console.log("Klik på flag opdaget!");
        $( this ).tooltip('open');
    });
    */

});

/**
 * Oversættelse for CameraTag
 * Af: Troels Madsen
 */

CT_i18n = []
CT_i18n[0] = "Til at optage med din mobiltelefon, besøg venligst <<url>> i browseren på din mobil."
CT_i18n[1] = "Din mobiltelefon understøtter ikke uploading af video"
CT_i18n[2] = "Tjek venligst at du har Flash Player 11 eller højere installeret"
CT_i18n[3] = "Ude af stand til at indlejre videooptager. Tjek venligst at du har Flash Player 11 eller højere installeret"
CT_i18n[4] = "Vælg en metode til at sende din tegn"
CT_i18n[5] = "optag fra webcam"
CT_i18n[6] = "upload en fil"
CT_i18n[7] = "optag fra mobil"
CT_i18n[8] = "vink til kameraet"
CT_i18n[9] = "optager om"
CT_i18n[10] = "uploader..."
CT_i18n[11] = "klik for at stop optagelsen"
CT_i18n[12] = "klik for at springe gennemse over"
CT_i18n[13] = "Godkend"
CT_i18n[14] = "Optag igen"
CT_i18n[15] = "Se optagelsen"
CT_i18n[16] = "Vent venligst mens vi flytter pixels rundt"
CT_i18n[17] = "Uploadet" //Kaldet Udgivet. Ændret så folk vil trykke på "indsend tegn"
CT_i18n[18] = "Skriv din <b>mobuilnummer</b> nedenunder og vi sender dig en link til optagelse fra mobiltelefon."
CT_i18n[19] = "Send link til mobil"
CT_i18n[20] = "fortryd"
CT_i18n[21] = "Tjek din mobil for instruktioner til at optage"
CT_i18n[22] = "eller henvis din mobil til"
CT_i18n[23] = "smid fil her til at uploade"
CT_i18n[24] = "sender din besked"
CT_i18n[25] = "skriv venligst din mobilnummer!"
CT_i18n[26] = "det ser ikke ud til at være en gyldige mobilnummer"
CT_i18n[27] = "Ude af stand til at sende SMS."
CT_i18n[28] = "Ingen kamera fundet"
CT_i18n[29] = "Ingen mikrofon fundet"
CT_i18n[30] = "Adgang til kamera afvist"
CT_i18n[31] = "Mistet forbindelse til server"
CT_i18n[32] = "Afspilning mislykkedes"
CT_i18n[33] = "Ude af stand til at forbinde"
CT_i18n[34] = "Ude af stand til at udgive din optagelse."
CT_i18n[35] = "Ude af stand til at sende formular data."
CT_i18n[36] = "uploader din video"
CT_i18n[37] = "henter video til afspilning"
CT_i18n[38] = "uploader"
CT_i18n[39] = "forbinder..."
CT_i18n[40] = "forhandler med din firewall..."
CT_i18n[41] = "Ah nej! Det ser ud til at din browser pausede optagelsen"