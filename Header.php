<?php
    session_start();
    $debug=0;
?>
<!DOCTYPE html>
<html lang="en">
    <head>
     <title>Bible4All</title>
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1">
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
     <link rel="stylesheet" href="./css/style.css">
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
        /*This is modifying the btn-primary colors but you could create your own .btn-something class as well*/
.btn-primary-search {
    color: #fff;
    background-color: #990000;
    border-color: darkred /*set the color you want here*/
}
.btn-primary-search:hover, .btn-primary-search:focus, .btn-primary-search:active, .btn-primary-search.active, .open>.dropdown-toggle.btn-primary-search {
    color: #fff;
    background-color: red;
    border-color: grey /*set the color you want here*/
}

.bg-primary-custom{
    background-color: #990000;
}
    </style> 
   </head>
   <body>      
       <div class="container">
           <div  class="row align-items-start">
               <div class="col-auto">
                <nav>
                    <a href="./">Home</a> |
                    <a href="./ParallelView/">Parallel View</a> |
                    <a href="./PDFs/">PDF's</a> |
                    <a href="./About/">About</a>
                    <a href="./test_search_function.php">test_search_function</a>
                </nav>
               </div>
           </div>
           <div class="container-fluid p-5 bg-danger bg-primary-custom text-white text-center">
                <h1>My First Bootstrap Page</h1>
                <p>Resize this responsive page to see the effect!</p> 
           </div>
           <div  class="row align-items-start">
               <div class="col">
                    <form class="form-inline" action="Search.php" method="POST">
                      <div class="form-group mb-2">
                          <input class="form-control" placeholder="bibleverse or keyword ; biblebook(optional) (E.g. In the beginning; John)" name="searchfield" type="text" style="width:100%;"/>
                      </div>
                      <button class="btn btn-primary-search" onclick="this.form.submit()">Search</button>
                    </form>               
               </div>
           </div>
           <div>
               <p></p>
           </div>
           <div class="row">            
           
