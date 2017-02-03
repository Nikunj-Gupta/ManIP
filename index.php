<?php

    //Check login session.
    session_start();

    if(!isset($_SESSION['login']) || $_SESSION['login'] != "yes" || $_SESSION['login'] == "")
    {
        header("Location: login.php");
        exit();
    }

    require_once('classes/ticket.php');
    require_once('functions.php');

    $ticket = new ticket();
    $ticket-> db_open();

    if(isset($_GET['page']) && filter_var($_GET['page'], FILTER_VALIDATE_INT)) 
    {
        $page = $_GET['page'];
    } 
    else $page = 0;

    if($_SESSION['userlevel'] == 3 || $_SESSION['userlevel'] == 4) 
    {
        $admin = true;
    } 
    else $admin = false;

    $participant=false;
    if($_SESSION['userlevel'] == 5) {
      $participant = true;
      } 

    if(isset($_GET['search'])) 
    {
        $gettickets = $ticket->
        search_tickets($_GET['search'], $page, $admin, $_SESSION['uid']);
    }

    elseif(isset($_GET['from']) && isset($_GET['to'])) 
    {

        $pattern = '^(\d{4})\D?(0[1-9]|1[0-2])\D?([12]\d|0[1-9]|3[01])$^';

        if(preg_match($pattern, $_GET['from']) && preg_match($pattern, $_GET['to'])) 
        {
            $day = 86400;
            $from = strtotime($_GET['from']);
            $to = strtotime($_GET['to']) + $day;

            if(!$admin) 
            {
            if(!$participant)
              {
                $gettickets = $ticket->
                get_all_tickets(/*$_SESSION['uid'],*/ $page, 'DESC', 'edittstamp', 15, $from, $to);
              }
            else
              {
              $gettickets = $ticket->
              get_patented_tickets($_SESSION['uid'], $page, 'DESC', 'edittstamp', 15, $from, $to);  
              }
            } 
            else 
            {
                $gettickets = $ticket-> get_all_tickets($page, 'DESC', 'edittstamp', 15, $from, $to);
            }
        } 
     
        else $error = "Invalid dates.";
    }	

    elseif(isset($_GET['status'])) 
    {

        if($_GET['status'] == 'Pending' || $_GET['status'] == 'Approved' || $_GET['status'] == 'Open' || $_GET['status'] == 'Rejected' || $_GET['status']== 'Ordered' || $_GET['status'] == 'Procured') 
        {
            if(!$admin) 
            {
              if(!$participant)
                  {
                    $gettickets = $ticket->
                    get_all_tickets(/*$_SESSION['uid'],*/ $page, 'DESC', 'edittstamp', 15, NULL, NULL, $_GET['status']);
                  }
                  else
                  {
                    $gettickets = $ticket->
                    get_all_tickets($page, 'DESC', 'edittstamp', 15, NULL, NULL, 'Procured'); 
                  }            
    } 
            else 
            {
            $gettickets = $ticket-> get_all_tickets($page, 'DESC', 'edittstamp', 15, NULL, NULL, $_GET['status']);
            }
        } 
        else 
        {
            $error = "Invalid status filter.";
        }
    }

    elseif($admin) 
    {
        $gettickets = $ticket-> get_all_tickets($page);
    } 
    else 
    {
if(!$participant)
  {
    $gettickets = $ticket->
    get_all_tickets(/*$_SESSION['uid'],*/ $page);
  }
  else
  {
    $gettickets = $ticket->
    get_all_tickets($page, 'DESC', 'edittstamp', 15, NULL, NULL, 'Procured'); 
  }    }

    if(isset($gettickets) && !$gettickets) 
    {
        $error = $ticket-> get_error();
    } 
    elseif (isset($gettickets) && $gettickets) 
    {
        $totalrows = $ticket-> get_total_rows();		
    }
?>

<!DOCTYPE html>
<html>
  <head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title>
        IPMS - View IP Assets
  </title>
  <link rel="stylesheet" href="styles/stylesheet.css" type="text/css" media="screen">
  <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800|Open+Sans+Condensed:300,700' rel='stylesheet' type='text/css'>
  <link rel="stylesheet" href="http://code.jquery.com/ui/1.8.20/themes/base/jquery-ui.css" type="text/css" media="all" />
  <script src="http://code.jquery.com/jquery-latest.js">
  </script>
  <script src="http://code.jquery.com/ui/1.8.20/jquery-ui.min.js" type="text/javascript">
  </script>
  <script type="text/javascript">
    $(document).ready(function() {
      
      $(function() {
		var dates = $( "#from, #to" ).datepicker({
          defaultDate: "+1w",
          dateFormat: "yy-mm-dd",
          changeMonth: true,
          numberOfMonths: 3,
          onSelect: function( selectedDate ) {
            var option = this.id == "from" ? "minDate" : "maxDate",
                instance = $( this ).data( "datepicker" ),
                date = $.datepicker.parseDate(
                  instance.settings.dateFormat ||
                  $.datepicker._defaults.dateFormat,
                  selectedDate, instance.settings );
            dates.not( this ).datepicker( "option", option, date );
          }
        }
                                                );
		}
         );
          
          $('#tickettable tr').click(function() {
	        var href = $(this).find("a").attr("href");
          if(href) {
            window.location = href;
          }
        }
                                  );
		}
                         );
  </script>
  
  </head>
  <body>
	<br>
  <center>
    <a href="index.php" style="text-decoration: none;" >
      <span style="color:black">
        <b>
          <font size="6">
            IP Asset Management System
          </font>
        </b>
      </span>
    </a>
  </center>
  
  <nav>
    <ul id="navigation">
      <li>
        <a href="index.php">
          View IP Assets
        </a>
      </li>

	<?php if($_SESSION['userlevel'] != 5) echo '
  <li>
      <a href="send.php">
        Submit new Asset
      </a>
  </li>
  '; ?>

  <li>
    <a href="profile.php">
      Change Password
    </a>
  </li>
