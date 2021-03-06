<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2 Final//EN">
<html>
    <head>
        <title>Conway's Date Quizzer</title>
        <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="HandheldFriendly" content="true">
        <script type="text/javascript" src="conway.js"></script>
        <link rel="stylesheet" type="text/css" href="conway.css">
        <link rel="apple-touch-icon" sizes="57x57" href="icons/conway_57.png" />
        <link rel="apple-touch-icon" sizes="72x72" href="icons/conway_72.png" />
        <link rel="apple-touch-icon" sizes="114x114" href="icons/conway_114.png" />
        <link rel="apple-touch-icon" sizes="144x144" href="icons/conway_144.png" />
    </head>
    <? include_once "conway.php"; ?>
    <body>
        <div id='container'>
            <?
            print "<form id='theForm' action='$tRoot' method='POST'>\n";
            print "$t[4]<input type='hidden' name='flags' value=$pFlags>\n";
            print "$t[4]<input type='hidden' name='guesses' value=$tGuesses>\n";
            print "$t[4]<input type='hidden' name='data' value=$tData>\n";
            if($pPaused)
            {
                print "$t[4]<div id='ui' name='paused'>\n";
                print "$t[5]<div id='prompt' align=center>Please press<br>&quot;Resume&quot;<br>to continue.</div>\n";
                print "$t[5]<button type='button' id='conwaySubmit' onclick='pauseResume()'>Resume</button>\n";
                printAnswerDivs();
                print "$t[4]</div>\n";
            }
            else
            {
                print "$t[4]<div id='ui' name='standard'>\n";
                print "$t[5]<div id='prompt' align=center>Please calculate<br>(or guess)<br>the weekday:</div>\n";
                print "$t[5]<div id='theDate' align=center>$tDate</div>\n";
                print "$t[5]<div align=center>\n";
                    print "$t[6]<select name='guess'>\n";
                        printDayOptions();
                    print "$t[6]</select>\n";
                print "$t[5]</div>\n";
                print "$t[5]<button id='conwaySubmit'>Guess</button>\n";
                printAnswerDivs();
                print "$t[4]</div>\n";
            }
            print "$t[4]<button type='button' disabled id='drawerHandle'>&nbsp;</button>\n";
            print "$t[3]</form>\n"
            ?>
        </div>
    </body>
</html>
