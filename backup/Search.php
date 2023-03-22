<?php include './ConnectionString.php';?>
<?php include './Header.php';?>
<?php
//Ref : https://stackoverflow.com/questions/12369709/php-string-words-to-array
                if(isset($_POST["searchfield"]))
                {
                    // Get book names, if books are in the bible then do search of Bible Verses in Bible Create link to Bible Reading full chapter
                    echo '$_POST["searchfield"] ISSET<br/>';
                    echo 'Starting search, by extracting all Bible books from the keywords<br/>';

                    $_SESSION["SEARCH_KEYWORDS"]= $_POST["searchfield"];
                    $searchkeywords=preg_split("/\s+/", $_POST["searchfield"]);

                    $amount_of_keywords = sizeof($searchkeywords);
                    $keywords_as_biblebooks_count = 0;
                    $keywords_as_biblebooks = array();
                    $keywords_as_bibleverses = array();

                    // Might want to change thinking
                    foreach($searchkeywords as $value)
                    {
                        // Check if any keywords match a book in the bible, then search the terms where bookid is genesis for example
                       $sqlquery_bible_bookresult = "select * from BibleBook WHERE BibleBook.Name LIKE '".$value."';";
                       if($debug == 1){ echo "-----SQL QUERY : ". $sqlquery_bible_bookresult.""; }
                       $params = array();
                       $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
                       $result_bible_books = sqlsrv_query( $conn, $sqlquery_bible_bookresult , $params, $options );
                       $row_count = sqlsrv_num_rows($result_bible_books);

                       if ($row_count === false || $row_count == 0){
                           $keywords_as_bibleverses[] = $value;
                           echo "-----VALUE ADDED to \$keywords_as_bibleverses[] ". $value."-----<br/>";
                       }
                       else if($row_count > 0)
                       {
                           echo "-----Number of books found for keyword ----->>>>".$value." - Rowcount Verse Content ".$row_count."-----<br/>";
                           $keywords_as_biblebooks[] = $value;
                           echo "-----VALUE ADDED to \$keywords_as_biblebooks[] " . $value."-----<br/>";
                       }
                       
                       if($debug==1){echo sizeof($keywords_as_bibleverses)." - <br/>Size of \$keywords_as_bibleverses array <br/>";}
                       
                        if($row_count>0)
                       {
                           $keywords_as_biblebooks_count++;
                       }

                       if($result_bible_books === false) {
                           die(print_r(sqlsrv_errors(), true));                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 die(print_r(sqlsrv_errors(), true));
                       }
                       else
                       {
                       }

                        // All books information extracted from keywords
                       while($row = sqlsrv_fetch_array($result_bible_books, SQLSRV_FETCH_ASSOC)) {
                           $keywords_as_biblebooks[] = $row["Name"];
                           echo "------->>>>>> Bible Book as Keyword Found: " . $row["Name"]."<br/>";
                       }
                    }
                    
                    // SEARCH keywords by book/s then by keyword
                    // For everybook create a result
                    foreach($keywords_as_biblebooks as $bookvalue) {

                        // Get verses by Biblebook (get bibleid first)
                        // Check if any keywords match a book in the bible, then search the terms where bookid is genesis for example
                        $sqlquery_bible_biblebookid = "select Id from BibleBook WHERE BibleBook.Name='".$bookvalue."'";                        
                        $result_bible_biblebookid = sqlsrv_query($conn, $sqlquery_bible_biblebookid);
                        if($debug=1){ echo "<br/>[SEARCH] - BibleBookID - SQL QUERY---->".$sqlquery_bible_biblebookid."<br/>"; }
                        if($result_bible_biblebookid === false) {
                            die(print_r(sqlsrv_errors(), true));                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 die(print_r(sqlsrv_errors(), true));
                        }
                        else 
                        {

                        }                                                       

                        // Get chapters for the book
                        // SELECT * FROM BibleChapter WHERE BibleBookId=".$row[$bible_book]."";

                        // For every verse create a link
                        while($bible_book = sqlsrv_fetch_array($result_bible_biblebookid, SQLSRV_FETCH_ASSOC))
                        {
                            $sqlquery_bible_biblechapters = "SELECT * FROM BibleChapter WHERE BibleBookId=".$bible_book["Id"]."";                        
                            $result_bible_biblechapters = sqlsrv_query($conn, $sqlquery_bible_biblechapters);
                            if($debug=1){ echo "[SEARCH] - BibleBookID - SQL QUERY---->".$sqlquery_bible_biblechapters."<br/>"; }

                            if($result_bible_biblechapters === false) {
                                die(print_r(sqlsrv_errors(), true));                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 die(print_r(sqlsrv_errors(), true));
                            }
                            else 
                            {
                            }
                        }
                    }

                    // Search bible verses without the limitation of books
                    // AND Search then OR Search
                    // Build Select Queries
                    $SELECT_SEARCH_QUERY_AND_OPERATION="SELECT * FROM BibleVerses WHERE ";
                    $SELECT_SEARCH_QUERY_OR_OPERATION="";
                    $current_count=0;
                    if($debug==1){echo sizeof($keywords_as_bibleverses)." - Size of \$keywords_as_bibleverses array ";}
                    foreach($keywords_as_bibleverses as $verse_keyword)
                    {
                        echo "<br/>-----Building query with following keyword: ". $verse_keyword."-----<br/>";
                        if($current_count == 0)
                        {
                            echo "<br/>================={BEFORE CONCATENATION}".$SELECT_SEARCH_QUERY_AND_OPERATION."<br/>";
                            echo "<br/>=================------\$current_count==0 so building first part of the select query without the AND-----";
                            $SELECT_SEARCH_QUERY_AND_OPERATION = $SELECT_SEARCH_QUERY_AND_OPERATION." VerseContent LIKE '%".$verse_keyword."%'";
                            echo "<br/>================={AFTER CONCATENATION}".$SELECT_SEARCH_QUERY_AND_OPERATION."<br/>";
                        }
                        else
                        {
                            echo "<br/>================={BEFORE CONCATENATION}".$SELECT_SEARCH_QUERY_AND_OPERATION."<br/>";
                            echo "<br/>=================-----\$current_count>0 so building rest of the select query with AND-----<br/>";                            
                            $SELECT_SEARCH_QUERY_AND_OPERATION = $SELECT_SEARCH_QUERY_AND_OPERATION." AND VerseContent LIKE '%".$verse_keyword."%'" ;
                            echo "<br/>================={AFTER CONCATENATION}".$SELECT_SEARCH_QUERY_AND_OPERATION."<br/>";
                        }

                        $current_count++;
                    }

                    echo "\$SELECT_SEARCH_QUERY_OR_OPERATION : <br/>".$SELECT_SEARCH_QUERY_AND_OPERATION."<br/>";
                  }
                
