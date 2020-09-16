# TrinityCore-QuestTracker

TrinityCore QuestTracker is a tool made specifically for [TrinityCore](https://github.com/TrinityCore/TrinityCore) and the purpose of this tool is to keep track of most accepted/abandoned/completed quests.
To find out more about Quest Tracking in TrinityCore, [(see details)](https://github.com/TrinityCore/TrinityCore/pull/13353)

To get started, go to your web server folder, and type :
git clone https://github.com/Unwrath/TrinityCore-QuestTracker.git
Once cloning is complete, go to TrinityCore-QuestTracker/includes/config.php.dist, and edit the following:

```

define('HOSTNAME'       , '');
define('USERNAME'       , '');
define('PASSWORD'       , '');
define('PORT'           , '');
define('CHAR_DB'        , '');
define('WORLD_DB'       , '');
define('ARMORY'         , '1');
define('TOOLTIP_LIMIT'  , '255');

 * HOSTNAME      - Address  for connection to database. If QuestTracker is stored locally - For Winblows users, please put "localhost", for UNIX/MacOS - 127.0.0.1
 * USERNAME      - Username for connection to database.
 * PASSWORD      - Password for connection to database.
 * PORT          - Port for for connection to database.By default, this should be 3306. If your's is different, please change it.
 * CHAR_DB       - Database name for TrinityCore Character's database.
 * WORLD_DB      - Database name for TrinityCore World's database.
 * QT_TABLE      - Quest Tracker table name.
 * ARMORY_URL    - Armory script to be defined. There are three options:
 *                 1 - use EvoWoW tooltips. great for 3.3.5.
 *                 2 - use Wowhead tooltips. great for master I guess? not so great for 3.3.5.
 *                 3 - use WotlkDB tooltips. great for 3.3.5, has slightly more info.
 * TOOLTIP_LIMIT - Limit for tooltips, how many records should be retrieved. Keep the number real, don't put insane amounts here. Huge numbers will decrease performance.
```
Once that's done, save the file, and rename it from config.php.dist to config.php.


You will also need to enable your Quest Tracker in your **worldserver.conf** file by setting:

```
Quests.EnableQuestTracker = 1
```
