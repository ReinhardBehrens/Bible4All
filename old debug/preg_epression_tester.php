<?php
                    $original_string = "in the beginning ";
                    $biblebook_array = preg_split("/[;]/", $original_string);
                    $searchkeywords = array();
                    $bible_book = "";
                    if(sizeof($biblebook_array)==1)
                    {
                        // No biblebook given
                        $searchkeywords=preg_split("/\s+/", $original_string);
                        echo "-----FOUND [".sizeof($searchkeywords)."] KEYWORDS-----";
                    }
                    else if(sizeof($biblebook_array)==2)
                    {
                        // Verse + Book
                        $searchkeywords=preg_split("/\s+/", $biblebook_array[0]);
                        $bible_book= trim($biblebook_array[1]);
                        echo "-----FOUND [".sizeof($searchkeywords)."] KEYWORDS-----";
                        echo "-----FOUND BIBLEBOOK [".$bible_book."]-----";
                    }
                    
                    $amount_of_keywords = sizeof($searchkeywords);
