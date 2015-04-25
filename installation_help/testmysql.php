 <?php
  echo "Current PHP version: <b> ". phpversion() ."</b>";
  
  
  /*
  change pass to MYSQL password!!! 
  $link = mysql_connect("localhost", "root","pass") or die("Could not connect");
*/
  
  
  $link = mysql_connect("localhost", "root","pass") or die("Could not connect");
  if( !$link ) die( mysql_error() );
  
  $db_list = mysql_list_dbs($link);
  
  while ($row = mysql_fetch_object($db_list)) 
  {
    echo "<h3>Database \"".$row->Database."\"</h3>\n";
    $result = mysql_list_tables($row->Database); 
    
    if(!$result) die( "DB Error, could not list tables\n MySQL Error: ".mysql_error() );
    else {
      while ($row = mysql_fetch_row($result))
      print "Table: $row[0]<br>";
      mysql_free_result($result);
    }
  }
  
  ?>