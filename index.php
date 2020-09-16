<?php
include_once('includes/functions.php');
$func = new Functions();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>TrinityCore QuestTracker System</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/dark.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css"/>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/jq-3.3.1/dt-1.10.21/cr-1.5.2/fh-3.1.7/datatables.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://unpkg.com/tippy.js@6"></script>
    <?= $func->getTooltipScriptURL(); ?>
</head>
<body>
<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
    <div class="navbar-brand" href="#">TrinityCore QuestTracker</div>

    <div class="containeer" id="Collap">
        <label id="switch" class="switch">
            <input type="checkbox" onchange="toggleTheme()" id="slider">
            <span class="slider round"></span>
        </label>
    </div>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#Collap" aria-controls="Collap" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="Collap">
        <ul class="navbar-nav mr-auto"></ul>
        <form class="form-inline my-2 my-lg-0"><input id="searchField" class="form-control mr-sm-2" type="text" placeholder="Search for quests" aria-label="Search for quests"></form>
    </div>
</nav>
<div class="content" id="content" style="margin-top:50px; width:98%;  margin:50px auto;">
    <table id="QuestTable" class="table table-striped table-bordered dt-head-center" style="width:100%">
        <thead>
        <tr>
            <th id="DTAA" class="cell_header">ID</th>
            <th id="DTAB" class="cell_header">Quest Name</th>
            <th id="DTAC" class="cell_header">Total Tries</th>
            <th id="DTAD" class="cell_header">Accepted</th>
            <th id="DTAE" class="cell_header">Abandoned</th>
            <th id="DTAF" class="cell_header">Completed</th>
            <th id="DTAH" class="cell_header">Last Accepted</th>
            <th id="DTAI" class="cell_header">Last Abandoned</th>
            <th id="DTAJ" class="cell_header">Last Completed</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>
<script>

    // function to set a given theme/color-scheme
    function setTheme(themeName) {localStorage.setItem('theme', themeName);document.documentElement.className = themeName;}
    // function to toggle between light and dark theme
    function toggleTheme() {if (localStorage.getItem('theme') === 'theme-dark') setTheme('theme-light'); else setTheme('theme-dark');}

    // Immediately invoked function to set the theme on initial load
    (function () {if (localStorage.getItem('theme') === 'theme-dark') {setTheme('theme-dark');document.getElementById('slider').checked = false;} else {setTheme('theme-light');document.getElementById('slider').checked = true;}})();
    tippy('#DTAA', { content: 'Quest ID',});
    tippy('#DTAB', { content: 'Quest name',});
    tippy('#DTAC', { content: 'How many times users had interaction with this quest (Accepted/Abandoned/Completed)',});
    tippy('#DTAD', { content: 'How many times quest was accepted',});
    tippy('#DTAE', { content: 'How many times quest was abandoned',});
    tippy('#DTAF', { content: 'How many times quest was completed',});
    tippy('#DTAH', { content: 'Last time quest was accepted',});
    tippy('#DTAI', { content: 'Last time quest was abandoned',});
    tippy('#DTAJ', { content: 'Last time quest was completed',});
    $(document).ready(function () {
        var dataTable = $('#QuestTable').DataTable({
            "responsive": true,
            "processing": true,
            "serverSide": true,
            "dataType": "json",
            "cache": true,
            "ajax": {url: "includes/functions.php", data: {action: 'getTableData'}, type: 'post'},
            "initComplete": function(settings, json) {
                $('#searchField').keyup(function(){dataTable.search($(this).val()).draw() ;});
                $('#length_change').change( function() {dataTable.page.len( $(this).val() ).draw();});
                $(".dataTables_filter").hide();
            },
            dom:
            "<'row'<'col-sm-6'B><'col-sm-6'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-4'l><'col-sm-4 text-center'i><'col-sm-4'p>>",
        });
        $('#QuestTable').on( 'draw.dt', function () {$('[data-toggle="tooltip"]').tooltip();tippy('[data-tippy-content]', {allowHTML: true, maxWidth: 800,});});
    });
</script>
</body>
</html>