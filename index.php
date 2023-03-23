        <?php include './ConnectionString.php';?>
        <?php include './Header.php';?>
        <?php
 
            ////////////////////////////////////////////////////////////////////////////////////////////////////
            //
            //
            //          SOLI DEO GLORIA - JESUS CHRIST ALONE - SCRIPTURE ALONE
            //
            //
            // HANDLE POST REQUESTS && SESSION DATA
            
            ////////////////////////////////////////////////////////////////////////////////////////////////////
            // $_GET POST from Search Page
            ////////////////////////////////////////////////////////////////////////////////////////////////////
            // Get posts from the search page a lookup and set the $_SESSION variables based on search
            if(isset($_GET["VersionId"]))
            {
                if($debug==1){echo "Version ID - ".$_GET["VersionId"]."<br/>";}
                $SELECT_BIBLE_VERSION_FOR_SESSION = "SELECT Name from BibleVersion Where Id=".$_GET["VersionId"];
                
                // Get biblebook               
                if($debug==1){echo "-----SQL QUERY \$SELECT_BIBLE_VERSION_FOR_SESSION>".$SELECT_BIBLE_VERSION_FOR_SESSION."<br/>";}
                $params = array();
                $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
                $result_bible_version = sqlsrv_query($conn, $SELECT_BIBLE_VERSION_FOR_SESSION , $params, $options);

                while($row_version = sqlsrv_fetch_array($result_bible_version, SQLSRV_FETCH_ASSOC)) 
                {
                    $_SESSION["SELECTED_BIBLE_VERSION"] = $row_version["Name"];
                }
                
                if(!isset($_SESSION["SELECTED_BIBLE_VERSION"]))
                {
                    $_SESSION["SELECTED_BIBLE_VERSION"]="King James Version";
                }
            }
            
            if(isset($_GET["Bookid"]))
            {
                if($debug==1){echo "Bookid - ". $_GET["Bookid"]."<br/>";}
                $SELECT_BIBLE_BOOK_FOR_SESSION = "SELECT Name FROM BibleBook where Id=".$_GET["Bookid"];
                if($debug==1){echo "-----SQL QUERY \$SELECT_BIBLE_BOOK_FOR_SESSION>".$SELECT_BIBLE_BOOK_FOR_SESSION."<br/>";}
                $params = array();
                $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
                $result_bible_book = sqlsrv_query($conn, $SELECT_BIBLE_BOOK_FOR_SESSION , $params, $options);

                while($row_book = sqlsrv_fetch_array($result_bible_book, SQLSRV_FETCH_ASSOC)) 
                {
                    $_SESSION["SELECTED_BIBLE_BOOK"] = $row_book["Name"];
                }
                
                if(!isset($_SESSION["SELECTED_BIBLE_BOOK"]))
                {
                    $_SESSION["SELECTED_BIBLE_BOOK"]="Genesis";
                }
            }
            
            if(isset($_GET["ChapterId"]))
            {
                if($debug==1){echo "ChapterId - ". $_GET["ChapterId"]."<br/>";}
                $SELECT_BIBLE_CHAPTER_FOR_SESSION = "SELECT ChapterNumber from BibleChapter WHERE Id=".$_GET["ChapterId"];
                if($debug==1){echo "-----SQL QUERY \$SELECT_BIBLE_CHAPTER_FOR_SESSION>".$SELECT_BIBLE_CHAPTER_FOR_SESSION."<br/>";}
                $params = array();
                $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
                $result_bible_chapter = sqlsrv_query($conn, $SELECT_BIBLE_CHAPTER_FOR_SESSION , $params, $options);

                while($row_chapter = sqlsrv_fetch_array($result_bible_chapter, SQLSRV_FETCH_ASSOC))
                {
                    $_SESSION["SELECTED_BIBLE_CHAPTER"] = $row_chapter["ChapterNumber"];
                }
                
                if(!isset($_SESSION["SELECTED_BIBLE_CHAPTER"]))
                {
                    $_SESSION["SELECTED_BIBLE_CHAPTER"]="1";
                }

            }

            if(isset($_GET["VerseNr"]))
            {
                if($debug==1){echo "VerseNr - ". $_GET["VerseNr"]."<br/>";}
                $SELECT_BIBLE_VERSE_FOR_SESSION = "";
                if($debug==1){echo "-----SQL QUERY \$SELECT_BIBLE_VERSION_FOR_SESSION>".$SELECT_BIBLE_VERSION_FOR_SESSION."<br/>";}
                $params = array();
                $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
                $result_bible_version = sqlsrv_query($conn, $SELECT_BIBLE_VERSION_FOR_SESSION , $params, $options);

                while($row_version = sqlsrv_fetch_array($result_bible_version, SQLSRV_FETCH_ASSOC)) 
                {
                    $_SESSION["SELECTED_BIBLE_VERSE"] = $row_version["Name"];
                }                              
            }
            
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

            if(isset($_POST["biblechapter"]) || isset($_POST["biblebook"]) ||isset($_POST["bibleversion"]))
            {
                if(isset($_GET["ChapterId"]))
                {
                    unset($_GET["ChapterId"]);
                }
                
                if(isset($_GET["Bookid"]))
                {
                    unset($_GET["Bookid"]);
                }
                
                if(isset($_GET["VerseNr"]))
                {
                    unset($_GET["VerseNr"]);
                }       
            }    
            
