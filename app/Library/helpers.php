<?php
    function mellemrum($s) {
        return trim(str_replace("_", " ", $s));
    };

    function GenerateUrl($s) {

        //Convert accented characters
        //$from = explode(",", "ç,œ,á,é,í,ó,ú,à,è,ì,ò,ù,ä,ë,ï,ö,ü,ÿ,â,ê,î,ô,û");
        //$to = explode(",", "c,oe,a,e,i,o,u,a,e,i,o,u,a,e,i,o,u,y,a,e,i,o,u");

        //Do the replacements, and convert all spaces to understrokes
        //$s = str_replace($from, $to, trim($s));

        //Konverter alle mellemrum til "_" og gør alle bogstav småt
        $s = preg_replace('/\s/', '_', strtolower($s));

        //Fjerner "-" eller "_" i begyndelsen eller slutningen
        return preg_replace('/^(-|_)/', '', preg_replace('/(-|_)$/', '', $s));
    }
?>
