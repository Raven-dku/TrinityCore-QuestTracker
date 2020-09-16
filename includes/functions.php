<?php
/**
 * Functions file for TrinityCore QuestTracker system.
 * Author : Tomas Rad (Raven)
 * Date : 14/09/2020
 * Time : 3:08 PM GMT+1
 */
include_once('config.php');
class Functions {

    protected  $hostname=HOSTNAME;
    protected  $username=USERNAME;
    protected  $password=PASSWORD;
    protected  $char=CHAR_DB;
    protected  $world=WORLD_DB;
    protected  $url=ARMORY_URL;
    protected  $limit=TOOLTIP_LIMIT;
    protected  $armory=ARMORY;
    private $db;

    /**
     * Tooltip script URL's to use.
     * @return string
     */
    public function getTooltipScriptURL()
    {
        switch($this->armory) {
            case 1: // EvoWoW
                return"<script type=\"text/javascript\" src=\"https://wotlk.evowow.com/static/widgets/power.js\">var aowow_tooltips = { \"colorlinks\": true, \"iconizelinks\": true, \"renamelinks\": true }</script>";
                break;
            case 2: // Wowhead
                echo "<script src=\"https://wow.zamimg.com/widgets/power.js\">const whTooltips = {colorLinks: true, iconizeLinks: true, renameLinks: true};</script>";
                break;
            case 3: // WotlkDB
                echo "<script type=\"text/javascript\" src=\"https://wotlkdb.com/static/widgets/power.js\">var aowow_tooltips = { \"colorlinks\": true, \"iconizelinks\": true, \"renamelinks\": true }</script>";
                break;
        }

    }

    /**
     * Connect to database.
     */
    public function connect()
    {
        $db = new mysqli($this->hostname, $this->username, $this->password);

        if ($db->connect_error)
            die("Connection to the database server failed! Reason: ".mysqli_connect_error());
        else
            $this->db=$db;
    }

    /**
     * Get character's name by providing an ID obtained from quest_tracker.
     * @param $CharacterID
     * @return string
     */
    public function getCharacterNameByID($CharacterID) {
        $query = "SELECT name FROM `{$this->char}`.`characters` WHERE guid = '".$CharacterID."'";

        $result = $this->db->query($query);
        if($result !== false) {
            $data = $result->fetch_assoc();
            return $value = $data ? $data['name'] : "[DELETED]";
        }
        $this->db->close();
    }

    /**
     * Get character's class. For coloring tooltips. Because it's fun.
     * @param $CharacterID
     * @return string
     */
    public function getCharacterClassByID($CharacterID)
    {
        $query = "SELECT class FROM `{$this->char}`.`characters` WHERE guid = ".$CharacterID;

        $result = $this->db->query($query);
        if($result !== false) {
            $data = $result->fetch_assoc();
            return $value = $data ? $data['class'] : "00";
        }
        $this->db->close();
    }

    /**
     * Get color of each class.
     * @param $class
     * @return string
     */
    public function getClassIDColor($class){
        switch( $class ) {
            case 00:
                return "FF0000";
            case 1:
                return "C79C6E";
                break;
            case 2:
                return "F58CBA";
                break;
            case 3:
                return "A9D271";
                break;
            case 4:
                return "FFF569";
                break;
            case 5:
                return "FFFFFF";
                break;
            case 6:
                return "C41F3B";
                break;
            case 7:
                return "0070DE";
                break;
            case 8:
                return "40C7EB";
                break;
            case 9:
                return "8787ED";
                break;
            case 11:
                return "FF7D0A";
                break;
        }
        $this->db->close();
    }

    /**
     * Get quest's name from quest_template.
     * @param $QuestID
     * @return string
     */
    public function getQuestName($QuestID) {
        $query = 'SELECT `LogTitle` FROM `'.$this->world.'`.`quest_template` WHERE `ID` = '.$QuestID;
        $result = $this->db->query($query);
        if($result !== false)
        {
            $data = $result->fetch_assoc();
            return $value = $data ? $data['LogTitle'] : "Unknown Quest";
        }
        $this->db->close();
    }

