# BellaBuffs with PHPMailer Integration

This is a fork of the fanlisting PHP script [BullaBuffs](https://github.com/jemjabella/BellaBuffs), with [PHPMailer](https://github.com/PHPMailer/PHPMailer) powering the email sending functions.

## Description

BellaBuffs includes the optional features to email new members after submitting the join form, email the fanlisting admin when a new member joins or email new members when their applications were approved. The original BellaBuffs script achieved this by using PHP's built-in `mail()` function.

This fork replaces the PHP `mail()` functions from BellaBuffs with PHPMailer, allowing a fanlisting to send out emails with SMTP, provided the fanlisting owner chooses to enable them, even if the hosting server does not support the PHP `mail()` function.

The PHPMailer script that is incorporated in this fork is based on [InfinityFree](https://www.infinityfree.com/)'s [PHPMailer contact form script](https://github.com/InfinityFreeHosting/contactform). As InfinityFree's free hosting plan [does not support PHP `mail()` function](https://forum.infinityfree.com/t/sending-email-from-your-website-php-mail/49242), InfinityFree has provided their contact form script as an alternative.

This BellaBuffs fork has been tested with PHP 8.2 on InfinityFree's free hosting and PHP 8.3 on Hostinger's Premium Shared Hosting.

## New Features
* Integrate PHPMailer for the email sending features, should the fanlisting owner enables these features.
* Email sending features are enabled by default, but can be disabled in `prefs.php`.
* HTML5 form validation, including E-mail and URL input types and the `required` attribute.
* Update list of countries.
* Add a buttons folder (with a placeholder file to enable the folder to be pushed via Git), so users do not need to manually create the folder to store uploaded buttons.
* Allows fanlisting members to change their favourites with the update form if the favourites field is enabled.
* Check for `https://` in addition to `http://` for potential spam in the comment fields.
* Add the option to enable a security question field on the join and contact forms to improve anti-spam measures.

## Usage Instructions
1. Download the [latest release of BellaBuffs-PHPMailer](https://github.com/helenclx/BellaBuffs-PHPMailer/releases/latest) from the GitHub repository, then extract the archive.
    - Alternately, you can clone this repository, as the files can be used out of the box.
1. Customise your preferences in `prefs.php`.
1. Configure your email SMTP settings in `contactform/config.php`.
1. Upload all files of this fork to a directory where you want your fanlisting to be hosted on. 
1. Set file permissions of all the `.txt` files to CHMOD 666 to make the text files writeable.
1. Set permission of the `buttons` directory to CHMOD 777 to enable buttons to be uploaded to the directory (NOTE: For better security, it is recommended to set the permissions of `buttons` directory to CHMOD 755 when not using the button upload features).

More instructions for using BellaBuffs can be found in the [original Read Me of BellaBuffs](https://github.com/helenclx/BellaBuffs-PHPMailer/blob/master/README-original.txt).

## Permissions

You are free to use this BellaBuffs fork and customise it as much as you like. You may credit me for this fork specifically, but it is not required.

However, regardless if you choose to credit me for this fork or not, per the original author Jem Turner's Read Me, you must link back to Jem Turner at https://www.jemjabella.co.uk/. By using BellaBuffs, you also agree not to sell copies of the script, or services relating to the script (i.e. installation, customisation, etc) without written permission of Jem Turner. You can read [Jem Turner's original Read Me file here](https://github.com/helenclx/BellaBuffs-PHPMailer/blob/master/README-original.txt).

## Credits
* [Jem Turner](https://www.jemjabella.co.uk/) - The original author of [BellaBuffs](https://www.jemjabella.co.uk/scripts/bellabuffs/)
* [InfinityFree](https://www.infinityfree.com/) for their [PHPMailer contact form script](https://github.com/InfinityFreeHosting/contactform)
* [PHPMailer](https://github.com/PHPMailer/PHPMailer)
