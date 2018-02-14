<?
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set up tab stops: $t[0] = no tabs, $t[9] = 9 tabs. 1 tab = 4 spaces.
unset($t);$t[0]="";for($i=1;$i<10;$i++)$t[$i]=$t[$i-1]."    ";
$sp = "<font size=+5>&nbsp;</font>";

$tGetArgsCount = count($_GET);
$tPostArgsCount = count($_POST);
$tToday = time();

if(isset($_POST["conway"]))
{
    $pConway = $_POST["conway"];
    $pGuess = $_POST["guess"];
    $tAnswer = getDayIndex($pConway);
    $tYesNo = ($tAnswer == $pGuess) ? 'Correct' : 'Wrong';
    $tIsWas = (strtotime($pConway) <= $tToday) ? 'was' : 'will be';
    $tDay = getDay($tAnswer);
}
$pFlags = isset($_GET['flags']) ? $_GET['flags'] : 
		(isset($_POST['flags']) ? $_POST['flags'] : 42);
$tPrintDebug = $pFlags == 73;
$tDate = randomDate('1800-01-01', '2199-12-31');

if ($tPrintDebug)
{
    print "<!-- debugging output...\n";
    printDebug("POST: conway - '$pConway'; guess - '$pGuess'; flags - '$pFlags'");
    printDebug("TEMPS: tAnswer - '$tAnswer'; tDay - '$tDay'");
    print "$t[1] ...end debugging output -->\n";
}
	
// Find a randomDate between $start_date and $end_date
function randomDate($start_date, $end_date)
{
    // Convert to timetamps
    $min = strtotime($start_date);
    $max = strtotime($end_date);

    // Generate random number using above bounds
    $val = rand($min, $max);

    // Convert back to desired date format
    return date('Y-m-d', $val);
}

// Find the weekday index for a given date
function getDayIndex($dateString)
{
    return date('w', strtotime($dateString));
}

// Find the weekday for a given day index
function getDay($dayIndex)
{
    return date('l', strtotime("Sunday +{$dayIndex} days"));
}

function printDayOptions($theDate)
{
    global $t;
    $tKeyDays = getCenturyDays($theDate);
    foreach (array(1, 2, 3, 4, 5, 6, 0) as $dayIndex) 
    {
        $tDay = getDay($dayIndex);
        print "$t[5]<option $tKeyDays[$dayIndex]value='$dayIndex'>$tDay</option>\n";
    }
}

function getCenturyDays($theDate)
{
    $tCentury = intval(date('Y', strtotime($theDate)) / 100);
    $tKeyDay = (2 + 5 * ($tCentury % 4)) % 7;
    $tCDays = array();
    foreach (array(1, 2, 3, 4, 5, 6, 0) as $dayIndex) 
    {
        $tCDays[$dayIndex] = $dayIndex == $tKeyDay ? "selected " : "";
    }
    return $tCDays;
}

function printDebug($message)
{
	global $tPrintDebug, $t;
	if ($tPrintDebug) print "$t[1]$message\n";
}
?>
