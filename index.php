<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2 Final//EN">
<html>
    <head>
        <title>Conway's Date Quizzer</title>
        <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="HandheldFriendly" content="true">
        <link rel="stylesheet" type="text/css" href="conway.css">
        <link rel="apple-touch-icon" sizes="57x57" href="icons/conway_57.png" />
        <link rel="apple-touch-icon" sizes="72x72" href="icons/conway_72.png" />
        <link rel="apple-touch-icon" sizes="114x114" href="icons/conway_114.png" />
        <link rel="apple-touch-icon" sizes="144x144" href="icons/conway_144.png" />
    </head>
    <? include_once "conway.php"; ?>
    <body>
        <div id="container">
            <form action="/" method="POST">
                <?
                $tI = 0;
                if($tPostArgsCount == 3)
                {
                    print "$t[$tI]<div align=center><font size='+3'>$tYesNo!<br>$pConway<br>$tIsWas a<br>$tDay.</font></div>\n";
                    $tI = 4;
                }
                print "$t[$tI]<div align=center><font size='+2'><br>Please calculate<br>(or guess)<br>the weekday:</font></div>\n";
                print "$t[4]<div align=center><font size='+3'><br>$tDate</font></div>\n";
                print "$t[4]<div align=center><br><select name='guess'>\n";
                printDayOptions($tDate);
                print "$t[4]</select><br></div>\n";
                print "$t[4]<input type='hidden' name='flags' value=$pFlags>\n";
                print "$t[4]<input type='hidden' name='conway' value=$tDate>\n";
                print "$t[4]<button id='conway_submit'>Guess</button>\n";
                ?>
            </form>
        </div>
    </body>
</html>
