<?php
// cPanel API credentials
$cpanelUser = 'your_cpanel_username';
$cpanelPassword = 'your_cpanel_password';

// Email server details
$hostname = '{mail.yourdomain.com:993/imap/ssl}';

// Function to connect to an email account and delete large emails
function delete_large_emails($hostname, $email, $password) {
    $inbox = imap_open($hostname, $email, $password) or die('Cannot connect to mail server: ' . imap_last_error());
    $emails = imap_search($inbox, 'BEFORE "' . date("d-M-Y", strtotime("-14 days")) . '"');

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

// Function to get the list of email accounts
function get_email_accounts($cpanelUser, $cpanelPassword) {
    $url = "https://yourdomain.com:2083/json-api/cpanel?cpanel_jsonapi_user=$cpanelUser&cpanel_jsonapi_apiversion=2&cpanel_jsonapi_module=Email&cpanel_jsonapi_func=listpopswithdisk";
    $headers = array(
        "Authorization: Basic " . base64_encode("$cpanelUser:$cpanelPassword")
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);

    return json_decode($result, true)['cpanelresult']['data'];
}

// Get the list of email accounts
$email_accounts = get_email_accounts($cpanelUser, $cpanelPassword);

foreach ($email_accounts as $account) {
    $email = $account['email'];
    $password = 'password_for_' . $email; // You need to have passwords for each email account

    // Delete large emails from this account
    delete_large_emails($hostname, $email, $password);
}

echo "Large emails older than 2 weeks have been deleted from all accounts.";
?>
