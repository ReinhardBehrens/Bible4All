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
            
            if(!isset($_SESSION["SELECTED_BIBLE_VERSION_2"]) && !isset($_POST["bibleversion2"]))
            {
                $_SESSION["SELECTED_BIBLE_VERSION_2"]= "King James Version";
            }
            else 
            {
                if(isset($_POST["bibleversion_2"]))
                {
                  $_SESSION["SELECTED_BIBLE_VERSION_2"]= $_POST["bibleversion_2"];
                  $bibleversion=$_POST["bibleversion_2"];
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
            
            if(!isset($_SESSION["SELECTED_BIBLE_BOOK_2"]) && !isset($_POST["biblebook_2"]))
            {
                $_SESSION["SELECTED_BIBLE_BOOK_2"]= "Genesis";
            }   
            else 
            {
                if(isset($_POST["biblebook_2"]))
                {
                  $_SESSION["SELECTED_BIBLE_BOOK_2"]= $_POST["biblebook_2"];
                  $biblebook=$_POST["biblebook_2"];
                  $_SESSION["SELECTED_BIBLE_CHAPTER_2"]="1";
                  $_SESSION["SELECTED_BIBLE_VERSE_2"]="1";
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
            
            if(!isset($_SESSION["SELECTED_BIBLE_CHAPTER_2"]) && !isset($_POST["biblechapter_2"]))
            {
                $_SESSION["SELECTED_BIBLE_CHAPTER_2"]= "1";
            }   
            else 
            {
                if(isset($_POST["biblechapter_2"]))
                {
                  $_SESSION["SELECTED_BIBLE_CHAPTER_2"]= $_POST["biblechapter_2"];
                  $_SESSION["SELECTED_BIBLE_VERSE_2"]="1";
                  $biblechapter_2=$_POST["biblechapter_2"];
                  if($debug==1){ echo "selected biblechapter_2 is => ".$biblechapter_2; }
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
            
            // SELECT ID FROM BIBLEBOOK
            $result_bible_bookid_2 = $dbconnection->GetBibleBookIDbyName($_SESSION["SELECTED_BIBLE_BOOK_2"]);
            
            // Should only be one result, 
            // TODO: This needs cleaning up, code makes no sense.
            $BibleBookID_2=1;
            while($rowid = sqlsrv_fetch_array($result_bible_bookid_2, SQLSRV_FETCH_ASSOC))
            {
                $BibleBookID_2 = $rowid["Id"];
                if($debug==1){echo "BibleBookID_2 found ===> " .$BibleBookID_2."<br/>";}
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
            
            $result_bible_versionid_2=$dbconnection->GetBibleVersionIDByName($_SESSION["SELECTED_BIBLE_VERSION_2"]);
            $BibleVersionID_2=1;
            // Should only be one 
            // TODO: This needs cleaning up, code makes no sense.
            while($rowid = sqlsrv_fetch_array($result_bible_versionid_2, SQLSRV_FETCH_ASSOC))
            {
                $BibleVersionID_2 = $rowid["Id"];
                if($debug==1){echo "BibleVersionID found ===> " .$BibleVersionID_2."<br/>";}

            }
//------------------------------------------------------------------------------------------------------------------------------------------------            
            $result_bible_chapterid = $dbconnection->GetChapterIdByChapterNameandBiblebookIDandBibleVersionID($_SESSION["SELECTED_BIBLE_CHAPTER"],$BibleBookID, $BibleVersionID);
            $BibleChapterID=1;
            // Should only be one
            while($rowid = sqlsrv_fetch_array($result_bible_chapterid, SQLSRV_FETCH_ASSOC))
            {
                $BibleChapterID = $rowid["Id"];
                if($debug==1){echo "BibleChapterID found ===> " .$BibleChapterID."<br/>";}
            }   
            
            $result_bible_chapterid_2 = $dbconnection->GetChapterIdByChapterNameandBiblebookIDandBibleVersionID($_SESSION["SELECTED_BIBLE_CHAPTER_2"],$BibleBookID_2, $BibleVersionID_2);
            $BibleChapterID_2=1;
            // Should only be one
            while($rowid = sqlsrv_fetch_array($result_bible_chapterid_2, SQLSRV_FETCH_ASSOC))
            {
                $BibleChapterID_2 = $rowid["Id"];
                if($debug==1){echo "BibleChapterID_2 found ===> " .$BibleChapterID_2."<br/>";}
            } 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$result_bible_verses_content = $dbconnection->GetVerseNrandVerseContentBYBibleChapterIDandBibleVersionID($BibleChapterID, $BibleVersionID);

$result_bible_verses_content_2 = $dbconnection->GetVerseNrandVerseContentBYBibleChapterIDandBibleVersionID($BibleChapterID_2, $BibleVersionID_2);
            
?>
    <div class="container">
        <div class="row">
            <div class="col-sm-6">
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
                </div>
                <div class="row">
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
                </div>
                <div class="row">
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
             <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-4">
                         <form method="POST" action="">
                             <select class="form-select" name="bibleversion_2" onchange="this.form.submit()">
                             <?php
                                 $result_bible_versions_2 = $dbconnection->GetBibleVersionsByName();
                                 while($row = sqlsrv_fetch_array($result_bible_versions_2, SQLSRV_FETCH_ASSOC)) {
                             ?>
                                         <option value="<?php echo $row["Name"]; ?>" <?php if(isset($_SESSION["SELECTED_BIBLE_VERSION_2"]) && $_SESSION["SELECTED_BIBLE_VERSION_2"]==$row["Name"]){ echo "selected"; } ?>><?php echo $row["Name"]; ?></option>
                             <?php
                                 }
                             ?>
                             </select> 
                         </form>
                     </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                       <form method="POST" action="">
                           <select class="form-select" name="biblebook_2" onchange="this.form.submit()">
                           <?php
                               $result_bible_books = $dbconnection->GetBibleBooksByName();
                               while($row = sqlsrv_fetch_array($result_bible_books, SQLSRV_FETCH_ASSOC)) {
                           ?>
                                       <option value="<?php echo $row["Name"]; ?>" <?php if(isset($_SESSION["SELECTED_BIBLE_BOOK_2"]) && $_SESSION["SELECTED_BIBLE_BOOK_2"]==$row["Name"]){ echo "selected"; } ?>><?php echo $row["Name"]; ?></option>
                           <?php
                               }
                           ?>
                           </select> 
                       </form>
                   </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <form method="POST" action="">
                            <select class="form-select" name="biblechapter_2" onchange="this.form.submit()">
                            <?php
                                $result_bible_chapters = $dbconnection->GetChapterNumberByBibleVersionIDandBibleBookId($BibleVersionID_2, $BibleBookID_2);
                                while($row = sqlsrv_fetch_array($result_bible_chapters, SQLSRV_FETCH_ASSOC)) {
                            ?>

                                <option value="<?php echo $row["ChapterNumber"]; ?>" <?php if(isset($_SESSION["SELECTED_BIBLE_CHAPTER_2"]) && $_SESSION["SELECTED_BIBLE_CHAPTER_2"]==$row["ChapterNumber"]){ echo "selected"; } ?>><?php echo $row["ChapterNumber"]; ?></option>

                            <?php
                                }
                            ?>
                            </select> 
                        </form>
                    </div>
                    
                </div>           
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div>
                <?php                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        
                    while($row = sqlsrv_fetch_array($result_bible_verses_content, SQLSRV_FETCH_ASSOC)) {
                ?>
                    <sup><?php echo $row["VerseNr"] ?></sup><?php echo $row["VerseContent"] ?>
                <?php
                    }
                 ?>
                </div>
            </div>
            <div class="col-sm-6">
                <div>
                <?php                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        
                    while($row = sqlsrv_fetch_array($result_bible_verses_content_2, SQLSRV_FETCH_ASSOC)) {
                ?>
                    <sup><?php echo $row["VerseNr"] ?></sup><?php echo $row["VerseContent"] ?>
                <?php
                    }
                 ?>
                </div>
            </div>            
        </div>        
    </div>
        
<?php          
        include './footer.php';