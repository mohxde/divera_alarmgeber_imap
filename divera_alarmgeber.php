<?php
#
#	Script (c) by mohx.de
#	info@mohx.de
#


#################### Config ####################


$imap_server 		= ""; // mail.domain.de
$imap_port		= ""; // 993
$imap_mail 		= ""; // alarm@domain.de
$imap_password 		= ""; // save Password

$search_string 		= ""; // String which must be in the subject

$divera_accesskey 	= ""; // Divera Access Key
$divera_alarmtxt	= ""; // Text for pushmessage

################################################


$mbox = imap_open ("{".$imap_server.":".$imap_port."/imap/ssl/novalidate-cert}", $imap_mail, $imap_password);

$alarm_count = 0;

$count = imap_num_msg($mbox);
for($msgno = 1; $msgno <= $count; $msgno++)
{
        $headers = imap_headerinfo($mbox, $msgno);

        if($headers->Unseen == 'U' && strpos($headers->Subject, $search_string) !== false)
        {
                $alarm_count++;
                imap_setflag_full($mbox, $msgno, "\\Seen \\Flagged", ST_UID);
        }

}

if($alarm_count > 0)
{
        file_get_contents('https://www.divera247.com/api/alarm?accesskey='.$divera_accesskey.'&type='.$divera_alarmtxt);
}

imap_close($mbox);

