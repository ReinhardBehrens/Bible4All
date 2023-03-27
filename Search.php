<?php include './ConnectionString.php';?>
<?php include './Header.php';?>
<?php
//Ref : https://stackoverflow.com/questions/12369709/php-string-words-to-array
                if(isset($_POST["searchfield"]))
                {
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
                            // Get biblebook
                            $SELECT_GET_BIBLE_BOOKID = "SELECT Id FROM BibleBook WHERE Name='".$bible_book."'";
                            if($debug==1){echo "-----SQL QUERY \$SELECT_GET_BIBLE_BOOKID>".$SELECT_GET_BIBLE_BOOKID."<br/>";}
                            $params = array();
                            $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
                            $result_bible_biblebookid_details = sqlsrv_query($conn, $SELECT_GET_BIBLE_BOOKID , $params, $options);

                            while($row_bookdetailsid = sqlsrv_fetch_array($result_bible_biblebookid_details, SQLSRV_FETCH_ASSOC)) 
                            {
                                $biblebookid = $row_bookdetailsid["Id"];
                            }

                            $SELECT_GET_BIBLE_CHAPTERSLIST_FOR_BOOKID = "SELECT Id FROM BibleChapter WHERE BibleBookId='".$biblebookid."'";
                            if($debug==1){echo "-----SQL QUERY>".$SELECT_GET_BIBLE_CHAPTERSLIST_FOR_BOOKID."<br/>";}
                            $params = array();
                            $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
                            $result_bible_chapters_byid = sqlsrv_query($conn, $SELECT_GET_BIBLE_CHAPTERSLIST_FOR_BOOKID , $params, $options);                            
                            while($row_chapters_list = sqlsrv_fetch_array($result_bible_chapters_byid, SQLSRV_FETCH_ASSOC)) 
                            {
                                $biblechaptersbyid[] = $row_chapters_list["Id"];
                            }                      
                        }

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////                        
// Three queries to get total row count
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////                        
$page_maxumum_results = 35;
$row_total_number_rows = 0;

////////******************************************************************************************************************************//////
    $FULLTEXT_SEARCH_WITH_CONSTRAINED_SEARCH_FIRST = "SELECT * FROM BibleVerses WHERE CONTAINS(VerseContent, '\"". implode(" " ,$searchkeywords)."\"')";
    if($debug==1){ echo "\$FULLTEXT_SEARCH_WITH_CONSTRAINED_SEARCH_FIRST : ".$FULLTEXT_SEARCH_WITH_CONSTRAINED_SEARCH_FIRST; }
    $params = array();
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
    $result_bible_verses_ft = sqlsrv_query( $conn, $FULLTEXT_SEARCH_WITH_CONSTRAINED_SEARCH_FIRST , $params, $options );
    $row_count_full_text_search_query = sqlsrv_num_rows($result_bible_verses_ft);
    $row_total_number_rows = $row_total_number_rows + $row_count_full_text_search_query;
    if($debug==1){ echo "\$row_total_number_rows [0]: $row_total_number_rows";}
////////******************************************************************************************************************************//////
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
    $row_count_search_query_AND_search= sqlsrv_num_rows($result_bible_verses);
    $row_total_number_rows = $row_total_number_rows + $row_count_search_query_AND_search;
    if($debug==1){ echo "\$row_total_number_rows [1]: $row_total_number_rows";}
