<?php include './ConnectionString.php';?>
<?php include './Header.php';?>
<?php
//Ref : https://stackoverflow.com/questions/12369709/php-string-words-to-array
                if(isset($_POST["searchfield"]))
                {
                    echo "<h1>Search Results:<br/></h1>";
                    // Get book names, if books are in the bible then do search of Bible Verses in Bible Create link to Bible Reading full chapter
                    if($debug==1){echo '$_POST["searchfield"] ISSET<br/>';}
                    if($debug==1){echo 'Starting search, by extracting all Bible books from the keywords<br/>';}

                    $_SESSION["SEARCH_KEYWORDS"]= $_POST["searchfield"];
                    $biblebook_array = preg_split("/[;]/", $_POST["searchfield"]);
                    $searchkeywords = array();
                    $bible_book = "";

                    if( $debug==1){     echo "SIZE OF SEARCHFIELD ARRAY: ".sizeof($biblebook_array);    }
                    if(sizeof($biblebook_array)==1)
                    {
                        // No biblebook given
                        $searchkeywords=preg_split("/\s+/", $_POST["searchfield"]);
                        if($debug==1){echo "<br/>-----FOUND [".sizeof($searchkeywords)."] KEYWORDS-----<br/>";}
                    }
                    else if(sizeof($biblebook_array)==2)
                    {
                        // Verse + Book
                        $searchkeywords=preg_split("/\s+/", $biblebook_array[0]);
                        $bible_book = trim($biblebook_array[1]);
                        if($debug==1){echo "<br/>-----FOUND [".sizeof($searchkeywords)."] KEYWORDS-----<br/>"; }
                        if($debug==1){echo "<br/>-----FOUND BIBLEBOOK IN SEARCH FIELD [".$bible_book."]-----<br/>"; }
                    }

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//                  Getting ChatperID for Verses based on BibleBook Entered
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////                    
                        // Entered BiblebookId
                        $biblebookid = -1;
                        $biblechaptersbyid = array();
                        if($bible_book != null && $bible_book != "")
                        {
                            $result_bible_biblebookid_details = $dbconnection->GetBibleBookIDbyName($bible_book);

                            while($row_bookdetailsid = sqlsrv_fetch_array($result_bible_biblebookid_details, SQLSRV_FETCH_ASSOC)) 
                            {
                                $biblebookid = $row_bookdetailsid["Id"];
                            }

                            $result_bible_chapters_byid = $dbconnection->GetChapterIDbyBibleBookId($biblebookid);
                            
                            while($row_chapters_list = sqlsrv_fetch_array($result_bible_chapters_byid, SQLSRV_FETCH_ASSOC)) 
                            {
                                $biblechaptersbyid[] = $row_chapters_list["Id"];
                            }                      
                        }

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Full Text Search using Contains   
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////                        
                    $amount_of_keywords = sizeof($searchkeywords);


                    $result_bible_verses_ft = $dbconnection-> GetAllFromBibleVersesWHERECONTAINS(implode(" " ,$searchkeywords));
                    
                    while($row = sqlsrv_fetch_array($result_bible_verses_ft, SQLSRV_FETCH_ASSOC))
                    {
                        try 
                        {
                            $result_bible_version_byid = $dbconnection->GetAllFromBibleVersionBYID($row["BibleVersionId"]);
                            $bible_version_for_verse = "";
                            while($row_versionid = sqlsrv_fetch_array($result_bible_version_byid, SQLSRV_FETCH_ASSOC))  {
                                $bible_version_for_verse = $row_versionid["Version"];
                            }
                            
                            $result_bible_chapter_getid = $dbconnection->GetALlFromBibleChapterBYID($row["BibleChapterId"]);
                            $row_count_bible_chapter = sqlsrv_num_rows($result_bible_chapter_getid);

                            if($row_count_bible_chapter=1)
                            {
                                while($row_chapterid = sqlsrv_fetch_array($result_bible_chapter_getid, SQLSRV_FETCH_ASSOC))  {
                                    $result_bible_biblebook_details = $dbconnection->GetAllFromBibleBookById($row_chapterid["BibleBookId"]);
                                    $row_count_bookinfo = sqlsrv_num_rows($result_bible_biblebook_details);
                                    while($row_bookdetails = sqlsrv_fetch_array($result_bible_biblebook_details, SQLSRV_FETCH_ASSOC)) 
                                    {                                        
                                      if(strlen($row["VerseContent"])<=10)
                                      {
                                         echo "<div><a href=\"./index.php?VersionId=".$row["BibleVersionId"]."&ChapterId=".$row["BibleChapterId"]."&Bookid=".$row_chapterid["BibleBookId"]."&VerseNr=".$row["VerseNr"]."\" >".$row_bookdetails["Name"]." ".$row_chapterid["ChapterNumber"]." : ".$row["VerseNr"]." ". substr($row["VerseContent"],0,10) ."... (".$bible_version_for_verse.")</a>"; 
                                      }
                                      else 
                                      {
                                         echo "<div><a href=\"./index.php?VersionId=".$row["BibleVersionId"]."&ChapterId=".$row["BibleChapterId"]."&Bookid=".$row_chapterid["BibleBookId"]."&VerseNr=".$row["VerseNr"]."\" >".$row_bookdetails["Name"]." ".$row_chapterid["ChapterNumber"]." : ".$row["VerseNr"]." ". substr($row["VerseContent"],0,25) ."... (".$bible_version_for_verse.")</a>";   
                                      }
                                    }
                                }
                            }
                        }
                        catch(Exception $e)
                        {
                            echo "Error occured: " + $e;
                        }
                        //$array_of_bible_verse fix in display the \s\. 
                        
                        $array_of_bible_verse = preg_split("/\s+/", $row["VerseContent"]);

                        // Replacing $keyword
                        $tmp=$searchkeywords;

                        // Replacing $keyword
                        if($debug==1){ echo "<br/>-----KEYWORDS". implode(" ", $tmp)."-----<br/>";}
                        if($debug==1){ echo sizeof($array_of_bible_verse)." Size Array....<br/>";}

                        $outputverse = array();
                        foreach($array_of_bible_verse as $verse_by_word)
                        {                       
                            $tmpArray[] = array();
                            // Check if any of the keywords match the word in the verse
                            // Iterate through each keyword and check if its in the specific verse (has to be this way around)
                            $found_keyword=false;
                            foreach ($searchkeywords as $keyword)
                            {
                                // Is the keyword in the
                                unset($tmpArray);
                                $tmpArray[] = $verse_by_word;
                                if($debug==1){ echo "----------------------------------------------------------------------<br/>";}
                                if($debug==1){ echo "Verse that is matched for criteria : ".$verse_by_word."<br/>";}
                                if($debug==1){ echo "Keyword to be used in search ".$keyword."<br/>";}
                                $regex_string = "/".$keyword."/i";
                                if($debug==1){ echo "Temp array size [".sizeof($tmpArray)."]<br/>";}
                                $regex_string = "/".$keyword."/i";
                                if($debug==1){ echo "Regular Expression used in search ".$regex_string."<br/>";}
                                $search_result_by_keyword  = preg_grep($regex_string, $tmpArray);
                                if($debug==1){ echo "Regex results found ".sizeof($search_result_by_keyword)."<br/>";}
                                if(sizeof($search_result_by_keyword) > 0)
                                {

                                    $found_keyword = true;
                                    break;
                                }

                                if($debug==1){ echo "----------------------------------------------------------------------<br/>";}
                            }

                            if($found_keyword)
                            {
                                if($debug==1){ echo "KEYWORD MATCHES CRITERIA<br/>";}
                                $outputverse[] = " <b>".preg_replace('/\s\./', '.', $verse_by_word)."</b> "; 
                            }
                            else
                            {
                                $outputverse[] = " ".preg_replace('/\s\./', '.', $verse_by_word)." "; 
                            }
                        }   

                        // Build HTML string
                        echo "<p><sup>".$row["VerseNr"]."</sup>". implode("", $outputverse)."</div>";
                        unset($outputverse);
                    }
  
                    
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Search containing AND condition   
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////                      
                    // Search bible verses without the limitation of books
                    // AND Search then OR Search
                    // Build Select Queries
                    
                    if($biblebookid > 0 )
                    {
                        $SELECT_SEARCH_QUERY_AND_OPERATION="SELECT * FROM BibleVerses WHERE BibleChapterId IN(". implode(",", $biblechaptersbyid).") AND ";
                        if($debug==1){echo "<br/>Basic with BibleChapterId $SELECT_SEARCH_QUERY_AND_OPERATION++++++++++++++++++++++++++++++>>>>>>>>>>>>> ".$SELECT_SEARCH_QUERY_AND_OPERATION;}
                    }
                    else
                    {
                        $SELECT_SEARCH_QUERY_AND_OPERATION="SELECT * FROM BibleVerses WHERE ";
                        if($debug==1){echo "<br/>Basic $SELECT_SEARCH_QUERY_AND_OPERATION++++++++++++++++++++++++++++++>>>>>>>>>>>>> ".$SELECT_SEARCH_QUERY_AND_OPERATION;}
                    }

                    $SELECT_SEARCH_QUERY_OR_OPERATION="";
                    $current_count=0;

                    foreach($searchkeywords as $value)
                    {
                        $select_bible_book_name = "";
                        if($debug==1){echo "<br/>-----Building query with following keyword: ". $value."-----<br/>";}
                        if($current_count == 0)
                        {
                            if($debug==1){echo "<br/>================={BEFORE CONCATENATION}".$SELECT_SEARCH_QUERY_AND_OPERATION."<br/>";}
                            if($debug==1){echo "<br/>=================------\$current_count==0 so building first part of the select query without the AND-----";}
                            $SELECT_SEARCH_QUERY_AND_OPERATION = $SELECT_SEARCH_QUERY_AND_OPERATION." VerseContent LIKE '%".$value."%'";
                            if($debug==1){echo "<br/>================={AFTER CONCATENATION}".$SELECT_SEARCH_QUERY_AND_OPERATION."<br/>";}
                        }
                        else
                        {
                            if($debug==1){echo "<br/>================={BEFORE CONCATENATION}".$SELECT_SEARCH_QUERY_AND_OPERATION."<br/>";}
                            if($debug==1){echo "<br/>=================-----\$current_count>0 so building rest of the select query with AND-----<br/>";}                            
                            $SELECT_SEARCH_QUERY_AND_OPERATION = $SELECT_SEARCH_QUERY_AND_OPERATION." AND VerseContent LIKE '%".$value."%'" ;
                            if($debug==1){echo "<br/>================={AFTER CONCATENATION}".$SELECT_SEARCH_QUERY_AND_OPERATION."<br/>";}
                        }

                        $current_count++;
                    }
                    $SELECT_SEARCH_QUERY_AND_OPERATION = $SELECT_SEARCH_QUERY_AND_OPERATION." ";
                    if($debug==1){echo "\$SELECT_SEARCH_QUERY_OR_OPERATION : <br/>".$SELECT_SEARCH_QUERY_AND_OPERATION."<br/>"; } 

                    $params = array();
                    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
                    $result_bible_verses = sqlsrv_query( $conn, $SELECT_SEARCH_QUERY_AND_OPERATION , $params, $options );
                    if($result_bible_verses != false)
                    {
                        $row_count = sqlsrv_num_rows($result_bible_verses);
                        
                        if($row_count <=0)
                        {
                            echo "No results found.";
                        }
                    }
                    $outputverse = array();
                    
                    if($debug==1){ echo "-----KEYWORDS IN STACK - ". implode(" ",$searchkeywords)."-----<br/>" ; }
                    
                    while($row = sqlsrv_fetch_array($result_bible_verses, SQLSRV_FETCH_ASSOC))
                    {
                        try 
                        {
                            $result_bible_version_byid = $dbconnection->GetAllFromBibleVersionBYID($row["BibleVersionId"]);
                            
                            $bible_version_for_verse = "";
                            while($row_versionid = sqlsrv_fetch_array($result_bible_version_byid, SQLSRV_FETCH_ASSOC))  {
                                $bible_version_for_verse = $row_versionid["Version"];
                            }

                            $result_bible_chapter_getid = $dbconnection->GetALlFromBibleChapterBYID($row["BibleChapterId"]);
                            $row_count_bible_chapter = sqlsrv_num_rows($result_bible_chapter_getid);

                            if($row_count_bible_chapter=1)
                            {
                                while($row_chapterid = sqlsrv_fetch_array($result_bible_chapter_getid, SQLSRV_FETCH_ASSOC))  {
                                    $result_bible_biblebook_details = $dbconnection->GetAllFromBibleBookById($row_chapterid["BibleBookId"]);
                                    $row_count_bookinfo = sqlsrv_num_rows($result_bible_biblebook_details);
                                    while($row_bookdetails = sqlsrv_fetch_array($result_bible_biblebook_details, SQLSRV_FETCH_ASSOC)) 
                                    {                                        
                                      if(strlen($row["VerseContent"])<=10)
                                      {
                                         echo "<div><a href=\"./index.php?VersionId=".$row["BibleVersionId"]."&ChapterId=".$row["BibleChapterId"]."&Bookid=".$row_chapterid["BibleBookId"]."&VerseNr=".$row["VerseNr"]."\" >".$row_bookdetails["Name"]." ".$row_chapterid["ChapterNumber"]." : ".$row["VerseNr"]." ". substr($row["VerseContent"],0,10) ."... (".$bible_version_for_verse.")</a>"; 
                                      }
                                      else 
                                      {
                                         echo "<div><a href=\"./index.php?VersionId=".$row["BibleVersionId"]."&ChapterId=".$row["BibleChapterId"]."&Bookid=".$row_chapterid["BibleBookId"]."&VerseNr=".$row["VerseNr"]."\" >".$row_bookdetails["Name"]." ".$row_chapterid["ChapterNumber"]." : ".$row["VerseNr"]." ". substr($row["VerseContent"],0,25) ."... (".$bible_version_for_verse.")</a>";   
                                      }
                                    }
                                }
                            }
                        }
                        catch(Exception $e)
                        {
                            echo "Error occured: " + $e;
                        }
                        //$array_of_bible_verse fix in display the \s\. 
                        $array_of_bible_verse = preg_split("/\s+/", $row["VerseContent"]);

                        // Replacing $keyword
                        $tmp=$searchkeywords;

                        // Replacing $keyword
                        if($debug==1){ echo "<br/>-----KEYWORDS". implode(" ", $tmp)."-----<br/>";}
                        if($debug==1){ echo sizeof($array_of_bible_verse)." Size Array....<br/>";}

                        $outputverse = array();
                        foreach($array_of_bible_verse as $verse_by_word)
                        {                       
                            $tmpArray[] = array();
                            // Check if any of the keywords match the word in the verse
                            // Iterate through each keyword and check if its in the specific verse (has to be this way around)
                            $found_keyword=false;
                            foreach ($searchkeywords as $keyword)
                            {
                                // Is the keyword in the
                                unset($tmpArray);
                                $tmpArray[] = $verse_by_word;
                                if($debug==1){ echo "----------------------------------------------------------------------<br/>";}
                                if($debug==1){ echo "Verse that is matched for criteria : ".$verse_by_word."<br/>";}
                                if($debug==1){ echo "Keyword to be used in search ".$keyword."<br/>";}
                                $regex_string = "/".$keyword."/i";
                                if($debug==1){ echo "Temp array size [".sizeof($tmpArray)."]<br/>";}
                                $regex_string = "/".$keyword."/i";
                                if($debug==1){ echo "Regular Expression used in search ".$regex_string."<br/>";}
                                $search_result_by_keyword  = preg_grep($regex_string, $tmpArray);
                                if($debug==1){ echo "Regex results found ".sizeof($search_result_by_keyword)."<br/>";}
                                if(sizeof($search_result_by_keyword) > 0)
                                {

                                    $found_keyword = true;
                                    break;
                                }

                                if($debug==1){ echo "----------------------------------------------------------------------<br/>";}
                            }

                            if($found_keyword)
                            {
                                if($debug==1){ echo "KEYWORD MATCHES CRITERIA<br/>";}
                                $outputverse[] = " <b>".preg_replace('/\s\./', '.', $verse_by_word)."</b> "; 
                            }
                            else
                            {
                                $outputverse[] = " ".preg_replace('/\s\./', '.', $verse_by_word)." "; 
                            }
                        }  

                        // Build HTML string
                        echo "<p><sup>".$row["VerseNr"]."</sup>". implode("", $outputverse)."</div>";
                        unset($outputverse);
                    }                  
                }
      
        include './Footer.php';
