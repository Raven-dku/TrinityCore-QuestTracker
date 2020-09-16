# TrinityCore-QuestTracker

TrinityCore QuestTracker is a tool made specifically for [TrinityCore](https://github.com/TrinityCore/TrinityCore) and the purpose of this tool is to keep track of most accepted/abandoned/completed quests.
To find out more about Quest Tracking in TrinityCore, [(see details)](https://github.com/TrinityCore/TrinityCore/pull/13353)

To get started, go to your web server folder, and type :
git clone https://github.com/Unwrath/TrinityCore-QuestTracker.git

Once cloning is complete, go to **TrinityCore-QuestTracker/includes/config.php.dist**, and edit the following:

```
define('HOSTNAME'       , '');
define('USERNAME'       , '');
define('PASSWORD'       , '');
define('PORT'           , '');
define('CHAR_DB'        , '');
define('WORLD_DB'       , '');
define('ARMORY'         , '1');
define('TOOLTIP_LIMIT'  , '255');

Inside the file, there is a description for each line, to keep it simple and understandable.
```
Once that's done, save the file, and rename it from config.php.dist to config.php.


You will also need to enable your Quest Tracker in your **worldserver.conf** file by setting:

```
Quests.EnableQuestTracker = 1
```
Features:
- Tooltips on almost every row with Last X Abandoned/Accepted/Completed quests (Number to display is customizable)
- Caching is working. The more you use application, the faster it gets. // TODO: refresh cache/delete cache
- Retrieves character's names (If deleted, will display [DELETED])
- Retrieves character's class, and converts it to color. Because it's awesome!
- Quest Tooltips (Tooltip information and source can be switched, depending on your needs)
- Dynamic live table loading
- Light / Dark mode, with an instant switch. Incase you want to go to the dark side >.<
- Sorting by any row, both descending and ascending order
- Coded in OOP

Screenshots:
![Screenshot](https://raw.githubusercontent.com/Unwrath/TrinityCore-QuestTracker/master/images/light-example.JPG)
![Screenshot](https://raw.githubusercontent.com/Unwrath/TrinityCore-QuestTracker/master/images/dark-example.JPG)
Tested on:
- 3.3.5, works as intended.
- master, waiting to be tested. (In theory it should work if such table exists in master, just need to select ARMORY type to 2(Wowhead)).
