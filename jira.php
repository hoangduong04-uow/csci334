<?php
    $issue = $_GET['jira_issue'];
    $url = "https://issues.apache.org/jira/rest/api/2/issue/$issue";
    $jira_json = file_get_contents($url);
    $jira_array = json_decode($jira_json, true);

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
      
      preg_match('/^.* pull request.*:\s*(https:.*)/im', $content, $matches2, PREG_OFFSET_CAPTURE);
      if((!($matches2[1][0] == "" || $matches2[1][0] == "\n")) && (!($matches[1][0] == "" || $matches[1][0] == "\n"))) {
        $url[] = $matches2[1][0].'/commits/'.$matches[1][0];
        $pull_count++;
      }
      
    }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Commit Collector</title> 
    <link rel="stylesheet" type="text/css" href="css/style.css?<?php echo time(); ?>">
    <link rel="stylesheet" type="text/css" href="css/jira.css?<?php echo time(); ?>">
</head>

<body>
  <div class="container">
  <div id="info-container">
    <h3>Collected information</h3>
    <h4>Issue details</h4>
    <fieldset>
      <ul >
      <?php
        echo "<li><b>Issue ID:</b> $issue_id</li>";
        echo "<li><b>Issue name:</b> $issue_name</li>";
        echo "<li><b>Issue type:</b> $issue_type</li>";
        echo "<li><b>Issue creator:</b> $issue_creator</li>";
        echo "<li><b>Issue assignee:</b> $issue_assignee</li>";
      ?>    
      </ul>
    </fieldset>
    <br>
    <h3>GitHub resources</h3>
    <?php
    echo "<h4>$pull_count pull requests and $commit_count commits were found!</h4>";
    for ($i = 0; $i < $pull_count; $i++) {
      echo "<fieldset><ul>";
      if (!($commit_id[$i] == "" || $commit_id[$i] == "\n" || $commit_id[$i] == null))
        echo "<li><b>Commit ID: </b>".$commit_id[$i]."</li>";
      if (!($url[$i] == "" || $url[$i] == "\n" || $url[$i] == null))
        echo "<li>Link to pull request:</b> <a href=\"".$url[$i]."\">Click here to see GitHub pull request</a></li>";
      echo "</ul></fieldset>";
    }
    ?>
    <fieldset id="home-button-field"><a id="home-button" href="index.html">Home</a></fieldset>
  </div>
  </div>

  <pre>
  <?php
  
    echo "=====================matches======================================<br>";
    print_r($matches);
    echo "=====================commit======================================<br>";
    print_r($commit_id);
    echo "=====================url======================================<br>";
    print_r($url);
    echo "===========================================================<br>";
    print_r($jira_array);
  
  ?>
  </pre>

</body>