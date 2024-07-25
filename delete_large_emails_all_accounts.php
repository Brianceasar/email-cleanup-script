<?php
$cpanelUser = 'your_cpanel_username';
$cpanelPassword = 'your_cpanel_password';

$hostname = '{mail.yourdomain.com:993/imap/ssl}';
function delete_large_emails($hostname, $email, $password) {
    $inbox = imap_open($hostname, $email, $password) or die('Cannot connect to mail server: ' . imap_last_error());
    $emails = imap_search($inbox, 'BEFORE "' . date("12-01-2024", strtotime("-14 days")) . '"');

    if ($emails) {
        foreach ($emails as $email_number) {
            $overview = imap_fetch_overview($inbox, $email_number, 0);
            $message_size = $overview[0]->size;
            if ($message_size > 1024000) {
                imap_delete($inbox, $email_number);
            }
        }
        imap_expunge($inbox);
    }

    imap_close($inbox);
}


?>
