<?php

class DatabaseLayer{

    public $connectionInfo;
    public $Database;
    public $UID;
    public $PWD;
    public $CharacterSet;
    public $connection;
    public $serverName;
    public $debug;

    function __construct($Database,$UID,$PWD,$CharacterSet,$ServerName)
    {      
        $this->serverName=$ServerName;
        $this->connectionInfo = array( "Database"=>$Database, "UID"=>$UID, "PWD"=>$PWD, "CharacterSet" =>$CharacterSet);
        $this->connection = sqlsrv_connect( $this->serverName, $this->connectionInfo);

        if( $this->connection) {
             //echo "Connection established.<br />";
        }
        else
        {
             echo "Connection could not be established.<br/>";
             die(print_r(sqlsrv_errors(), true));
        }
    }
    
    function GetConnection()
    {
        return $this->connection;                
    }
    
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //      SELECT QUERIES FOR SELECT MENU's at the top of the front page
    //      
    //      Each query simply gets a list of the names of the versions, the books of the bible and the chapters
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function GetBibleVersionsByName()
    {
        //SELECT NAME FROM BIBLEVERSION
        $sqlquery_bible_version = "SELECT Name FROM BibleVersion";
        //print "SQL: $sql\n";
        $result_bible_version = sqlsrv_query($this->connection, $sqlquery_bible_version);
        if($result_bible_version === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        return $result_bible_version;
    }
    
    function GetBibleBooksByName()
    {
        // SELECT NAME FROM BIBLEBOOK
        $sqlquery_bible_books = "SELECT Name FROM BibleBook";
        $result_bible_books = sqlsrv_query($this->connection, $sqlquery_bible_books);
        if($result_bible_books === false) {
            die(print_r(sqlsrv_errors(), true));                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      die(print_r(sqlsrv_errors(), true));
        }
        
        return $result_bible_books;
    }
    
    function GetBibleBookIDbyName($BiblebookName)
    {
        $sqlquery_bible_bookid = "SELECT Id FROM BibleBook WHERE dbo.BibleBook.Name='".$BiblebookName."'";
        if($this->debug==1){echo "<br/>".$sqlquery_bible_bookid . "<br/>";}
        $result_bible_bookid = sqlsrv_query($this->connection, $sqlquery_bible_bookid);
        
        if($result_bible_bookid === false) {
            die(print_r(sqlsrv_errors(), true));                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      die(print_r(sqlsrv_errors(), true));
        }

        return $result_bible_bookid;
    }
    
    function GetBibleVersionIDByName($bible_version_name)
    {
        // GET BibleVerionId based on name
        $sqlquery_bible_versionid = "SELECT Id FROM BibleVersion WHERE dbo.BibleVersion.Name='".$bible_version_name."'";
        if($this->debug==1){echo $sqlquery_bible_versionid . "<br/>";}
        $result_bible_versionid = sqlsrv_query($this->connection, $sqlquery_bible_versionid);
        if($result_bible_versionid === false) {
            die(print_r(sqlsrv_errors(), true));                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               die(print_r(sqlsrv_errors(), true));
        }
        return $result_bible_versionid;
    }
    
    function GetChapterNumberByBibleVersionIDandBibleBookId($BibleVersionID, $BibleBookID)
    {
        $sqlquery_bible_chapters = "SELECT ChapterNumber FROM BibleChapter WHERE BibleVersionId=".$BibleVersionID." AND BibleBookId=".$BibleBookID." ORDER BY ChapterNumber ASC";
        if($this->debug==1){echo $sqlquery_bible_chapters . "<br/>";}
        $result_bible_chapters = sqlsrv_query($this->connection, $sqlquery_bible_chapters);
        if($result_bible_chapters === false) {
            die(print_r(sqlsrv_errors(), true));                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      die(print_r(sqlsrv_errors(), true));
        }

        return $result_bible_chapters;
    }
    
    function GetChapterIdByChapterNameandBiblebookIDandBibleVersionID($SelectedBibleChapter, $BibleBookID, $BibleVersionID)
    {
        // GET BibleVerionId based on name
        $sqlquery_bible_chapterid = "SELECT Id FROM BibleChapter WHERE dbo.BibleChapter.ChapterNumber=".$SelectedBibleChapter." AND BibleBookId=".$BibleBookID." AND BibleVersionId=".$BibleVersionID;
        if($this->debug==1){echo $sqlquery_bible_chapterid."<br/>";}
        $result_bible_chapterid = sqlsrv_query($this->connection, $sqlquery_bible_chapterid);
        if($result_bible_chapterid === false) {
            die(print_r(sqlsrv_errors(), true));                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      die(print_r(sqlsrv_errors(), true));
        }
        return $result_bible_chapterid;
    }
    
    function GetVerseNrandVerseContentBYBibleChapterIDandBibleVersionIDandVerseNr($VerseNr, $BibleChapterID, $BibleVersionID)
    {

        $sqlquery_bible_verses_content = "SELECT VerseNr,VerseContent FROM BibleVerses WHERE BibleChapterId=".$BibleChapterID." AND BibleVersionId=".$BibleVersionID." AND VerseNr=".$VerseNr;
        if($this->debug==1){ echo $sqlquery_bible_verses_content. "<br/>"; }
        $result_bible_verses_content = sqlsrv_query($this->connection, $sqlquery_bible_verses_content);
        if($result_bible_verses_content === false) {
            die(print_r(sqlsrv_errors(), true));                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      die(print_r(sqlsrv_errors(), true));
        }

        return $result_bible_verses_content;
    }

    function GetVerseNrandVerseContentBYBibleChapterIDandBibleVersionID($BibleChapterID, $BibleVersionID)
    {
        $sqlquery_bible_verses_content = "SELECT VerseNr,VerseContent FROM BibleVerses WHERE BibleChapterId=".$BibleChapterID." AND BibleVersionId=".$BibleVersionID."";
        if($this->debug==1){ echo $sqlquery_bible_verses_content. "<br/>"; }
        $result_bible_verses_content = sqlsrv_query($this->connection, $sqlquery_bible_verses_content);
        if($result_bible_verses_content === false) {
            die(print_r(sqlsrv_errors(), true));                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      die(print_r(sqlsrv_errors(), true));
        }

        return $result_bible_verses_content;
    }
    
    ////////////////////////////////////////////////////////////////////////////
    // $_GET session data queries
    ////////////////////////////////////////////////////////////////////////////
    function GetBibleNameBYID($VersionID)
    {
        if($this->debug==1){echo "Version ID - ".$VersionID."<br/>";}
        $SELECT_BIBLE_VERSION_FOR_SESSION = "SELECT Name from BibleVersion Where Id=".$VersionID;
        // Get biblebook            
        if($this->debug==1){echo "-----SQL QUERY \$SELECT_BIBLE_VERSION_FOR_SESSION>".$SELECT_BIBLE_VERSION_FOR_SESSION."<br/>";}
        $params = array();
        $options = array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
        $result_bible_version = sqlsrv_query($this->connection, $SELECT_BIBLE_VERSION_FOR_SESSION , $params, $options);
        
        return $result_bible_version;
    }
    
    function GetChapterNumberBYID($ChapterID)
    {
        if($this->debug==1){echo "ChapterId - ". $ChapterID."<br/>";}
        $SELECT_BIBLE_CHAPTER_FOR_SESSION = "SELECT ChapterNumber from BibleChapter WHERE Id=".$ChapterID;
        if($this->debug==1){echo "-----SQL QUERY \$SELECT_BIBLE_CHAPTER_FOR_SESSION>".$SELECT_BIBLE_CHAPTER_FOR_SESSION."<br/>";}
        $params = array();
        $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
        $result_bible_chapter = sqlsrv_query($this->connection, $SELECT_BIBLE_CHAPTER_FOR_SESSION , $params, $options);
        
        return $result_bible_chapter;
    }

    function GetBibleBookNameBYID($Bookid)
    {
        if($this->debug==1){echo "Bookid - ".$Bookid."<br/>";}
        $SELECT_BIBLE_BOOK_FOR_SESSION = "SELECT Name FROM BibleBook where Id=".$Bookid;
        if($this->debug==1){echo "-----SQL QUERY \$SELECT_BIBLE_BOOK_FOR_SESSION>".$SELECT_BIBLE_BOOK_FOR_SESSION."<br/>";}
        $params = array();
        $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET);
        $result_bible_book = sqlsrv_query($this->connection, $SELECT_BIBLE_BOOK_FOR_SESSION , $params, $options);
        
        return $result_bible_book;
    }
}