//            if(!isset($_SESSION["SELECTED_BIBLE_VERSE"]) && !isset($_POST["bibleverse"]))
//            {
//                $_SESSION["SELECTED_BIBLE_VERSE"]= "1";
//            }   
//            else 
//            {
//                if(isset($_POST["bibleverse"]))
//                {
//                  $_SESSION["SELECTED_BIBLE_VERSE"]= $_POST["bibleverse"];
//                  $bibleverse=$_POST["bibleverse"];
//                  if($debug==1){echo "selected bibleverse is => ".$bibleverse;}
//                }
//            }

//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>        
            //SELECT NAME FROM BIBLEVERSION
            $sqlquery_bible_version = "SELECT Name FROM BibleVersion";
            //print "SQL: $sql\n";
            $result_bible_version = sqlsrv_query($conn, $sqlquery_bible_version);
            if($result_bible_version === false) {
                die(print_r(sqlsrv_errors(), true));
            }
            
            // SELECT NAME FROM BIBLEBOOK
            $sqlquery_bible_books = "SELECT Name FROM BibleBook";
            $result_bible_books = sqlsrv_query($conn, $sqlquery_bible_books);
            if($result_bible_books === false) {
                die(print_r(sqlsrv_errors(), true));                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      die(print_r(sqlsrv_errors(), true));
            }
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

            
            
//------------------------------------------------------------------------------------------------------------------------------------------------
            // SELECT ID FROM BIBLEBOOK
            $sqlquery_bible_bookid = "SELECT Id FROM BibleBook WHERE dbo.BibleBook.Name='". $_SESSION["SELECTED_BIBLE_BOOK"]."'";
            if($debug==1){echo "<br/>".$sqlquery_bible_bookid . "<br/>";}
            $result_bible_bookid = sqlsrv_query($conn, $sqlquery_bible_bookid);
            if($result_bible_bookid === false) {
                die(print_r(sqlsrv_errors(), true));                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      die(print_r(sqlsrv_errors(), true));
            }
            else 
            {
                
            }
            if($debug===1){echo "Entering while loop<br/>";}
            // Should only be one
            $BibleBookID=1;
            while($rowid = sqlsrv_fetch_array($result_bible_bookid, SQLSRV_FETCH_ASSOC))
            {
                $BibleBookID = $rowid["Id"];
                if($debug==1){echo "BibleBookID found ===> " .$BibleBookID."<br/>";}
                break; // Should only be one
            }
//------------------------------------------------------------------------------------------------------------------------------------------------

//------------------------------------------------------------------------------------------------------------------------------------------------            
            // GET BibleVerionId based on name
            $sqlquery_bible_versionid = "SELECT Id FROM BibleVersion WHERE dbo.BibleVersion.Name='".$_SESSION["SELECTED_BIBLE_VERSION"]."'";
            if($debug==1){echo $sqlquery_bible_versionid . "<br/>";}
            $result_bible_versionid = sqlsrv_query($conn, $sqlquery_bible_versionid);
            if($result_bible_versionid === false) {
                die(print_r(sqlsrv_errors(), true));                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      die(print_r(sqlsrv_errors(), true));
            }
            
            $BibleVersionID=1;
            // Should only be one
            while($rowid = sqlsrv_fetch_array($result_bible_versionid, SQLSRV_FETCH_ASSOC))
            {
                $BibleVersionID = $rowid["Id"];
                if($debug==1){echo "BibleVersionID found ===> " .$BibleVersionID."<br/>";}

            }
