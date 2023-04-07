<?php
/*

 * Bible4All is a free and open source distribution of Bible software for the Christian Community (Exclusive)
 * The software is developed for Jesus Christ and for the use of His church to further the gospel of Jesus Christ.
 * 
 * The software should be used responsibly and for the furtherance of the gospel of peace between God and man 
 * via Jesus Christ. 
 *
 */
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

.footer-ahref{
    color: white;
}

.search-bar-custom{
    background-image: url('./images/grey-mg.png');
    background-color: white;
    background-image: url('searchicon.png');
    background-position: 10px 10px;
    background-repeat: no-repeat;
    padding-left: 40px;
}

@media only screen and (max-width: 1920px) {
    .header_top_logo{
        background-image: url(./images/Bible4All_1920.jpg); 
        background-size: 1920px 306px;
        background-repeat: no-repeat;
        background-position: center center;
        height: 310px;
    }
}

@media only screen and (min-width: 1367px) and (max-width: 1600px) {
    .header_top_logo{
        background-image: url(./images/Bible4All_1600.jpg);
        background-size: 1600px 300px;
        background-color: #990000;
        background-repeat: no-repeat;
        background-position: center center;
        height: 300px;
    }    
}

@media only screen and (min-width: 1201px) and (max-width: 1366px) {
    .header_top_logo{
        background-image: url(./images/Bible4All_1366.jpg);
        background-size: 1366px 300px;
        background-color: #990000;
        background-repeat: no-repeat;
        background-position: center center;
        height: 300px;
    }    
}

@media only screen and (min-width: 801px) and (max-width: 1200px) {
    .header_top_logo{
        background-image: url(./images/Bible4All_1200.jpg);
        background-size: 1200px 300px;
        background-color: #990000;
        background-repeat: no-repeat;
        background-position: center center;
        height: 300px;
    }    
}

@media only screen and (max-width: 800px) {
    .header_top_logo{
        background-image: none; 
        background-color: #ffffff;
        background-repeat: no-repeat;
        background-position: center center;
        height: 300px;
    }    
}

@media only screen and (max-width: 800px) {
    .header_top_logo{
        background-image: none; 
        background-color: #ffffff;
        background-repeat: no-repeat;
        background-position: center center;
        height: auto;
    }    
}

@media only screen and (max-width: 600px) {
    .header_top_logo{
        background-image: none; 
        background-color: #ffffff;
        background-repeat: no-repeat;
        background-position: center center;
        height: auto;
    }    
}

@media only screen and (max-width: 500px) {
    .header_top_logo{
        background-image: none; 
        background-color: #ffffff;
        background-repeat: no-repeat;
        background-position: center center;
        height: auto;
    }    
}

#loading {width: 100%;height: 100%;top: 0px;left: 0px;position: fixed;display: block; z-index: 99}

#loading-image {position: absolute;top: 40%;left: 45%;z-index: 100} 
    </style> 
   </head>
   <body>
       <div id="loading">
        <img id="loading-image" src="images/loading.gif" alt="Loading..." />
       </div>
       <div class="container-fluid">
           <div  class="row">
               <div class="col-sm-12">
                    <nav>
                        <a href="./"><b>Home</b></a> |
                        <a href="./comparativestudy.php"><b>Comparative Bible Study</b></a> |
                        <a href="./pdfs.php"><b>Bible in PDF</b></a> |
                        <a href="./statementoffaith.php"><b>Statement of Faith</b></a> |
                        <a href="./about.php"><b>About</b></a> |
                        <a href="./contactus.php"><b>Contact Us</b></a>
<!--                    <a href="./test_search_function.php"><b>test_search_function</b></a> |                         
                        <a href="./simpleregexmatch.php"><b>simpleregexmatch</b></a>-->
                    </nav>
               </div>
           </div>
           <div  class="row">
               <div class="col-sm-12" center-block>
                    <div class="header_top_logo">
                        <form class="form-inline" action="Search.php" method="POST">
                          
                          
                            <table style="width:100%;">
                            <tr>
                                <td><br/></td>
                            </tr>
                            <tr>
                                <td>
                                  <input class="form-control search-bar-custom" placeholder="Bibleverse or keyword ; biblebook(optional) (E.g. In the beginning; John)" name="searchfield" type="text" style="width:100%;"/>
                                </td>
                                <td><button class="btn btn-primary-search" onclick="this.form.submit()">Search</button></td>
                            </tr>
                          </table>
                        </form>
                    </div>
               </div>
           </div>
           <div>
               <p></p>
           </div>
           <div class="row">          
