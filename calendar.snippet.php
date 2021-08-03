<?php
/* Events / Calendar PHP Snippet
* 
*/

$calendar_id = $modx->resource->get('id');
$calendar_alias = $modx->resource->get('alias');

$events = $modx->getCollection('modResource', array('published'=>'1','deleted'=>'0','parent'=>$calendar_id,'class_key'=>'modDocument'));

function build_calendar($month,$year,$dateArray,$events,$calendar_alias) {

     // Create array containing abbreviations of days of week.
     $daysOfWeek = array('Sun','Mon','Tues','Wed','Thurs','Fri','Sat');

     // What is the first day of the month in question?
     $firstDayOfMonth = mktime(0,0,0,$month,1,$year);

     // How many days does this month contain?
     $numberDays = date('t',$firstDayOfMonth);

     // Retrieve some information about the first day of the
     // month in question.
     $dateComponents = getdate($firstDayOfMonth);

     // What is the name of the month in question?
     $monthName = $dateComponents['month'];

     // What is the index value (0-6) of the first day of the
     // month in question.
     $dayOfWeek = $dateComponents['wday'];

     // Create the table tag opener and day headers
     $calendar = "<h2 class='text-secondary py-4'>$monthName $year</h2>";
     $calendar .= "<table class='calendar'>";
     $calendar .= "<tr>";

     // Create the calendar headers

     foreach($daysOfWeek as $day) {
          $calendar .= "<th class='header'>$day</th>";
     } 

     // Create the rest of the calendar

     // Initiate the day counter, starting with the 1st.

     $currentDay = 1;

     $calendar .= "</tr><tr>";

     // The variable $dayOfWeek is used to
     // ensure that the calendar
     // display consists of exactly 7 columns.

     if ($dayOfWeek > 0) { 
          $calendar .= "<td colspan='$dayOfWeek' class='not-month'>&nbsp;</td>"; 
     }
     
     $month = str_pad($month, 2, "0", STR_PAD_LEFT);
  
     while ($currentDay <= $numberDays) {

          // Seventh column (Saturday) reached. Start a new row.

          if ($dayOfWeek == 7) {

               $dayOfWeek = 0;
               $calendar .= "</tr><tr>";

          }
          
          $currentDayRel = str_pad($currentDay, 2, "0", STR_PAD_LEFT);
          
          $date = "$year-$month-$currentDayRel";
          
          $event_link = "";
          if(count($events)){
              foreach($events as $resource){
                    $start_date = date("Y-m-d", strtotime($resource->getTVValue('start_date')));
                    $end_date = date("Y-m-d", strtotime($resource->getTVValue('end_date')));
                    
                    if($start_date == $date || $end_date == $date){
                        $event_link .= '<p class="p-1"><a href="/'.$calendar_alias.'/'.$resource->get('alias').'.html">'.$resource->get('pagetitle').'</a></p>';
                    }
                    
                    if($date > $start_date && $date < $end_date){
                        $event_link .= '<p class="p-1"><a href="/'.$calendar_alias.'/'.$resource->get('alias').'.html">'.$resource->get('pagetitle').'</a></p>';
                    }
              }
          }
          
          if ($date == date("Y-m-d")){
           $calendar .= "<td class='day today' rel='$date'><small class='today-date'>$currentDay</small>$event_link</td>";
          }
          else{
           $calendar .= "<td class='day' rel='$date'><small class='day-date'>$currentDay</small>$event_link</td>";
          }
          
          // Increment counters
 
          $currentDay++;
          $dayOfWeek++;

     }

     // Complete the row of the last week in month, if necessary

     if ($dayOfWeek != 7) { 
          $remainingDays = 7 - $dayOfWeek;
          $calendar .= "<td colspan='$remainingDays' class='not-month'>&nbsp;</td>"; 
     }
     
     $calendar .= "</tr>";
     $calendar .= "</table>";

     return $calendar;

}

function build_previousMonth($month,$year,$monthString,$calendar_alias){
 
 $prevMonth = $month - 1;
  
  if ($prevMonth == 0) {
   $prevMonth = 12;
  }
  
 if ($prevMonth == 12){  
  $prevYear = $year - 1;
 } else {
  $prevYear = $year;
 }
 
 $dateObj = DateTime::createFromFormat('!m', $prevMonth);
 $monthName = $dateObj->format('F'); 
 
 return "<div style='width: 33%; display:inline-block;'><a class='btn btn-sm btn-outline-secondary' href='/".$calendar_alias."/?m=" . $prevMonth . "&y=". $prevYear ."'><- " . $monthName . "</a></div>";
}

function build_nextMonth($month,$year,$monthString,$calendar_alias){
 
 $nextMonth = $month + 1;
  
  if ($nextMonth == 13) {
   $nextMonth = 1;
  }
 
  if ($nextMonth == 1){  
   $nextYear = $year + 1;
  } else {
   $nextYear = $year;
  }
 
 $dateObj = DateTime::createFromFormat('!m', $nextMonth);
 $monthName = $dateObj->format('F'); 
 
 return "<div style='width: 33%; display:inline-block;'>&nbsp;</div><div style='width: 33%; display:inline-block; text-align:right;'><a class='btn btn-sm btn-outline-secondary' href='/".$calendar_alias."/?m=" . $nextMonth . "&y=". $nextYear ."'>" . $monthName . " -></a></div>";
}

$m = $modx->getOption('m', $_GET, "");
 
if ($m == ""){

 $dateComponents = getdate();
 $month = $dateComponents['mon'];
 $year = $dateComponents['year'];

} else {

 $month = $m;
 $year = $modx->getOption('y', $_GET, "");

}

 $output = build_previousMonth($month, $year, $monthString,$calendar_alias);
 $output .= build_nextMonth($month,$year,$monthString,$calendar_alias);
 $output .= build_calendar($month,$year,$dateArray,$events,$calendar_alias);
 return $output;
