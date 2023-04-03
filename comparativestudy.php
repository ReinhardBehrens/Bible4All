<?php

?>
<script>
    var col = document.querySelector('#template').innerHTML;
    var el = document.querySelector('.row.text-center');
    
    console.log(col);
    
    document.querySelector('#addrow').onclick = function() {
        alert("test");
        alert(col);
        el.insertAdjacentHTML('beforeend', col);
    }
</script>
<style>
  #template {
    display: none;
  }

  .col {
    display: inline-block;
    border: 1px solid gray;
    padding: 4px 8px;
  }
</style>
<div id="bloodsweatandtears" class="row text-center">
  <div class="col h4">We Work With:</div>
  <div class="col">test</div>
  <div class="col">test</div>
  <div class="col">test</div>
  <div class="col">test</div>
</div>
<button id="addrow">Add</button>
<div id="template">
  <div class="col">new</div>
</div>