//------------------------------------------------------------------------------------------------------------------------------------------------
            $sqlquery_bible_chapters = "SELECT ChapterNumber FROM BibleChapter WHERE BibleVersionId=".$BibleVersionID." AND BibleBookId=".$BibleBookID." ORDER BY ChapterNumber ASC";
            if($debug==1){echo $sqlquery_bible_chapters . "<br/>";}
            $result_bible_chapters = sqlsrv_query($conn, $sqlquery_bible_chapters);
            if($result_bible_chapters === false) {
                die(print_r(sqlsrv_errors(), true));                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      die(print_r(sqlsrv_errors(), true));
            }   


            
//------------------------------------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------------------------------------            
            // GET BibleVerionId based on name
            $sqlquery_bible_chapterid = "SELECT Id FROM BibleChapter WHERE dbo.BibleChapter.ChapterNumber=".$_SESSION["SELECTED_BIBLE_CHAPTER"]." AND BibleBookId=".$BibleBookID." AND BibleVersionId=".$BibleVersionID;
            if($debug==1){echo $sqlquery_bible_chapterid . "<br/>";}
            $result_bible_chapterid = sqlsrv_query($conn, $sqlquery_bible_chapterid);
            if($result_bible_chapterid === false) {
                die(print_r(sqlsrv_errors(), true));                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      die(print_r(sqlsrv_errors(), true));
            }

            $BibleChapterID=1;
            // Should only be one
            while($rowid = sqlsrv_fetch_array($result_bible_chapterid, SQLSRV_FETCH_ASSOC))
            {
                $BibleChapterID = $rowid["Id"];
                if($debug==1){echo "BibleChapterID found ===> " .$BibleChapterID."<br/>";}
            }
//------------------------------------------------------------------------------------------------------------------------------------------------
            $sqlquery_bible_verses = "SELECT VerseNr,VerseContent FROM BibleVerses WHERE BibleChapterId=".$BibleChapterID." AND BibleVersionId=".$BibleVersionID." ORDER BY VerseNr ASC";
            if($debug==1){echo $sqlquery_bible_verses . "<br/>";}
            $result_bible_verses = sqlsrv_query($conn, $sqlquery_bible_verses);
            if($result_bible_verses === false) {
                die(print_r(sqlsrv_errors(), true));                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      die(print_r(sqlsrv_errors(), true));
            }
            
            if(isset($_GET["VerseNr"]))
            {
                $sqlquery_bible_verses_content = "SELECT VerseNr,VerseContent FROM BibleVerses WHERE BibleChapterId=".$BibleChapterID." AND BibleVersionId=".$BibleVersionID." AND VerseNr=".$_GET["VerseNr"];
            }
            else {
                $sqlquery_bible_verses_content = "SELECT VerseNr,VerseContent FROM BibleVerses WHERE BibleChapterId=".$BibleChapterID." AND BibleVersionId=".$BibleVersionID."";
            }
            if($debug==1){echo $sqlquery_bible_verses_content . "<br/>";}
            $result_bible_verses_content = sqlsrv_query($conn, $sqlquery_bible_verses_content);
            if($result_bible_verses_content === false) {
                die(print_r(sqlsrv_errors(), true));                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      die(print_r(sqlsrv_errors(), true));
            }
        ?>

<div class="container">
    
    <div class="row">
        <div class="col-sm-4">
            <form method="POST" action="">
                <select class="form-select" name="bibleversion" onchange="this.form.submit()">
                <?php
                            while($row = sqlsrv_fetch_array($result_bible_version, SQLSRV_FETCH_ASSOC)) {
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

<style>
    .doublecolumnview {
        column-count: 2;
        border: 0px solid #00ff21;
        padding: 15px;
        overflow-y: auto;
        height: 100%;
        font-family: Arial;
        text-align: justify;
    }
</style>
<div class="container">
    <div class="row">
        <div class="col-auto">
            <div class="doublecolumnview">
            <?php                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        
                while($row = sqlsrv_fetch_array($result_bible_verses_content, SQLSRV_FETCH_ASSOC)) {
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
        include './Footer.php';

