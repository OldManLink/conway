<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2 Final//EN">
<HTML>
  <HEAD>
    <TITLE>Conway's Date Quizzer</TITLE>
  </HEAD>
  <BODY BGCOLOR="#B1B3BC" TEXT="#FFFFFF" LINK="#FF0000" VLINK="#800000" ALINK="#FF00FF">
    <form action="/" method="POST">
      <?
        $tToday = time();
        $tConway = $_POST["conway"];
        $tGuess = $_POST["guess"];
        $tAnswer = getDayIndex($tConway);
        $tDay = getDay($tAnswer);
        $tYesNo = ($tAnswer == $tGuess) ? 'Yes' : 'No';
        $tIsWas = (strtotime($tConway) <= $tToday) ? 'was' : 'will be';
        
        if(isset($tGuess)) print("$tYesNo! $tConway $tIsWas a $tDay.");

        print("	<h4>Please calculate the weekday (or guess)...</h4>");
		$tDate = randomDate('1900-01-01', '2099-12-31');
		print($tDate);
		print(" <select name='guess'> ".
              "<option value='1'>Monday</option> ".
              "<option value='2'>Tuesday</option> ".
              "<option value='3'>Wednesday</option> ".
              "<option value='4'>Thursday</option> ".
              "<option value='5'>Friday</option> ".
              "<option value='6'>Saturday</option> ".
              "<option value='0'>Sunday</option> ".
              "</select>\n");
        print(" <input type='hidden' name='conway' value='$tDate'>");
        print(" <input type='submit' value='Guess'>");
        print(" </form>");

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
      ?>
  </BODY>
</HTML>
