<?php

// todo: Hier die Getter für die Globalen Informationen definieren
function get_global_contact() {
    $contact = carbon_get_theme_option('contact');
    js_log($contact);
    return $contact;
}
