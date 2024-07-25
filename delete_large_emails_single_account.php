<?php
// Email account credentials
$hostname = '{mail.yourdomain.com:993/imap/ssl}INBOX';
$username = 'your-email@yourdomain.com';
$password = 'your-password';

// Connect to the mailbox
$inbox = imap_open($hostname, $username, $password) or die('Cannot connect to mail server: ' . imap_last_error());

// Search for emails older than 2 weeks
$emails = imap_search($inbox, 'BEFORE "' . date("d-M-Y", strtotime("-14 days")) . '"');

if ($emails) {
    foreach ($emails as $email_number) {
        $overview = imap_fetch_overview($inbox, $email_number, 0);
        $message_size = $overview[0]->size;
        // Check if email size is greater than 1MB
        if ($message_size > 1024000) {
            // Delete the email
            imap_delete($inbox, $email_number);
        }
    }
    // Expunge deleted emails
    imap_expunge($inbox);
}

// Close the connection
imap_close($inbox);

echo "Large emails older than 2 weeks have been deleted.";
?>
