<html>

<head>
    <meta charset="utf-8">
    <meta name="description" content="Final Project 332 website with databases.">
    <meta name="author" content="Henry Ho">
    <title>CPSC332|Project</title>
    <link rel="stylesheet" type="text/css" href="./CSS/style.css">
</head>

<body>
    <div id="content">
        <header>
            <div class="container">
                <div id="branding">
                    <h1>Final Project 332</h1>
                </div>
                <nav>
                    <ul>
                        <li><a href="index.html">Home</a></li>
                        <li><a href="Professor.php">Professor</a></li>
                        <li class="current"><a href="Student.php">Student</a></li>
                    </ul>

                </nav>

            </div>
        </header>
        <section id="showcase">
            <div class="container">
                <h3>Computer Science Department at California State University</h3>
                <h5>CPSC 332 Project using University Database</h5>
            </div>
        </section>

        <section>

          <div class="submit">
              <h1>Course Number</h1>
              <form method="POST" action="Student.php">
                  <input type="text" name="course_number" placeholder="Course Number" required>
                  <button type="submit" name="course_submit" class="button_1">Submit</button>
              </form>
          </div>

          <div class="submit">
              <h1>CWID</h1>
              <form method="POST" action="Student.php">
                  <input type="text" name="student_id" placeholder="Campus Wide ID" required>
                  <button type="submit" name="student_id_submit" class="button_1">Submit</button>
              </form>
          </div>


        </section>
    </div>
    <div class="submit">
      <?php
        $hostName='mariadb';
        $userName='cs332t10';
        $password='audaihov';
        $dbName='cs332t10';
        $link=mysql_connect($hostName, $userName,$password);
        if (!$link) {
          die('Could not connect: '.mysql_error());
        }
        //echo "Successfully connected to server."."<br>";
        $db_selected=mysql_select_db($dbName,$link);
        if (!$db_selected) {
          die("Unable to select databases $dbName".mysql_error());
        }
      //  echo "Succesfully connected to database."."<br>";
        function test_input($data){
          $data=trim($data);
          $data=stripslashes($data);
          $data=htmlspecialchars($data);
          return $data;
        }

        //FIRST FORM IF CODE
        if (!empty($_POST["course_number"]) && isset($_POST["course_submit"])) {
          $courseNumber=test_input($_POST["course_number"]);
          if (!preg_match("/^[0-9]*$/",$courseNumber)) {
            echo "<font color=red>Only numbers allowed for Course Number</font>";
          }
          else {
            $sql="SELECT SNumber, Classroom,MeetingDay, BeginningTime, EndingTime, COUNT(DISTINCT StuID)
            FROM SECTION S, ENROLL E , MEETING_DAY M
            WHERE S.CNumberSec=E.CoNumSe
                  AND E.CoNumSe=M.CoNoSec
                  AND S.CNumberSec=$courseNumber
                  AND S.SNumber= E.SecNum
            GROUP BY SNumber";
            $result=mysql_query($sql,$link);
            $num_rows=mysql_num_rows($result);
                if ($num_rows>0) {
                      echo "Results for Course Number: ".$courseNumber." are: "."<br>";
                      echo "<table>";
                      echo "<tr><th>"."Section Number"."</th><th>"."Classroom"."</th><th>"."Meeting Day"."</th><th>"."Beginning Time"."</th><th>".
                      "Ending Time"."</th><th>"."Number of Student Enrolled"."</th></tr>";

                      while ($row=mysql_fetch_array($result)) {
                        echo "<tr><td>".$row['SNumber']."</td><td>".$row['Classroom']."</td><td>".$row['MeetingDay'].
                        "</td><td>".$row['BeginningTime']."</td><td>".$row['EndingTime']."</td><td>".$row['COUNT(DISTINCT StuID)']."</td></tr>";
                      }
                      echo "</table>";
                }
                else {
                    echo "No Results Found for Course Number: ".$courseNumber."<br>";
                }
          }
        }
        // SECOND IF FORM CODE
        if (!empty($_POST["student_id"]) && isset($_POST["student_id_submit"])) {

          $studentID=test_input($_POST["student_id"]);
          if (!preg_match("/^[0-9]*$/",$studentID)) {
            echo "<font color=red>Only numbers allowed for Student ID</font>";
          }
          else {

            $sql="SELECT CoNumSe, CTitle, Grade
                  FROM ENROLL E, COURSE C
                  WHERE E.StuID=$studentID AND C.CNumber=E.CoNumSe";

            $result=mysql_query($sql,$link);
            $num_rows=mysql_num_rows($result);
                if ($num_rows>0) {
                      echo "Results for Student ID: ".$studentID." are: "."<br>";
                      echo "<table>";
                      echo "<tr><th>"."Course Number"."</th><th>"."Course Title"."</th><th>"."Grade"."</th></tr>";

                      while ($row=mysql_fetch_array($result)) {
                        echo "<tr><td>".$row['CoNumSe']."</td><td>".$row['CTitle']."</td><td>".$row['Grade']."</td></tr>";
                      }
                      echo "</table>";
                }
                else {
                    echo "No Results Found for student CWID: ".$studentID."<br>";
                }
          }

        }
        mysql_close($link);
       ?>
    </div>
</body>
</html>
