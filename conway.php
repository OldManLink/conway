<?
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Default definitions to make IntelliJ happy
$t = [];
$pGuess = 0;
$tAnswer = 0;
$tDay = "Undefined";
$pFlags = 42;
$tGuesses = [];
$tYesNo = "Undefined";
$pConway = "Undefined";
$tIsWas = "Undefined";
$pPaused = FALSE;

// Set up tab stops: $t[0] = no tabs, $t[9] = 9 tabs. 1 tab = 4 spaces.
$t[0]="";for($i=1; $i<10; $i++)$t[$i]=$t[$i-1]."    ";

$tRequestParts = explode("/", $_SERVER['REQUEST_URI']);
$tRequestParts[count($tRequestParts) - 1] = "";
$tRoot = implode("/", $tRequestParts);
$tGetArgsCount = count($_GET);
$tPostArgsCount = count($_POST);
$tPostData = isset($_POST["data"]) ? $_POST["data"] : NULL;
$pData = getPostedData($tPostData);
$pPaused = $pData["pause"];
$tToday = time();
$tNow = milliseconds();

if(isset($pData["conway"]))
{
    $pConway = $pData["conway"];
    $pThen = $pData["then"];
    $pGuess = isset($_POST["guess"]) ? $_POST["guess"] : 0;
    $pGuesses = json_decode($_POST["guesses"], TRUE);
    $tAnswer = getDayIndex($pConway);
    $tCorrect = $tAnswer == $pGuess;
    $tYesNo = $pPaused ? '(Paused)' : ($tCorrect ? 'Correct!' : 'Wrong!');
    $tIsWas = (strtotime($pConway) <= $tToday) ? 'was' : 'will be';
    $tDay = getDay($tAnswer);
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
    printDebug("TEMPS: tRoot - '$tRoot'; tAnswer - '$tAnswer'; tDay - '$tDay'");
    print "$t[1] ...end debugging output -->\n";
}

// Get and return the Posted JSON data object if it exists, and create a new one if not
function getPostedData($pPostData)
{
    $tJsonData = json_decode($pPostData, TRUE);
    return is_null($tJsonData) ? ["stats" => FALSE, "pause" => FALSE] : $tJsonData;
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

// Print the days of the week as options for a select or drop-down list
function printDayOptions()
{
    global $t;
    foreach (array(1, 2, 3, 4, 5, 6, 0) as $dayIndex)
    {
        $tDay = getDay($dayIndex);
        print "$t[6]<option value='$dayIndex'>$tDay</option>\n";
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

// Prints stuff only if the global debug flag is true
function printDebug($message)
{
    global $tPrintDebug, $t;
    if ($tPrintDebug) print "$t[1]$message\n";
}
