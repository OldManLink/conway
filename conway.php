<?
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Default definitions to make IntelliJ happy
$t = [];
$tAnswer = -1;
$pGuess = -1;
$pFlags = 42;
$pThen = 0;
$tGuesses = [];
$pPaused = FALSE;

// Set up tab stops: $t[0] = no tabs, $t[9] = 9 tabs. 1 tab = 4 spaces.
$t[0]="";for($i=1; $i<10; $i++)$t[$i]=$t[$i-1]."    ";

$cData = isset($_COOKIE["ConwayData"]) ? $_COOKIE["ConwayData"] : NULL;

$tRequestParts = explode("/", $_SERVER['REQUEST_URI']);
$tRequestParts[count($tRequestParts) - 1] = "";
$tRoot = implode("/", $tRequestParts);
$tPostData = isset($_POST["data"]) ? $_POST["data"] : NULL;
$pData = getPostedData($tPostData, $cData);
$pPaused = $pData["pause"];
$tNow = milliseconds();

if(isset($pData["conway"]))
{
    $pConway = $pData["conway"];
    $pThen = $pData["then"];
    $pGuess = isset($_POST["guess"]) ? $_POST["guess"] : 0;
    $pGuesses = json_decode($_POST["guesses"], TRUE);
    $tAnswer = getDayIndex($pConway);
    $tCorrect = $tAnswer == $pGuess;
    $tGuesses = logGuess($pGuesses, $pThen, $tNow, $tCorrect, $pPaused);
}
else
{
    $tGuesses = "[]";
}

$tDate = randomDate('1800-01-01', '2199-12-31');
$tNewData = $pData;
$tNewData["conway"] = $tDate;
$tNewData["century"] = getCenturyDay($tDate);
$tNewData["then"] = $tNow;
$tNewData["day"] = $pThen == 0 ? -1 : $tAnswer;
setCookieData($tNewData);
$tData = json_encode($tNewData);

if(isset($_GET['flags']))
{
    $pFlags = $_GET['flags'];
}
if(isset($_POST['flags']))
{
    $pFlags = $_POST['flags'];
}
$tPrintDebug = $pFlags == 73;

if ($tPrintDebug)
{
    print "<!-- debugging output...\n";
    printDebug("POST: data - '$tPostData'; guess - '$pGuess'; flags - '$pFlags'");
    printDebug("TEMPS: tRoot - '$tRoot'; tAnswer - '$tAnswer'");
    print "$t[1] ...end debugging output -->\n";
}

// Get and return the Posted JSON data object if it exists, and create a new one if not
function getPostedData($pPostData, $pCookieData)
{
    $tJsonData = json_decode($pPostData, TRUE);
    $tCookies = json_decode($pCookieData, TRUE);
    return is_null($tJsonData) ?
        (is_null($tCookies) ? ["pause" => FALSE, "ui" => "standard", "stats" => FALSE] : ["pause" => FALSE, "ui" => $tCookies["ui"], "stats" => $tCookies["stats"]]) :
        $tJsonData;
}

// Find a random Date between $start_date and $end_date
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

// Print the answer and/or paused state
function printAnswerDivs()
{
    global $pConway, $tCorrect, $pPaused, $t;
    if(count($_POST) == 4)
    {
        $tYesNo = $pPaused ? '(Paused)' : ($tCorrect ? 'Correct!' : 'Wrong!');
        print "$t[5]<div class='answer' align=center>$tYesNo</div>\n";
        if(!$tCorrect && !$pPaused)
        {
            $tIsWas = (strtotime($pConway) <= time()) ? 'was' : 'will be';
            $tDay = getDay(getDayIndex($pConway));
            print "$t[5]<div class='answer' align=center>$pConway<br>$tIsWas a</div>\n";
            print "$t[5]<div id='theDay' class='answer' align=center>$tDay</div>\n";
        }
    }
}

// Print the days of the week as options for a select or drop-down list
function printDayOptions()
{
    global $t;
    foreach (array(1, 2, 3, 4, 5, 6, 0) as $dayIndex)
    {
        $tDay = getDay($dayIndex);
        print "$t[7]<option value='$dayIndex'>$tDay</option>\n";
    }
}

// Return the key day for the century
function getCenturyDay($theDate)
{
    $tCentury = intval(date('Y', strtotime($theDate)) / 100);
    return (2 + 5 * ($tCentury % 4)) % 7;
}

// returns the current unix timestamp in milliseconds
function milliseconds()
{
    $mt = explode(' ', microtime());
    return ((int)$mt[1]) * 1000 + ((int)round($mt[0] * 1000));
}

// Add the latest guess to the guesses history and return the array as a JSON string
function logGuess($pGuesses, $pThen, $pNow, $pCorrect, $pPaused)
{
    $tResult = array();
    foreach ($pGuesses as $pGuess)
    {
        array_push($tResult, $pGuess);
    }
    if (!$pPaused && $pThen != 0)
    {
        $tNext = $pCorrect ? $pNow - $pThen : -1;
        array_push($tResult, $tNext);
    }
    return json_encode($tResult);
}

// Set the cookie data to be saved in the browser
function setCookieData($pNewData)
{
    $tData = json_encode(["ui" => $pNewData["ui"], "stats" => $pNewData["stats"]]);
    setcookie("ConwayData", $tData, time() + (10 * 365 * 24 * 60 * 60));
}

// Prints stuff only if the global debug flag is true
function printDebug($message)
{
    global $tPrintDebug, $t;
    if ($tPrintDebug) print "$t[1]$message\n";
}
