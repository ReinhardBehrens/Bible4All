<?php include './ConnectionString.php';?>
<?php include './header.php';?>
<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//          $_POST variables
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////           
            if(!isset($_SESSION["SELECTED_BIBLE_VERSION"]) && !isset($_POST["bibleversion"]))
            {
                $_SESSION["SELECTED_BIBLE_VERSION"]= "King James Version";
            }
            else 
            {
                if(isset($_POST["bibleversion"]))
                {
                  $_SESSION["SELECTED_BIBLE_VERSION"]= $_POST["bibleversion"];
                  $bibleversion=$_POST["bibleversion"];
                  if($debug==1){echo "selected bibleversion is => ".$bibleversion;}
                }
            }
 
            if(!isset($_SESSION["SELECTED_BIBLE_BOOK"]) && !isset($_POST["biblebook"]))
            {
                $_SESSION["SELECTED_BIBLE_BOOK"]= "Genesis";
            }   
            else 
            {
                if(isset($_POST["biblebook"]))
                {
                  $_SESSION["SELECTED_BIBLE_BOOK"]= $_POST["biblebook"];
                  $biblebook=$_POST["biblebook"];
                  $_SESSION["SELECTED_BIBLE_CHAPTER"]="1";
                  $_SESSION["SELECTED_BIBLE_VERSE"]="1";
                  if($debug==1){echo "<br/>selected biblebook is => ".$biblebook;}
                }
            }

            if(!isset($_SESSION["SELECTED_BIBLE_CHAPTER"]) && !isset($_POST["biblechapter"]))
            {
                $_SESSION["SELECTED_BIBLE_CHAPTER"]= "1";
            }   
            else 
            {
                if(isset($_POST["biblechapter"]))
                {
                  $_SESSION["SELECTED_BIBLE_CHAPTER"]= $_POST["biblechapter"];
                  $_SESSION["SELECTED_BIBLE_VERSE"]="1";
                  $biblechapter=$_POST["biblechapter"];
                  if($debug==1){ echo "selected biblechapter is => ".$biblechapter; }
                }
            }
//------------------------------------------------------------------------------------------------------------------------------------------------
            // SELECT ID FROM BIBLEBOOK
            $result_bible_bookid = $dbconnection->GetBibleBookIDbyName($_SESSION["SELECTED_BIBLE_BOOK"]);
            
            // Should only be one result, 
            // TODO: This needs cleaning up, code makes no sense.
            $BibleBookID=1;
            while($rowid = sqlsrv_fetch_array($result_bible_bookid, SQLSRV_FETCH_ASSOC))
            {
                $BibleBookID = $rowid["Id"];
                if($debug==1){echo "BibleBookID found ===> " .$BibleBookID."<br/>";}
                break; // Should only be one
            }

//------------------------------------------------------------------------------------------------------------------------------------------------            
            $result_bible_versionid=$dbconnection->GetBibleVersionIDByName($_SESSION["SELECTED_BIBLE_VERSION"]);
            $BibleVersionID=1;
            // Should only be one 
            // TODO: This needs cleaning up, code makes no sense.
            while($rowid = sqlsrv_fetch_array($result_bible_versionid, SQLSRV_FETCH_ASSOC))
            {
                $BibleVersionID = $rowid["Id"];
                if($debug==1){echo "BibleVersionID found ===> " .$BibleVersionID."<br/>";}

            }
?>
    <div class="container">
        <div class="row">
            <div class="col-sm-4">
                <form method="POST" action="">
                    <select class="form-select" name="bibleversion" onchange="this.form.submit()">
                    <?php
                        $result_bible_versions = $dbconnection->GetBibleVersionsByName();
                        while($row = sqlsrv_fetch_array($result_bible_versions, SQLSRV_FETCH_ASSOC)) {
                    ?>
                                <option value="<?php echo $row["Name"]; ?>" <?php if(isset($_SESSION["SELECTED_BIBLE_VERSION"]) && $_SESSION["SELECTED_BIBLE_VERSION"]==$row["Name"]){ echo "selected"; } ?>><?php echo $row["Name"]; ?></option>
                    <?php
                        }
                    ?>
                    </select> 
                </form>
            </div>
            <div class="col-sm-4">
                <form method="POST" action="">
                    <select class="form-select" name="biblebook" onchange="this.form.submit()">
                    <?php
                        $result_bible_books = $dbconnection->GetBibleBooksByName();
                        while($row = sqlsrv_fetch_array($result_bible_books, SQLSRV_FETCH_ASSOC)) {
                    ?>
                                <option value="<?php echo $row["Name"]; ?>" <?php if(isset($_SESSION["SELECTED_BIBLE_BOOK"]) && $_SESSION["SELECTED_BIBLE_BOOK"]==$row["Name"]){ echo "selected"; } ?>><?php echo $row["Name"]; ?></option>
                    <?php
                        }
                    ?>
                    </select> 
                </form>
            </div>
            <div class="col-sm-4">
                <form method="POST" action="">
                    <select class="form-select" name="biblechapter" onchange="this.form.submit()">
                    <?php
                        $result_bible_chapters = $dbconnection->GetChapterNumberByBibleVersionIDandBibleBookId($BibleVersionID, $BibleBookID);
                        while($row = sqlsrv_fetch_array($result_bible_chapters, SQLSRV_FETCH_ASSOC)) {
                    ?>

                        <option value="<?php echo $row["ChapterNumber"]; ?>" <?php if(isset($_SESSION["SELECTED_BIBLE_CHAPTER"]) && $_SESSION["SELECTED_BIBLE_CHAPTER"]==$row["ChapterNumber"]){ echo "selected"; } ?>><?php echo $row["ChapterNumber"]; ?></option>

                    <?php
                        }
                    ?>
                    </select> 
                </form>
            </div>
        </div>
    </div>

    <div class="row">
        <button onclick="addColumn()">Add Translation</button>        
    </div>
    <div class="row">
        <div id="columnContainer"></div>
    </div>
    <script>
        var columnCount = 0; // Keep track of the number of columns

        function addColumn() {
            if(columnCount<4)
            {                
                var selectedValue = $('#bibleversion :selected').text();

                columnCount++;
                var xhr = new XMLHttpRequest();
                xhr.open("GET", "process.php?version=" + encodeURIComponent(selectedValue), true);
                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        var newColumn = document.createElement("div");
                        newColumn.className = "column";
                        newColumn.innerHTML = xhr.responseText;
                        document.getElementById("columnContainer").appendChild(newColumn);
                    }
                };
                xhr.send();
            }
        }
<?php          
        include './footer.php';