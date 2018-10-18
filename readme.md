# DirectAdmin PHP API for Lets Encrypt DNS validation

This repository contains code snippets to automate your Lets Encrypt DNS validation when using DirectAdmin as DNS server. It works with `certbot-auto` and **supports currently only WILDCARD validations**.

## Prerequisites

Make sure you have the following things ready:
- You have a domainname that is pointed to the DirectAdmin server
- Make sure you can run .PHP files (via the browser)
- You have `cerbot-auto` installed on a Linux box. [Click here](https://certbot.eff.org/docs/install.html) for an installation guide
- Download the [httpsocket.php](https://github.com/arian/DirectAdminApi/blob/master/Source/HTTPSocket.php) file

## Setup

You can run the PHP scripts on a seperate server:

1. Copy the `letsencrypt-wildcard.php` and `httpsocket.php` file to someplace in your webroot on your HTTP server
2. Edit the settings in the `letsencrypt-wildcard.php` file.
3. **Make sure you change the `$requestValidationPassword` variable!**.
4. Test if it works by going to `http(s)://yourdomain.com/path/to/letsencrypt-wildcard.php?pass={{MYPASS}}&certbot_domain=yourdomain.com&certbot_validation=test123`. In your DirectAdmin web UI, navigate to your domain, 'Your Account' => 'DNS management'. You should see the `_acme-challenge.yourdomain.com` record there as a TXT record with `test123` as value.

On the server where you want to create the SSL certificates:

1. Place the `cleanup.sh` and `prehook.sh` files in a folder (preferably together with `cerbot-auto`). 
2. Edit them to make sure they point to the right location and include the right password. 
3. Make them executable by doing `chmod +x {filename}.sh`. 

Then, if all is working well, you should be able to use the following command to generate a wildcard certificate (might require `su`):

`./certbot-auto certonly --manual --preferred-challenges=dns --manual-auth-hook ./prehook.sh --manual-cleanup-hook ./cleanup.sh -d *.example.com --non-interactive --manual-public-ip-logging-ok`

This command automatically agrees with the TOS and public IP logging. If you would like to test the command without generating a real certificate, add `--dry-run` at the end of the command. 

### Automatic renewal
If you want to renew your certificates, you can run the command `./certbot-auto renew`. It should renew your certs without any parameters, so you can place this easily in your crontab.

## Known problems

DirectAdmin has some issues with publishing the DNS records. To test if your DNS record is working, test this from a Linux box: `dig @nameserver.yourdomain.com TXT _acme-challenge.yourdomain.com`. 

Sometimes, the DNS gets not immediately synced by the DNS server. This means it takes some time for the records to become visible. You can edit the `SLEEP` command in the `prehook.sh` file to make sure Lets Encrypt waits a certain time before validating.