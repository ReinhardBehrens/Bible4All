<?php

    if(isset($_GET["ChapterId"]))
    {
        echo "ChapterId - ". $_GET["ChapterId"]."<br/>";
    }

    if(isset($_GET["Bookid"]))
    {
        echo "Bookid - ". $_GET["Bookid"]."<br/>";
    }
    
    if(isset($_GET["VerseNr"]))
    {
        echo "VerseNr - ". $_GET["VerseNr"]."<br/>";
    }
    
    if(isset($_GET["VersionId"]))
    {
        echo "Version ID - ".$_GET["VersionId"]."<br/>";
    }

    
    // This page is a search page for a specific verse that was clicked.