<?php if($_SESSION['userlevel'] == 4) echo '
<li>
<a href="ipc.php">
Admin Panel
</a>
</li>
'; ?>
 <li>
    <a href="logout.php">
      Log Out
    </a>
  </li>
  </ul>
  </nav>
  
  
  <div class="main viewtickets">
    <div id="spacer">
      <div class="blacksmall">
        IP Assets
      </div>
    </div>
    <div id="optionbar">
      <div class="whitemedium" style="float: left; margin-top: 35px; margin-left: 20px;">
        Status:
      </div>
      <div id="filterbox">


      <?php if($_SESSION['userlevel'] != 5) echo '

		<form action="index.php" method="get">
          <input type="radio" name="status" value="Open" />
          Open
          <br />
          <!-- <input type="radio" name="status" value="Pending" />
          Pending
          <br /> -->
          <input type="radio" name="status" value="Approved" />
          Approved
          <br />
         <!-- <input type="radio" name="status" value="Rejected" />
          Rejected
          <input type="radio" name="status" value = "Ordered" />
          Ordererd -->
          <input type="radio" name="status" value = "Procured" />
          Procured
          <br />

                '; ?>

      </div>


      <?php if($_SESSION['userlevel'] != 5) echo '

      <div style="float: left; margin-top: 55px;">
        <input type="submit" style="background-color: silver; color: black;" class="submitbutton" id="submit" value="Search">
      </input>
  </div>

  '; ?>'

  </form>
  
  <form action="index.php" method="GET">
	<input type="submit" id="searchimage" value="" />
      <input id="searchfield" name="search" type="text" />
  </form>
  </div>
  
  <table id="tickettable">
	<thead>
      <tr>
        <th class="status" scope="col">
          Status
        </th>
        <th class="subject" scope="col">
          Subject
        </th>
        <th class="time" scope="col">
          Last Update
        </th>
        <th class="time" scope="col">
          Created
        </th>
      </tr>
  </thead>
  <tbody>
    <?php
if(isset($error)) {
echo '
<div class="notification error">
' . htmlspecialchars($error, ENT_QUOTES) . '
</div>
';
} else {
foreach($gettickets as $row) {
echo '
<tr>
<td class="' . htmlspecialchars($row['status'], ENT_QUOTES) . '">
' . htmlspecialchars($row['status'], ENT_QUOTES) . '
</td>
<td>
<a href="design.php?tid=' . htmlspecialchars($row['tid'], ENT_QUOTES) . '">
' . htmlspecialchars($row['subject'], ENT_QUOTES) . '
</a>
</td>
<td>
' . time_ago($row['edittstamp']) . '
</td>
<td>
' . time_ago($row['tstamp']) . '
</td>
</tr>
';
}
}
?>
  </tbody>
  </table>
  
  <?php
if(!isset($error))
{
if(isset($_GET['search'])) {
$append = '&search=' . $_GET['search'];
} elseif(isset($_GET['from'])) {
$append = '&from=' . $_GET['from'] . '&to=' . $_GET['to'];
} elseif(isset($_GET['status'])) {
$append = '&status=' . htmlspecialchars($_GET['status'], ENT_QUOTES);
} else $append = NULL;

if($page != 0) {
$prevpage = $page - 1;
echo '
<div class="blacksmall" style="font-size: 13pt;">
<a href="index.php?page=' . $prevpage . $append . '">
&lt;&lt; Previous
</a>
';
} else  {
echo '
<div class="blacksmall" style="font-size: 13pt;">
&lt;&lt; Previous';
}

if($totalrows >
15 && $page * 15 + 15 
< $totalrows) {
$nextpage = $page + 1;
echo '  
<a href="index.php?page=' . $nextpage . $append . '">
Next &gt;&gt;
</a>
</div>
';
} else {
echo '  Next &gt;&gt;
</div>
';
}
}
?>
</div>

</body>
</html>