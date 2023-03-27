<?php
                    $debug=1;
                    $testrapiddev=true;
                    $conditionismet=false;

                    if($testrapiddev==true)
                    {
                        $searchkeywords = array();
                        $searchkeywords[] = "jesus";                    
                        $searchkeywords[] = "Christ";

                        $string = "And this is life eternal, that they might know thee the only true God, and Jesus Christ, whom thou hast sent.";

                        $array_of_bible_verse = preg_split("/\s+/", $string);

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

                        echo sizeof($outputverse);
                        print_r($outputverse);

                        if(is_array($outputverse))
                        {
                            echo implode(' ' , $outputverse);
                        }

                        if($debug==1){  echo "<br/>After foreach statement...<br/>";}
                    }
                    else {
                        $conditionismet = true;
                    }
     
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    if($conditionismet == true) 
                    {
                        $serverName = "REIN\\SQLEXPRESS"; //serverName\instanceName
                        $connectionInfo = array( "Database"=>"BibleForAllServerAppv1.Server.Data", "UID"=>"sa", "PWD"=>"password");
                        $conn = sqlsrv_connect( $serverName, $connectionInfo);                    
                        if( $conn ) {
                             //echo "Connection established.<br />";
                        }else{
                             echo "Connection could not be established.<br />";
                             die(print_r(sqlsrv_errors(), true));
                        }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //                  Getting ChatperID for Verses based on BibleBook Entered
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////                    
                        // Entered BiblebookId
                        $biblebookentered = "";
                        $biblebookid = -1;
                        if($biblebookentered != null && $biblebookentered != "")
                        {
                            // Get biblebook
                            $SELECT_GET_BIBLE_BOOKID = "SELECT Id FROM BibleBook WHERE Name='".$biblebookentered."'";
                            if($debug==1){echo "-----SQL QUERY>".$SELECT_GET_BIBLE_BOOKID."<br/>";}
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
                            $biblechaptersbyid = array();
                            while($row_chapters_list = sqlsrv_fetch_array($result_bible_chapters_byid, SQLSRV_FETCH_ASSOC)) 
                            {
                              $biblechaptersbyid[] = $row_chapters_list["Id"];
                            }                      
                        }

                        $searchkeywords = array();
                        $searchkeywords[] = "in";
                        $searchkeywords[] = "the";
                        $searchkeywords[] = "beginning";

                        $string = "In the beginning was the Word";

                        if($biblebookid > 0 )
                        {
                            $SELECT_SEARCH_QUERY_AND_OPERATION = "SELECT * FROM BibleVerses WHERE BibleChapterId IN(". implode(",", $biblechaptersbyid).") AND VerseContent LIKE '%in%' AND VerseContent LIKE '%the%' AND VerseContent LIKE '%beginning%' " ;
                        }
                        else
                        {
                            $SELECT_SEARCH_QUERY_AND_OPERATION = "SELECT * FROM BibleVerses WHERE VerseContent LIKE '%in%' AND VerseContent LIKE '%the%' AND VerseContent LIKE '%beginning%' " ;                            
                        }

                        echo "-----".$SELECT_SEARCH_QUERY_AND_OPERATION."-----<br/>";

                        $params  = array();
                        $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
                        $result_bible_verses = sqlsrv_query($conn, $SELECT_SEARCH_QUERY_AND_OPERATION , $params, $options);
                        if($result_bible_verses != false)
                        {
                            $row_count = sqlsrv_num_rows($result_bible_verses);
                        }
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                        $outputverse = array();
                        $tmp = $searchkeywords;
                        if($debug==1){ echo "-----KEYWORDS IN STACK - ". implode(" ",$tmp)."-----<br/>" ; }

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
                                             echo "<div><a href=\"./biblebook.php?Id=&verseId=\" >".$row_bookdetails["Name"]." ".$row_chapterid["ChapterNumber"]." - ".$row["VerseNr"]." ". substr($row["VerseContent"],0,10) ."... (".$bible_version_for_verse.")</a>"; 
                                          }
                                          else 
                                          {
                                            echo "<div><a href=\"./biblebook.php?Id=&verseId=\" >".$row_bookdetails["Name"]." ".$row_chapterid["ChapterNumber"]." - ".$row["VerseNr"]." ". substr($row["VerseContent"],0,25) ."... (".$bible_version_for_verse.")</a>";   
                                          }
                                        }
                                    }
                                }
                            }
                            catch(Exception $e)
                            {
                                echo "Error occured: " + $e;
                            }

                            $array_of_bible_verse = preg_split("/\s+/",preg_replace('/[.]/', ' .', $row["VerseContent"]));

                            //                       TODO: Fix regex space and fullstop to be replaced with only full stop. 
                            // Replacing $keyword
                            $tmp=$searchkeywords;

                            // Replacing $keyword
                            if($debug==1){ echo "<br/>-----KEYWORDS". implode(" ", $tmp)."-----<br/>";}
                            if($debug==1){ echo sizeof($array_of_bible_verse)." Size Array....<br/>";}

                            $searchkeywords_lowercase= array();
                            foreach($searchkeywords as $keyword)
                            {
                                $searchkeywords_lowercase[] = strtolower(trim($keyword));
                            }

                            for($i=0 ; $i < sizeof($array_of_bible_verse); $i++)
                            {                            
                                try
                                {
                                    $lower_case_verse_word = strtolower($array_of_bible_verse[$i]);
                                    if($debug==1){ echo "[$i]Lowercase for ->>>> ".$lower_case_verse_word."<<<<- in [".implode(" ",$searchkeywords_lowercase)."]<br/>";}
                                    $array_search_result = array_search($lower_case_verse_word, $searchkeywords_lowercase);
                                    if($debug==1){ echo "[$i]----->".$array_search_result."<-----Array Search Result <br/>";} 

                                    if($array_search_result===false)
                                    {
                                      $outputverse[] = " ".preg_replace('/\s\./', '.', $array_of_bible_verse[$i])." ";
                                    }

                                    else if($array_search_result==0 || $array_search_result > 0) 
                                    {
                                      if($debug==1){ echo "[--|".preg_replace('/\s\./', '.', $array_of_bible_verse[$i])."|-- FOUND IN KW]"; } 
                                      $outputverse[] = " <b>".preg_replace('/\s\./', '.', $array_of_bible_verse[$i])."</b> ";
                                    }
                                }
                                catch(Exception $e)
                                {

                                }
                            }  

                            // Build HTML string
                            echo "<p><a href=\"\" ><sup>".$row["VerseNr"]."</sup></a>". implode("", $outputverse)."</div>";
                            unset($outputverse);
                        }
                    }