# BellaBuffs with PHPMailer Support

This is a fork of the fanlisting PHP script [BullaBuffs](https://github.com/jemjabella/BellaBuffs) with [PHPMailer](https://github.com/PHPMailer/PHPMailer) support added.

This fork replaces the PHP `mail()` functions from BellaBuffs with PHPMailer, allowing a fanlisting to send emails even if the hosting server does not support PHP `mail()` functions.

The PHPMailer script that is incorporated in this fork is based on [InfinityFree](https://www.infinityfree.com/)'s [PHPMailer contact form script](https://github.com/InfinityFreeHosting/contactform). As InfinityFree's free hosting plan [does not support PHP `mail()` function](https://forum.infinityfree.com/t/sending-email-from-your-website-php-mail/49242), InfinityFree has provided their contact form script as an alternative.

This BellaBuffs fork is tested with PHP 8.2 on InfinityFree's free hosting.

## Credits
* [Jem Turner](https://www.jemjabella.co.uk/) - The original author of [BellaBuffs](https://www.jemjabella.co.uk/scripts/bellabuffs/). [View Jem's original Read Me](https://github.com/helenclx/BellaBuffs-PHPMailer/blob/master/README_original.txt).
* [InfinityFree](https://www.infinityfree.com/) for their [PHPMailer contact form script](https://github.com/PHPMailer/PHPMailer).