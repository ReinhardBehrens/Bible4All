<?php
 
    try
    {
        $debug=1;                
        $searchkeywords = array();
        $searchkeywords[] = "jesus";                    
        $searchkeywords[] = "christ";

        $string = "And this is life eternal, that they might know thee the only true God, and Jesus Christ, whom thou hast sent.";
        $array_of_bible_verse = preg_split("/\s+/", $string);

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

//        for($i=0 ; $i < sizeof($array_of_bible_verse); $i++)
//        {
//            $regex_string = "/".$array_of_bible_verse[$i]."/i";
//            if($debug==1){ echo "<br/>Verse Number[$i]-----Regex String>".$regex_string."----Matches for Search[";}
//            $array_search_result = preg_grep($regex_string, $searchkeywords);
//            if($debug==1){ echo sizeof($array_search_result)."]<br/>";} 
//            
//            if(sizeof($array_search_result)<=0)
//            {
//              $outputverse[] = " ".preg_replace('/\s\./', '.', $array_of_bible_verse[$i])." ";
//            }
//            else if(sizeof($array_search_result) > 0) 
//            {
//              if($debug==1){ echo "<br/>[--|".preg_replace('/\s\./', '.', $array_of_bible_verse[$i])."|-- FOUND IN KW]<br/>"; } 
//              $outputverse[] = " <b>".preg_replace('/\s\./', '.', $array_of_bible_verse[$i])."</b> ";
//            }
//        }
    }
    catch(Exception $e)
    {

    }