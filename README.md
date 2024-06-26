# BellaBuffs with PHPMailer Support

This is a fork of the fanlisting PHP script [BullaBuffs](https://github.com/jemjabella/BellaBuffs), with [PHPMailer](https://github.com/PHPMailer/PHPMailer) powering the email sending features.

This fork replaces the PHP `mail()` functions from BellaBuffs with PHPMailer, allowing a fanlisting to send out emails, provided the fanlisting owner chooses to enable them, even if the hosting server does not support the PHP `mail()` function.

The PHPMailer script that is incorporated in this fork is based on [InfinityFree](https://www.infinityfree.com/)'s [PHPMailer contact form script](https://github.com/InfinityFreeHosting/contactform). As InfinityFree's free hosting plan [does not support PHP `mail()` function](https://forum.infinityfree.com/t/sending-email-from-your-website-php-mail/49242), InfinityFree has provided their contact form script as an alternative.

This BellaBuffs fork is tested with PHP 8.2 on InfinityFree's free hosting.

## Permissions

You are free to use this BellaBuffs fork and customise it as much as you like. You may credit me for this fork specifically, but it is not required.

However, regardless if you choose to credit me for this fork or not, per the original author Jem Turner's Read Me, you must link back to Jem Turner at https://www.jemjabella.co.uk/. By using BellaBuffs, you also agree not to sell copies of the script, or services relating to the script (i.e. installation, customisation, etc) without written permission of Jem Turner. You can read [Jem Turner's original Read Me file here](https://github.com/helenclx/BellaBuffs-PHPMailer/blob/master/README-original.txt).

## Credits
* [Jem Turner](https://www.jemjabella.co.uk/) - The original author of [BellaBuffs](https://www.jemjabella.co.uk/scripts/bellabuffs/).
* [InfinityFree](https://www.infinityfree.com/) for their [PHPMailer contact form script](https://github.com/PHPMailer/PHPMailer).
* [PHPMailer](https://github.com/PHPMailer/PHPMailer)