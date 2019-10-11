# GUILDBOT
A simple PHP Discord Bot to manage MMO Guilds.

Full Documentation on howto use this bot:
https://www.panic-mode.de/guides/technik/guildbot-manual/

## for development
``composer install`` for sure

add your propel.yml under config/
add your google auth json to config/

generate propel config (see propel docu: http://propelorm.org/)

You need to create your own discord app/bot to run
copy src/bot.example.php to src/bot.php and fill in needed discord fields
adjust paths to debug log

after everything is set up
``php src/bot.php``


