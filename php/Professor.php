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
                        <li class="current"><a href="Professor.php">Professor</a></li>
                        <li><a href="Student.php">Student</a></li>
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
                <h1>SSN</h1>
                <form method="POST" action="Professor.php">
                    <input type="text" name="professor_ssn" placeholder="Social Security Number" required>
                    <button type="submit" name="proSsn_submit" class="button_1">Submit</button>
                </form>
            </div>

            <div class="submit">

                <h1>Course and Section Number</h1>
                <form method="POST" action="Professor.php">
                    <div>
                        <label for="course_number">Course Number</label>
                        <input type="text" name="course_number" placeholder="Course Number" required>
                    </div>
                    <div>
                        <label for="section_number">Section Number</label>
                        <input type="text" name="section_number" placeholder="Section Number" required>
                    </div>
                    <button type="submit" name="course_section_submit" class="button_1">submit</button>
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
        $link=mysql_connect($hostName,$userName,$password);
        if (!$link) {
          die('Could not connect: '.mysql_error());
        }
        //echo "Successfully connected to server."."<br>";
        $db_selected=mysql_select_db($dbName,$link);
         if(!$db_selected) {
           die("Unable to select databases $dbName".mysql_error());
         }
        //echo "Successfully connected to database."."<br>";
        function test_input($data){
          $data=trim($data);
          $data=stripslashes($data);
          $data=htmlspecialchars($data);
          return $data;
        }

        if (isset($_POST["proSsn_submit"]) && !empty($_POST["professor_ssn"]))
        {

          $ssn=test_input($_POST["professor_ssn"]);
          if(!preg_match("/^[0-9]*$/",$ssn)){
            echo "<font color=red>Only numbers allowed for SSN</font>";
          }
          else {
            $sql="SELECT 	 CTitle, Classroom, MeetingDay, BeginningTime, EndingTime
                  FROM 	SECTION S, COURSE C, MEETING_DAY M,PROFESSOR P
                  WHERE	 S.CNumberSec=C.CNumber
                        AND C.CNumber=M.CoNoSec
                        AND S.ProTeach=P.PSsn
                        AND P.PSsn=$ssn
                        AND M.SecNo=S.SNumber";
            $result=mysql_query($sql,$link);
            $num_rows=mysql_num_rows($result);
                    if ($num_rows>0) {
                              echo "Results found for SSN: ".$ssn." are: "."<br>";
                              echo "<table>";
                              echo "<tr><th>"."Course Title" ."</th><th>". "Classroom" . "</th><th>"
                              ."Meeting Day". "</th><th>". "Beginning Time". "</th><th>". "EndingTime"."</th></tr>";

                              while($row=mysql_fetch_array($result)) {
                              echo "<tr><td>". $row['CTitle'] ."</td><td>". $row['Classroom'] . "</td><td>"
                              . $row['MeetingDay']. "</td><td>". $row['BeginningTime']. "</td><td>". $row['EndingTime']."</td></tr>";

                              }
                              echo "</table>";
                    }
                    else {
                      echo "No Results Found for SSN: ".$ssn."<br>";
                    }
           }

        }


        if (!empty($_POST["course_number"]) && !empty($_POST["section_number"]) && isset($_POST["course_section_submit"]))
        {

          $courseNumber=test_input($_POST["course_number"]);
          $sectionNumber=test_input($_POST["section_number"]);

          if((!preg_match("/^[0-9]*$/",$courseNumber)) || (!preg_match("/^[0-9]*$/",$sectionNumber)))
          {
            echo "<font color=red>Only numbers allowed for Course Number and Section Number</font>";
          }
          else
          {
            $sql="SELECT 	 COUNT(Grade), Grade
                  FROM 	ENROLL E
                  WHERE	 E.CoNumSe=$courseNumber
                         AND E.SecNum=$sectionNumber
                  GROUP BY Grade";

            $result=mysql_query($sql,$link);
            $num_rows=mysql_num_rows($result);
                    if ($num_rows>0) {
                              echo "Results found for Course Number: ".$courseNumber." and Section Number: ".$sectionNumber." are: "."<br>";
                              echo "<table>";
                              echo "<tr><th>"."Number of Students" ."</th><th>". "Grade Types"."</th></tr>";

                              while($row=mysql_fetch_array($result)) {
                              echo "<tr><td>". $row['COUNT(Grade)'] ."</td><td>". $row['Grade']."</td></tr>";
                              }
                              echo "</table>";
                    }
                    else {
                      echo "No Results Found  Course Number: ".$courseNumber." and Section Number: ".$sectionNumber."<br>";
                    }
          }


        }
        mysql_close($link);
        ?>
    </div>
</body>
</html>