////////******************************************************************************************************************************//////
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
            $SELECT_SEARCH_QUERY_AND_OPERATION = $SELECT_SEARCH_QUERY_AND_OPERATION." OR VerseContent LIKE '%".$value."%'" ;
            if($debug==1){echo "<br/>================={AFTER CONCATENATION}".$SELECT_SEARCH_QUERY_AND_OPERATION."<br/>";}
        }

        $current_count++;
    }
    $SELECT_SEARCH_QUERY_AND_OPERATION = $SELECT_SEARCH_QUERY_AND_OPERATION." ";
    if($debug==1){echo "\$SELECT_SEARCH_QUERY_OR_OPERATION : <br/>".$SELECT_SEARCH_QUERY_AND_OPERATION."<br/>"; } 

    $params = array();
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
    $result_bible_verses = sqlsrv_query( $conn, $SELECT_SEARCH_QUERY_AND_OPERATION , $params, $options );  
    $row_count_search_query_OR_search= sqlsrv_num_rows($result_bible_verses);
    $row_total_number_rows = $row_total_number_rows + $row_count_search_query_OR_search;
    if($debug==1){ echo "\$row_total_number_rows [2]: $row_total_number_rows";}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Full Text Search using Contains   
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////                        
                    $amount_of_keywords = sizeof($searchkeywords);

                    // DO FULL TEXT SEARCH TO NARROW EXPLICID SEARCHES
                    // Get all the constrained narrowed down bible verses
                    $FULLTEXT_SEARCH_WITH_CONSTRAINED_SEARCH_FIRST = "SELECT * FROM BibleVerses WHERE CONTAINS(VerseContent, '\"". implode(" " ,$searchkeywords)."\"')";
                    if($debug==1){ echo "\$FULLTEXT_SEARCH_WITH_CONSTRAINED_SEARCH_FIRST : ".$FULLTEXT_SEARCH_WITH_CONSTRAINED_SEARCH_FIRST; }
                    $params = array();
                    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
                    $result_bible_verses_ft = sqlsrv_query( $conn, $FULLTEXT_SEARCH_WITH_CONSTRAINED_SEARCH_FIRST , $params, $options );
                    
                    while($row = sqlsrv_fetch_array($result_bible_verses_ft, SQLSRV_FETCH_ASSOC))
                    {
                        try 
                        {
                            $SELECT_BIBLE_VERSION = "SELECT * FROM BibleVersion WHERE Id=".$row["BibleVersionId"]."";
                            if($debug==1){echo "-----SQL QUERY>".$SELECT_BIBLE_VERSION."<br/>";}
                            $params = array();
                            $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
                            $result_bible_version_byid = sqlsrv_query($conn, $SELECT_BIBLE_VERSION , $params, $options);
                            $bible_version_for_verse = "";
                            while($row_versionid = sqlsrv_fetch_array($result_bible_version_byid, SQLSRV_FETCH_ASSOC))  {
                                $bible_version_for_verse = $row_versionid["Version"];
                            }
                            
                            $SELECT_GET_BIBLE_CHAPTER_AND_ID = "SELECT * FROM BibleChapter WHERE Id=".$row["BibleChapterId"];
                            if($debug==1){echo "-----SQL QUERY>".$SELECT_GET_BIBLE_CHAPTER_AND_ID."<br/>";}
                            $params = array();
                            $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
                            $result_bible_chapter_getid = sqlsrv_query($conn, $SELECT_GET_BIBLE_CHAPTER_AND_ID , $params, $options);
                            $row_count_bible_chapter = sqlsrv_num_rows($result_bible_chapter_getid);

                            if($row_count_bible_chapter=1)
                            {
                                while($row_chapterid = sqlsrv_fetch_array($result_bible_chapter_getid, SQLSRV_FETCH_ASSOC))  {
                                    $SELECT_GET_BIBLE_BOOK = "SELECT * FROM BibleBook WHERE Id=".$row_chapterid["BibleBookId"];
                                    if($debug==1){echo "-----SQL QUERY>".$SELECT_GET_BIBLE_BOOK."<br/>";}
                                    $params = array();
                                    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
                                    $result_bible_biblebook_details = sqlsrv_query($conn, $SELECT_GET_BIBLE_BOOK , $params, $options);
                                    $row_count_bookinfo = sqlsrv_num_rows($result_bible_biblebook_details);
                                    while($row_bookdetails = sqlsrv_fetch_array($result_bible_biblebook_details, SQLSRV_FETCH_ASSOC)) 
                                    {                                        
                                      if(strlen($row["VerseContent"])<=10)
                                      {
                                         echo "<div><a href=\"./index.php?VersionId=".$row["BibleVersionId"]."&ChapterId=".$row["BibleChapterId"]."&Bookid=".$row_chapterid["BibleBookId"]."&VerseNr=".$row["VerseNr"]."\" >".$row_bookdetails["Name"]." ".$row_chapterid["ChapterNumber"]." - ".$row["VerseNr"]." ". substr($row["VerseContent"],0,10) ."... (".$bible_version_for_verse.")</a>"; 
                                      }
                                      else 
                                      {
                                         echo "<div><a href=\"./index.php?VersionId=".$row["BibleVersionId"]."&ChapterId=".$row["BibleChapterId"]."&Bookid=".$row_chapterid["BibleBookId"]."&VerseNr=".$row["VerseNr"]."\" >".$row_bookdetails["Name"]." ".$row_chapterid["ChapterNumber"]." - ".$row["VerseNr"]." ". substr($row["VerseContent"],0,25) ."... (".$bible_version_for_verse.")</a>";   
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
                        echo "<p><a href=\"\" ><sup>".$row["VerseNr"]."</sup></a>". implode("", $outputverse)."</div>";
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
                            $SELECT_BIBLE_VERSION = "SELECT * FROM BibleVersion WHERE Id=".$row["BibleVersionId"]."";
                            if($debug==1){echo "-----SQL QUERY>".$SELECT_BIBLE_VERSION."<br/>";}
                            $params = array();
                            $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
                            $result_bible_version_byid = sqlsrv_query($conn, $SELECT_BIBLE_VERSION , $params, $options);
                            $bible_version_for_verse = "";
                            while($row_versionid = sqlsrv_fetch_array($result_bible_version_byid, SQLSRV_FETCH_ASSOC))  {
                                $bible_version_for_verse = $row_versionid["Version"];
                            }
                            
                            $SELECT_GET_BIBLE_CHAPTER_AND_ID = "SELECT * FROM BibleChapter WHERE Id=".$row["BibleChapterId"];
                            if($debug==1){echo "-----SQL QUERY>".$SELECT_GET_BIBLE_CHAPTER_AND_ID."<br/>";}
                            $params = array();
                            $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
                            $result_bible_chapter_getid = sqlsrv_query($conn, $SELECT_GET_BIBLE_CHAPTER_AND_ID , $params, $options);
                            $row_count_bible_chapter = sqlsrv_num_rows($result_bible_chapter_getid);

                            if($row_count_bible_chapter=1)
                            {
                                while($row_chapterid = sqlsrv_fetch_array($result_bible_chapter_getid, SQLSRV_FETCH_ASSOC))  {
                                    $SELECT_GET_BIBLE_BOOK = "SELECT * FROM BibleBook WHERE Id=".$row_chapterid["BibleBookId"];
                                    if($debug==1){echo "-----SQL QUERY>".$SELECT_GET_BIBLE_BOOK."<br/>";}
                                    $params = array();
                                    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
                                    $result_bible_biblebook_details = sqlsrv_query($conn, $SELECT_GET_BIBLE_BOOK , $params, $options);
                                    $row_count_bookinfo = sqlsrv_num_rows($result_bible_biblebook_details);
                                    while($row_bookdetails = sqlsrv_fetch_array($result_bible_biblebook_details, SQLSRV_FETCH_ASSOC)) 
                                    {                                        
                                      if(strlen($row["VerseContent"])<=10)
                                      {
                                         echo "<div><a href=\"./index.php?VersionId=".$row["BibleVersionId"]."&ChapterId=".$row["BibleChapterId"]."&Bookid=".$row_chapterid["BibleBookId"]."&VerseNr=".$row["VerseNr"]."\" >".$row_bookdetails["Name"]." ".$row_chapterid["ChapterNumber"]." - ".$row["VerseNr"]." ". substr($row["VerseContent"],0,10) ."... (".$bible_version_for_verse.")</a>"; 
                                      }
                                      else 
                                      {
                                         echo "<div><a href=\"./index.php?VersionId=".$row["BibleVersionId"]."&ChapterId=".$row["BibleChapterId"]."&Bookid=".$row_chapterid["BibleBookId"]."&VerseNr=".$row["VerseNr"]."\" >".$row_bookdetails["Name"]." ".$row_chapterid["ChapterNumber"]." - ".$row["VerseNr"]." ". substr($row["VerseContent"],0,25) ."... (".$bible_version_for_verse.")</a>";   
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
                        echo "<p><a href=\"\" ><sup>".$row["VerseNr"]."</sup></a>". implode("", $outputverse)."</div>";
                        unset($outputverse);
                    }
                    
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Search containing OR condition   
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////                    
                    
//                    if($biblebookid > 0 )
//                    {
//                        $SELECT_SEARCH_QUERY_AND_OPERATION="SELECT * FROM BibleVerses WHERE BibleChapterId IN(". implode(",", $biblechaptersbyid).") AND ";
//                        if($debug==1){echo "<br/>Basic with BibleChapterId $SELECT_SEARCH_QUERY_AND_OPERATION++++++++++++++++++++++++++++++>>>>>>>>>>>>> ".$SELECT_SEARCH_QUERY_AND_OPERATION;}
//                    }
//                    else
//                    {
//                        $SELECT_SEARCH_QUERY_AND_OPERATION="SELECT * FROM BibleVerses WHERE ";
//                        if($debug==1){echo "<br/>Basic $SELECT_SEARCH_QUERY_AND_OPERATION++++++++++++++++++++++++++++++>>>>>>>>>>>>> ".$SELECT_SEARCH_QUERY_AND_OPERATION;}
//                    }
//
//                    $current_count=0;
//
//                    foreach($searchkeywords as $value)
//                    {
//                        $select_bible_book_name = "";
//                        if($debug==1){echo "<br/>-----Building query with following keyword: ". $value."-----<br/>";}
//                        if($current_count == 0)
//                        {
//                            if($debug==1){echo "<br/>================={BEFORE CONCATENATION}".$SELECT_SEARCH_QUERY_AND_OPERATION."<br/>";}
//                            if($debug==1){echo "<br/>=================------\$current_count==0 so building first part of the select query without the AND-----";}
//                            $SELECT_SEARCH_QUERY_AND_OPERATION = $SELECT_SEARCH_QUERY_AND_OPERATION." VerseContent LIKE '%".$value."%'";
//                            if($debug==1){echo "<br/>================={AFTER CONCATENATION}".$SELECT_SEARCH_QUERY_AND_OPERATION."<br/>";}
//                        }
//                        else
//                        {
//                            if($debug==1){echo "<br/>================={BEFORE CONCATENATION}".$SELECT_SEARCH_QUERY_AND_OPERATION."<br/>";}
//                            if($debug==1){echo "<br/>=================-----\$current_count>0 so building rest of the select query with AND-----<br/>";}                            
//                            $SELECT_SEARCH_QUERY_AND_OPERATION = $SELECT_SEARCH_QUERY_AND_OPERATION." OR VerseContent LIKE '%".$value."%'" ;
//                            if($debug==1){echo "<br/>================={AFTER CONCATENATION}".$SELECT_SEARCH_QUERY_AND_OPERATION."<br/>";}
//                        }
//
//                        $current_count++;
//                    }
//                    $SELECT_SEARCH_QUERY_AND_OPERATION = $SELECT_SEARCH_QUERY_AND_OPERATION." ";
//                    if($debug==1){echo "\$SELECT_SEARCH_QUERY_OR_OPERATION : <br/>".$SELECT_SEARCH_QUERY_AND_OPERATION."<br/>"; } 
//
//                    $params = array();
//                    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
//                    $result_bible_verses = sqlsrv_query( $conn, $SELECT_SEARCH_QUERY_AND_OPERATION , $params, $options );                 
//                    $outputverse = array();
//                    
//                    if($debug==1){ echo "-----KEYWORDS IN STACK - ". implode(" ",$searchkeywords)."-----<br/>" ; }
//                    
//                    while($row = sqlsrv_fetch_array($result_bible_verses, SQLSRV_FETCH_ASSOC))
//                    {
//                        try 
//                        {
//                            $SELECT_BIBLE_VERSION = "SELECT * FROM BibleVersion WHERE Id=".$row["BibleVersionId"]."";
//                            if($debug==1){echo "-----SQL QUERY>".$SELECT_BIBLE_VERSION."<br/>";}
//                            $params = array();
//                            $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
//                            $result_bible_version_byid = sqlsrv_query($conn, $SELECT_BIBLE_VERSION , $params, $options);
//                            $bible_version_for_verse = "";
//                            while($row_versionid = sqlsrv_fetch_array($result_bible_version_byid, SQLSRV_FETCH_ASSOC))  {
//                                $bible_version_for_verse = $row_versionid["Version"];
//                            }
//                            
//                            $SELECT_GET_BIBLE_CHAPTER_AND_ID = "SELECT * FROM BibleChapter WHERE Id=".$row["BibleChapterId"];
//                            if($debug==1){echo "-----SQL QUERY>".$SELECT_GET_BIBLE_CHAPTER_AND_ID."<br/>";}
//                            $params = array();
//                            $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
//                            $result_bible_chapter_getid = sqlsrv_query($conn, $SELECT_GET_BIBLE_CHAPTER_AND_ID , $params, $options);
//                            $row_count_bible_chapter = sqlsrv_num_rows($result_bible_chapter_getid);
//
//                            if($row_count_bible_chapter=1)
//                            {
//                                while($row_chapterid = sqlsrv_fetch_array($result_bible_chapter_getid, SQLSRV_FETCH_ASSOC))  {
//                                    $SELECT_GET_BIBLE_BOOK = "SELECT * FROM BibleBook WHERE Id=".$row_chapterid["BibleBookId"];
//                                    if($debug==1){echo "-----SQL QUERY>".$SELECT_GET_BIBLE_BOOK."<br/>";}
//                                    $params = array();
//                                    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
//                                    $result_bible_biblebook_details = sqlsrv_query($conn, $SELECT_GET_BIBLE_BOOK , $params, $options);
//                                    $row_count_bookinfo = sqlsrv_num_rows($result_bible_biblebook_details);
//                                    while($row_bookdetails = sqlsrv_fetch_array($result_bible_biblebook_details, SQLSRV_FETCH_ASSOC)) 
//                                    {                                        
//                                      if(strlen($row["VerseContent"])<=10)
//                                      {
//                                         echo "<div><a href=\"./index.php?VersionId=".$row["BibleVersionId"]."&ChapterId=".$row["BibleChapterId"]."&Bookid=".$row_chapterid["BibleBookId"]."&VerseNr=".$row["VerseNr"]."\" >".$row_bookdetails["Name"]." ".$row_chapterid["ChapterNumber"]." - ".$row["VerseNr"]." ". substr($row["VerseContent"],0,10) ."... (".$bible_version_for_verse.")</a>"; 
//                                      }
//                                      else 
//                                      {
//                                         echo "<div><a href=\"./index.php?VersionId=".$row["BibleVersionId"]."&ChapterId=".$row["BibleChapterId"]."&Bookid=".$row_chapterid["BibleBookId"]."&VerseNr=".$row["VerseNr"]."\" >".$row_bookdetails["Name"]." ".$row_chapterid["ChapterNumber"]." - ".$row["VerseNr"]." ". substr($row["VerseContent"],0,25) ."... (".$bible_version_for_verse.")</a>";   
//                                      }
//                                    }
//                                }
//                            }
//                        }
//                        catch(Exception $e)
//                        {
//                            echo "Error occured: " + $e;
//                        }
//                        //$array_of_bible_verse fix in display the \s\. 
//                        $array_of_bible_verse = preg_split("/\s+/",preg_replace('/[.]/', ' .', $row["VerseContent"]));
//
//                        //                       TODO: Fix regex space and fullstop to be replaced with only full stop. 
//                        // Replacing $keyword
//                        $tmp=$searchkeywords;
//
//                        // Replacing $keyword
//                        if($debug==1){ echo "<br/>-----KEYWORDS". implode(" ", $tmp)."-----<br/>";}
//                        if($debug==1){ echo sizeof($array_of_bible_verse)." Size Array....<br/>";}
//
//                        $searchkeywords_lowercase= array();
//                        foreach($searchkeywords as $keyword)
//                        {
//                            $searchkeywords_lowercase[] = strtolower(trim($keyword));
//                        }
//
//                        for($i=0 ; $i < sizeof($array_of_bible_verse); $i++)
//                        {                            
//                            try
//                            {
//                                $lower_case_verse_word = strtolower($array_of_bible_verse[$i]);
//                                if($debug==1){ echo "[$i]Lowercase for ->>>> ".$lower_case_verse_word."<<<<- in [".implode(" ",$searchkeywords_lowercase)."]<br/>";}
//                                $array_search_result = array_search($lower_case_verse_word, $searchkeywords_lowercase);
//                                if($debug==1){ echo "[$i]----->".$array_search_result."<-----Array Search Result <br/>";} 
//                                if($array_search_result===false)
//                                {
//                                  $outputverse[] = " ".preg_replace('/\s\./', '.', $array_of_bible_verse[$i])." ";
//                                }
//                                else if($array_search_result==0 || $array_search_result > 0) 
//                                {
//                                  if($debug==1){ echo "[--|".preg_replace('/\s\./', '.', $array_of_bible_verse[$i])."|-- FOUND IN KW]"; } // TODO : Fix this so that it displays the characters correctly without the space only the .
//                                  $outputverse[] = " <b>".preg_replace('/\s\./', '.', $array_of_bible_verse[$i])."</b> ";
//                                }
//                            }
//                            catch(Exception $e)
//                            {
//                                
//                            }
//                        }  
//
//                        // Build HTML string
//                        echo "<p><a href=\"\" ><sup>".$row["VerseNr"]."</sup></a>". implode("", $outputverse)."</div>";
//                        unset($outputverse);
//                    }                      
                }
      
        include './Footer.php';
