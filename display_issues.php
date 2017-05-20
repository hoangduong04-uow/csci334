<?php
    include('dbcontroller.php');

    $conn = new DBController();
    $table = "ISSUE";
    $qdrop = "DROP TABLE IF EXISTS `CSCI334`.`".$table."`";

    $project = $_GET['jira_project'];
    $url = "https://issues.apache.org/jira/rest/api/2/search?jql=project=$project";//."&maxResults=5000";
    $issue_collection_json = file_get_contents($url);
    $issue_collection_array = json_decode($issue_collection_json, true);

    function create_table($table_name, $db_name, $connection, $attributes) {
      $queries = array();
      $queries[] = "DROP TABLE IF EXISTS `".$db_name."`.`".$table."`;";
      $queries[] = "CREATE TABLE `".$db_name."`.`".$table."`(`NAME` varchar(60), `ID` varchar(12)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
    }


    /*
    $issue_id = $jira_array['key'];
    $issue_name = $jira_array['fields']['summary'];
    $issue_type = $jira_array['fields']['issuetype']['name'];
    $issue_creator = $jira_array['fields']['creator']['displayName'];
    $issue_assignee = $jira_array['fields']['assignee']['displayName'];

    $pull_count = 0;
    $commit_count = 0;
    $commit_id = array();
    $url = array();
    foreach ($jira_array['fields']['comment']['comments'] as $comment) {
      $content = $comment['body'];
      preg_match('/^commit\s(.*)/im', $content, $matches, PREG_OFFSET_CAPTURE);
      if(!($matches[1][0] == "" || $matches[1][0] == "\n")) {
        $commit_id[] = $matches[1][0];
        $commit_count++;
      }
      
      preg_match('/^.* pull request.*\s*(https:.*)/im', $content, $matches2, PREG_OFFSET_CAPTURE);
      if((!($matches2[1][0] == "" || $matches2[1][0] == "\n")) && (!($matches[1][0] == "" || $matches[1][0] == "\n"))) {
        $url[] = $matches2[1][0].'/commits/'.$matches[1][0];
        $pull_count++;
      }
      
    }
    */

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Commit Collector</title> 
    <!--<link rel="stylesheet" type="text/css" href="css/style.css?<?php echo time(); ?>">
    <link rel="stylesheet" type="text/css" href="css/jira.css?<?php echo time(); ?>">-->
</head>

<body>
  <div class="container">
  <div id="info-container">
    <h3>Collected information</h3>
    <h4>Issue collection</h4>

    <?php
    $collection = $issue_collection_array['issues'];
    echo "<table><tr><th>ID</th><th>Name</th></tr>";
    foreach ($collection as $issue) {
      $issue_id = $issue['key'];
      //$info['name'] = $issue['fields']['issuetype']['description'];
      //print_r($info);
      echo "<tr><td><a href=\"jira.php?jira_issue=$issue_id&submit=\">$issue_id</a></td><td>".$issue['fields']['issuetype']['description']."</td></tr>";
    }
    echo "</table>";
    ?>

  <pre>
  <?php
    echo "=====================matches======================================<br>";
    print_r($collection);
    /*
    echo "=====================matches======================================<br>";
    print_r($matches);
    echo "=====================commit======================================<br>";
    print_r($commit_id);
    echo "=====================url======================================<br>";
    print_r($url);
    echo "===========================================================<br>";
    print_r($jira_array);
    */
  ?>
  </pre>

</body>