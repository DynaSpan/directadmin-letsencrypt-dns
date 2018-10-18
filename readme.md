# DirectAdmin PHP API for Lets Encrypt DNS validation

This repository contains code snippets to automate your Lets Encrypt DNS validation when using DirectAdmin as DNS server. It works with `certbot-auto` and **supports currently only WILDCARD validations**.

## Prerequisites

Make sure you have the following things ready:
- You have a domainname that is pointed to the DirectAdmin server
- Make sure you can run .PHP files on the DirectAdmin server
- You have `cerbot-auto` installed on a Linux box. [Click here](https://certbot.eff.org/docs/install.html) for an installation guide
- Download the [httpsocket.php](https://github.com/arian/DirectAdminApi/blob/master/Source/HTTPSocket.php) file

## Setup

### DirectAdmin

1. Copy the `letsencrypt-wildcard.php` and `httpsocket.php` file to someplace in your webroot on your DirectAdmin server. 
2. **Make sure you change the `$requestValidationPassword` variable!**.

### Server
On the server you want to host the SSL certificates:

1. place the `cleanup.sh` and `prehook.sh` file in a folder (preferably together with `cerbot-auto`). 
2. Edit them to make sure they point to the right location and include the right password. 
3. Make them executable by doing `chmod +x {filename}.sh`. 

Then, if all is working well, you should be able to use the following command to generate a wildcard certificate (might require `su`):

`./certbot-auto certonly --manual --preferred-challenges=dns --manual-auth-hook ./prehook.sh --manual-cleanup-hook ./cleanup.sh -d *.example.com --non-interactive --manual-public-ip-logging-ok`

This command automatically agrees with the TOS and public IP logging. If you would like to test the command without generating a real certificate, add `--dry-run` at the end of the command. 

#### Automatic renewal
If you want to renew your certificates, you can run the command `./certbot-auto renew`. It should renew your certs without any parameters, so you can place this easily in your crontab.