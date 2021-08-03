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
                    $ticket_link = $resource->getTVValue('event_link');
                    $type = $resource->getTVValue('event_type');
                    $icon = "";
                    
                    if($type == "Paid"){ $icon = '<svg style="margin-right:5px;" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-coin" viewBox="0 0 16 16"><path d="M5.5 9.511c.076.954.83 1.697 2.182 1.785V12h.6v-.709c1.4-.098 2.218-.846 2.218-1.932 0-.987-.626-1.496-1.745-1.76l-.473-.112V5.57c.6.068.982.396 1.074.85h1.052c-.076-.919-.864-1.638-2.126-1.716V4h-.6v.719c-1.195.117-2.01.836-2.01 1.853 0 .9.606 1.472 1.613 1.707l.397.098v2.034c-.615-.093-1.022-.43-1.114-.9H5.5zm2.177-2.166c-.59-.137-.91-.416-.91-.836 0-.47.345-.822.915-.925v1.76h-.005zm.692 1.193c.717.166 1.048.435 1.048.91 0 .542-.412.914-1.135.982V8.518l.087.02z"/><path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/><path fill-rule="evenodd" d="M8 13.5a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11zm0 .5A6 6 0 1 0 8 2a6 6 0 0 0 0 12z"/></svg>'; }
                    if($type == "Luncheon"){ $icon = '<svg style="margin-right:5px;" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-basket" viewBox="0 0 16 16"><path d="M5.757 1.071a.5.5 0 0 1 .172.686L3.383 6h9.234L10.07 1.757a.5.5 0 1 1 .858-.514L13.783 6H15a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1v4.5a2.5 2.5 0 0 1-2.5 2.5h-9A2.5 2.5 0 0 1 1 13.5V9a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h1.217L5.07 1.243a.5.5 0 0 1 .686-.172zM2 9v4.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V9H2zM1 7v1h14V7H1zm3 3a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 4 10zm2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 6 10zm2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 8 10zm2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 1 .5-.5zm2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 1 .5-.5z"/></svg>';}
                    if($type == "Speaker"){ $icon = '<svg style="margin-right:5px;" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-mic-fill" viewBox="0 0 16 16"><path d="M5 3a3 3 0 0 1 6 0v5a3 3 0 0 1-6 0V3z"/><path d="M3.5 6.5A.5.5 0 0 1 4 7v1a4 4 0 0 0 8 0V7a.5.5 0 0 1 1 0v1a5 5 0 0 1-4.5 4.975V15h3a.5.5 0 0 1 0 1h-7a.5.5 0 0 1 0-1h3v-2.025A5 5 0 0 1 3 8V7a.5.5 0 0 1 .5-.5z"/></svg>'; }
                    
                    if($start_date == $date || $end_date == $date){
                        $id = $resource->get('id');
                        $event_link .= '<p class="p-1"><a href="/'.$calendar_alias.'/'.$resource->get('alias').'.html">'.$icon.$resource->get('pagetitle').'</a></p>';
                    }
                    
                    if($date > $start_date && $date < $end_date){
                        $id = $resource->get('id');
                        $event_link .= '<p class="p-1"><a href="/'.$calendar_alias.'/'.$resource->get('alias').'.html">'.$icon.$resource->get('pagetitle').'</a></p>';
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