    /**
     * Lists who accepted quest by Quest ID.
     * @param $QuestID
     * @return string
     */
    public function getLastAcceptedByQuestID($QuestID)
    {
        $limit = $this->limit;
        $query= "SELECT * FROM `{$this->char}`.`quest_tracker` WHERE `id` = '".$QuestID."'  LIMIT {$limit}";
        $result = $this->db->query($query);
        if($result !== false)
        {
            $text = "Last {$this->limit} character's that accepted this quest:<br>";
            while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                if(!empty($row['quest_accept_time'])) {
                    $class = $this->getCharacterClassByID($row['character_guid']);
                    $color = $this->getClassIDColor($class);
                    $text .= "<font color='$color'>" . $this->getCharacterNameByID($row['character_guid']) . "</font> accepted this quest on {$row['quest_accept_time']}<br>";
                }
            }
            return $text;
        }
        $this->db->close();
    }

    /**
     * Lists who abandoned quest by Quest ID.
     * @param $QuestID
     * @return string
     */
    public function getLastAbandonedByQuestID($QuestID)
    {
        $limit = $this->limit;
        $query= "SELECT * FROM `{$this->char}`.`quest_tracker` WHERE `id` = '".$QuestID."'  LIMIT {$limit}";
        $result = $this->db->query($query);
        if($result !== false)
        {
            $text = "Last {$this->limit} character's that abandoned this quest:<br>";
            while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                if(!empty($row['quest_abandon_time'])) {
                    $class = $this->getCharacterClassByID($row['character_guid']);
                    $color = $this->getClassIDColor($class);
                    $text .= "<font color='$color'>" . $this->getCharacterNameByID($row['character_guid']) . "</font> abandoned this quest on {$row['quest_abandon_time']}<br>";
                }
            }
            return $text;
        }
        $this->db->close();
    }

    /**
     * Lists who completed quest by Quest ID.
     * @param $QuestID
     * @return string
     */
    public function getLastCompletedByQuestID($QuestID)
    {
        $limit = $this->limit;
        $query= "SELECT `character_guid`, `quest_complete_time`, `completed_by_gm` FROM {$this->char}.quest_tracker WHERE id = '".$QuestID."'  LIMIT {$limit}";
        $result = $this->db->query($query);
        if($result !== false)
        {
            $text = "Last {$this->limit} character's that completed this quest:<br>";
            while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                if(!empty($row['quest_complete_time'])) {
                    $class = $this->getCharacterClassByID($row['character_guid']);
                    $color = $this->getClassIDColor($class);
                    if($row['completed_by_gm'] > 0)
                        $text .= "<font color='00FFFF'>GM</font> has completed this quest for <font color='$color'>".$this->getCharacterNameByID($row['character_guid'])."</font> on {$row['quest_complete_time']}<br>";
                    else
                        $text .= "<font color='$color'>".$this->getCharacterNameByID($row['character_guid'])."</font> completed this quest on {$row['quest_complete_time']}<br>";
                }
            }
            return $text;
        }
        $this->db->close();
    }

    /**
     * @param $QuestID
     * @return string
     */
    public function getLastAcceptedByDate($QuestID)
    {
        $limit = $this->limit;
        $query= "SELECT `character_guid`, `quest_accept_time` FROM `{$this->char}`.`quest_tracker` WHERE `id` = '".$QuestID."' LIMIT {$limit}";
        $result = $this->db->query($query);
        if($result !== false)
        {
            $text = "Last {$this->limit} character's that accepted this quest:<br>";
            while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                $class = $this->getCharacterClassByID($row['character_guid']);
                $color = $this->getClassIDColor($class);
                if(!empty($row['quest_accept_time']))
                    $text .= "<font color='$color'>" . $this->getCharacterNameByID($row['character_guid']) . "</font> accepted this quest on <b>{$row['quest_accept_time']}</b><br>";
            }
            return $text;
        }
        $this->db->close();
    }

    /**
     * @param $QuestID
     * @return string
     */
    public function getLastAbandonedByDate($QuestID)
    {
        $limit = $this->limit;
        $query= "SELECT `character_guid`, `quest_accept_time`, `quest_abandon_time` FROM `{$this->char}`.`quest_tracker` WHERE `id` = '".$QuestID."' LIMIT {$limit}";
        $result = $this->db->query($query);
        if($result !== false)
        {
            $text = "Last {$this->limit} times quest was abandoned:<br>";
            while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                $class = $this->getCharacterClassByID($row['character_guid']);
                $color = $this->getClassIDColor($class);
                if(!empty($row['quest_accept_time'])) {
                    if(!empty($row['quest_abandon_time']))
                        $text .= "<font color='$color'>" . $this->getCharacterNameByID($row['character_guid']) . "</font> abandoned this quest on <b>{$row['quest_abandon_time']}</b><br>";
                }
            }
            return $text;
        }
        $this->db->close();
    }

    /**
     * @param $QuestID
     * @return string
     */
    public function getLastCompletedByDate($QuestID)
    {
        $limit = $this->limit;
        $query= "SELECT `character_guid`, `quest_accept_time`, `quest_complete_time`, `completed_by_gm` FROM `{$this->char}`.`quest_tracker` WHERE `id` = '".$QuestID."' LIMIT {$limit}";
        $result = $this->db->query($query);
        if($result !== false)
        {
            $text = "Last {$this->limit} times quest was completed:<br>";
            while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                $class = $this->getCharacterClassByID($row['character_guid']);
                $color = $this->getClassIDColor($class);
                if(!empty($row['quest_accept_time'])) {
                    if(!empty($row['quest_complete_time'])  and $row['completed_by_gm'] == 0)
                        $text .= "<font color='$color'>" . $this->getCharacterNameByID($row['character_guid']) . "</font> completed this quest on <b>{$row['quest_complete_time']}</b><br>";
                    elseif(!empty($row['quest_complete_time']) and $row['completed_by_gm'] == 1)
                        $text .= "<font color='00FFFF'>GM</font> has completed this quest for <font color='$color'>" . $this->getCharacterNameByID($row['character_guid']) . "</font> on <b>{$row['quest_complete_time']}</b><br>";
                }
            }
            return $text;
        }
        $this->db->close();
    }

    /**
     * Populate table with data, allow for search, grouping, and put tooltips inside.
     * @return string JSON
     */
    public function getTableData()
    {
        $requestData = $_REQUEST;
        $tquery = "SELECT id FROM `".$this->char."`.`quest_tracker`";
        $tresult = $this->db->query($tquery);
        $totalData = $tresult->num_rows;
        $totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.
        $tableColumns = array(0 => 'id', 1 => 'QuestName', 2 => 'QuestTries', 3 => 'QuestAcceptedTimes', 4 => 'QuestAbandonedTimes',
                              5 => 'QuestCompletedTimes', 6 => 'QuestLastAccepted', 7 => 'QuestLastAbandoned', 8 => 'QuestLastCompleted');
        $rquery = 'SELECT A.id, character_guid, completed_by_gm, B.LogTitle as QuestName, COUNT(A.id) as QuestTries, COUNT(quest_accept_time) as QuestAcceptedTimes, COUNT(quest_abandon_time) as QuestAbandonedTimes, COUNT(quest_complete_time) as QuestCompletedTimes,
                    COUNT(completed_by_gm) as QuestCompletedByGMCount, MAX(quest_accept_time) as QuestLastAccepted, MAX(quest_abandon_time) as QuestLastAbandoned, MAX(quest_complete_time) as QuestLastCompleted FROM `'.$this->char.'`.`quest_tracker` A JOIN `'.$this->world.'`.`quest_template` B';
        if (!empty($requestData['search']['value']))
            $rquery .= " WHERE B.LogTitle LIKE '%" . $requestData['search']['value'] . "%' AND A.id = B.ID GROUP BY A.`id`";
        else
            $rquery .= " ON A.id = B.ID GROUP BY `id`";
        $rquery .= " ORDER BY ".$tableColumns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start'] . " ,".$requestData['length']."   ";
        $result = $this->db->query($rquery);
        $data = array();

        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $nestedData = array();
            $nestedData[] = '<a href="'.$this->url.$row['id'].'">'.$row["id"].'</a>';
            $nestedData[] = '<a href="'.$this->url.$row['id'].'">'.$row["QuestName"].'</a>';
            $nestedData[] = $row["QuestTries"] . ' Tries</a>';
            $nestedData[] = '<a href="#" data-tippy-content="'.$this->getLastAcceptedByQuestID($row['id']).'">'.$row["QuestAcceptedTimes"] . ' Times';
            $nestedData[] = '<a href="#" data-tippy-content="'.$this->getLastAbandonedByQuestID($row['id']).'">'.$row["QuestAbandonedTimes"] . ' Times';
            $nestedData[] = '<a href="#" data-tippy-content="'.$this->getLastCompletedByQuestID($row['id']).'">'.$row["QuestCompletedTimes"] . ' Times';
            $nestedData[] = '<a href="#" data-tippy-content="'.$this->getLastAcceptedByDate($row['id']).'">'.$row["QuestLastAccepted"];
            $nestedData[] = '<a href="#" data-tippy-content="'.$this->getLastAbandonedByDate($row['id']).'">'.$row["QuestLastAbandoned"];
            $nestedData[] = '<a href="#" data-tippy-content="'.$this->getLastCompletedByDate($row['id']).'">'.$row["QuestLastCompleted"];
            $data[] = $nestedData;
        }
            if(!empty($requestData['search']['value']))
                $totalFiltered = $result->num_rows;

        $json_data = array(
            "draw" => intval($requestData['draw']),   // for every request/draw by client side , they send a number as a parameter, when they receive a response/data they first check the draw number, so we are sending same number in draw.
            "recordsTotal" => intval($totalData),  // total number of records
            "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
            "data" => $data   // total data array
        );
        echo json_encode($json_data);  // send data as json format
        $this->db->close();
    }

}
if (!isset($_POST['action']) || empty($_POST['action'])) {
} else {
    $class = new Functions();
    $action = $_POST['action'];
    switch ($action) {
        case 'getTableData' :
            $class->connect();
            $class->getTableData();

            break;
    }
}


