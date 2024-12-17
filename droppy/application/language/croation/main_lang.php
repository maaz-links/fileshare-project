<?php
/**
 * Droppy - Online file sharing
 * Language
 * @lang Croatian
 * @author Robert
 */

defined('BASEPATH') OR exit('No direct script access allowed');

$lang = array(
    'share_files' 				=> "Pokreni prijenos podataka",
    'ok' 	                    => "U redu",
    'close' 					=> "Zatvori",
    'files' 					=> "Datoteka(e)",
    'link' 					    => "Poveznica",
    'email' 					=> "E-pošta",
    'success' 				    => "Dovršeno!",
    'password'        			=> "Lozinka",
    'message' 				    => "Vaša poruka",
    'download' 				    => "Preuzmi",
    'destruct' 				    => "Obriši",
    'select_files' 				=> "Odaberi datoteku(e)",
    'add_more' 				    => "Dodatne e-poštanske adrese",
    'total_size' 				=> "Ukupna veličina",
    'total_files'				=> "Ukupno datoteka",
    'download_id' 				=> "ID preuzimanja",
    'add_password' 				=> "Dodaj lozinku",
    'upload_error' 				=> "Pogreška prilikom učitavanja!",
    'wrong_pass' 				=> "Neispravna lozinka!",
    'enter_email' 				=> "E-pošta primatelja",
    'enter_own_email' 			=> "E-pošta pošiljatelja",
    'fill_fields' 				=> "Molimo popunite sva polja!",
    'message_receiver' 			=> "Poruka za primatelja",
    'download_started' 			=> "Preuzimanje je započelo...",
    'enable_destuct' 			=> "Obrisati nakon preuzimanja",
    'file_too_large' 			=> "Neke su datoteke prevelike.",
    'fill_password' 			=> "Unesite lozinku",
    'upload_not_found' 			=> "Datoteka nije pronađena, možda je već obrisana?",
    'processing_files' 			=> "Obrada datoteke. Stvara se poveznica za preuzimanje. Molimo vas za strpljenje.",
    'success_link' 				=> "Vaše datoteke su učitane, možete podijeliti poveznicu.",
    'success_email' 			=> "Vaše datoteke su učitane, uskoro ćete primiti e-poštu.",

    //Added in update 1.0.2
    'select_share'              => "Kako želite podijeliti:",
    'file_blocked'              => "Ove datoteke nisu dopuštene!",

    //Added in update 1.0.4
    'report_file'               => "Prijaviti datoteku",
    'report_file_text'          => "Jeste li sigurni da želite prijaviti ovu datoteku?",
    'report'                    => "Prijaviti",
    'max_files'                 => "Previše datoteka je odabrano!",

    //Added in update 1.0.7
    'yes'                       => "Da",
    'no'                        => "Ne",
    'max'                       => "Max.",
    'upload_settings'           => "Postavke prijenosa",
    'change_language'           => "Promijeni jezik",
    'terms_service'             => "Uvjeti korištenja",
    'about_us'                  => "Impresum",
    'protect_with_pass'         => "Zaštititi lozinkom",
    'destruct_file'             => "Automatski izbrisati datoteku(e)",
    'leave_empty_password'      => "Ostavite prazno za deaktiviranje lozinke",
    'share_type'                => "Opcije prijenosa",
    'share_type_text'           => "Datoteku možete podijeliti putem e-pošte ili poveznice.",
    'destruct_text'             => "Prijenos će se automatski izbrisati nakon što svi primatelji preuzmu datoteke.",
    'password_text'             => "Ovaj prijenos se može preuzeti samo unosom lozinke.",
    'select_pref_lang'          => "Odaberite svoj jezik",
    'select_language'           => "Odaberi jezik",
    'copy_url'                  => "Kopiraj poveznicu",
    'sign_in'                   => "Prijaviti se",
    'invalid_login'             => "Pogrešna prijava",
    'upload_processing'         => "Ove datoteke se trenutno prenose, vratite se kasnije.",
    'not_allowed'               => "Niste ovlašteni preuzeti ovu datoteku.",
    'invalid_pass'              => "Pogrešna lozinka",
    'msg_seconds'               => "Sekunda(e)",
    'msg_minutes'               => "Minuta(e)",
    'msg_hours'                 => "Sat(i)",
    'msg_remaining'             => "Preostaje",
    'remaining'                 => "Preostaje",
    'save'                      => "Spremi",

    //Added in update 1.2
    'not_available_pass'        => "(Potrebna pretplata)",

    //Added in update 1.2.2
    'uploaded_of'               => "Učitano od",
    'cancel'                    => "Odustani",
    'destructed_on'             => "Trajno izbrisano dana",
    'open'                      => "Otvori",
    'accept_terms'              => "Morate prihvatiti uvjete korištenja kako biste nastavili.",
    'accept'                    => "Prikaži uvjete",

    //Added in update 1.2.3
    'view_terms'                => "Bedingungen anzeigen",

    //Added in update 1.2.6
    'month'                     => "Mjesec",
    'week'                      => "Tjedan",
    'day'                       => "Dan",
    'hour'                      => "Sat",
    'months'                    => "Mjeseci",
    'weeks'                     => "Tjedni",
    'days'                      => "Dani",
    'hours'                     => "Sati",

    //Added in update 1.2.7
    'drop_files_here'           => "Ispustite datoteke ovdje",

    //Added in update 1.4.6
    'are_you_sure'              => "Jeste li sigurni?",

    // Added in update 2.0
    'login'                     => "Prijaviti se",
    'user_login'                => "Prijava korisnika",

    // Added in update 2.0.3
    'logout'                    => "Odjava",

    // Added in update 2.1.4
    'contact'                   => "Kontakt",
    'subject'                   => "Predmet",
    'send'                      => "Pošalji",
    'message_sent'              => "Vaša poruka je poslana!",

    // Added in update 2.1.5
    'contact_email_description'   => 'Vaša e-poštanska adresa',
    'contact_subject_description' => 'Vaš predmet',
    'contact_message_description' => 'Vaša poruka',

    // Added in update 2.1.6
    'invalid_email'               => "Unesena e-poštanska adresa nije valjana",

    // Added in update 2.2.6
    'add_more_files'              => 'Dodaj još datoteka',
    'email_to'                    => 'E-pošta za',
    'email_from'                  => 'E-pošta od',
    'how_to_share_file'           => 'Kako želite poslati datoteku?',
    'send_using_email'            => 'Poslati e-poštom',
    'get_sharable_link'           => 'Dobiti poveznicu za dijeljenje',
    'protect_upload_password'     => 'Zaštititi prijenos lozinkom',
    'when_file_expire'            => 'Kada želite da datoteka istekne?',
    'upload'                      => 'Pokreni učitavanje',
    'lets_begin_files'            => "Dodaj datoteke",
    'files_selected'              => "Odabrane datoteke",
    'selected'                    => "Odabrano",
    'recipient_exists'            => "Primatelj već postoji",
    'upload_more'                 => "Učitaj više",
    'refresh'                     => "Ažuriraj",
    'download_will_be_deleted'    => "Ističe dana",
    'download_is_ready'           => "Vaše preuzimanje je gotovo",
    'delete'                      => "Izbriši",

    // Added in update 2.3.6
    'internet_down'               => 'Poslužitelj nije dostupan, jeste li povezani s internetom?',
    'do_not_expire'               => 'Ne istječe',
    'select_recipient'            => '- Odaberi primatelja -',

    // Added in update 2.3.9
    'verify_email_title'          => "Potvrda e-poštanske adrese",
    'verify_email_desc'           => "Moramo biti sigurni da ste to stvarno vi, zato smo poslali potvrdni kod na sljedeću adresu: ",
    'enter_verify_code'           => "Unesite potvrdni kod",
    'verify'                      => "Potvrda",
    'incorrect_verify'            => "Potvrdni kod nije bio ispravan!",

    // Added in update 2.4.1
    'error'                       => "Greška",
    'download_browser_unsupported'=> "Vaš preglednik ne podržava preuzimanje unutar aplikacije, otvorite URL u svom mobilnom pregledniku.",

    // Added in update 2.4.4
    'unlock_download'             => "Otključaj preuzimanje",
    'preview_files'               => "Pregled datoteke",

    // Added in update 2.4.5
    'copied'                      => "Kopirano!",

    // Added in update 2.4.6
    'login_email'                 => "E-pošta",
    'login_password'              => "Lozinka",
    'never'                       => "Nikada",
    'add_folders'                 => "Dodaj mapu",
    'or_select_folder'            => "Ili odaberite cijelu mapu",

    // Added in update 2.4.8
    'are_you_sure_delete'         => "Jeste li sigurni da želite izbrisati ovu datoteku?",

    // Added in update 2.5.9
    'report_download_subject'     => "Report for download: "

    // Please help translating Droppy by sending this fully translated file to support@proxibolt.com
    // and we'll make sure to include it in the next update
);