# Mattermost Channel Exporter

This is a channel export/archival script written for the communications software Mattermost.  The official channel export plugin requires a license to use.  This fully defeats the purpose of Free & Open-Source Software (FOSS) so I wrote my own export script.

***

## Requirements

* Mattermost self-hosted server.
* SQL credentials for your Mattermost database.  You can get these from your `config.json` file or make your own credentials if you know how to.
* An Apache or NGINX instance with PHP & the `php_mysqli` extension.  If you run a web server and Mattermost on separate systems, make sure your database is accessible from the internet.  If they're on the same system, you may use `localhost` as the hostname for your SQL credentials.
* *Bonus:* phpMyAdmin.  It's not necessary but will help you if you decide to improve the script.

## How to Use

Enter the ID of a channel on your Mattermost instance and click Submit.  The script will create a GIANT table of every message, including edited & deleted ones.  It will also print the date & time that the exporter ran for your future convenience.

## Disclaimer

Playing around in your Mattermost SQL database can be extremely dangerous if you don't know what you're doing.  Make all changes with caution, keep backups, and RTFM!
