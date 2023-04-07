<?php

    if(isset($_POST["name"]) && isset($_POST["email"]) && isset($_POST["subject"]) && isset($_POST["message"]))
    {
        // Send email to admin
        $msg = "Message sent from ".$_POST["email"]."\n\n".$_POST["message"];
        mail("reinhardforjesus@gmail.com","Bible4All site - Topic -> ".$_POST["subject"] ,$msg);
    }
