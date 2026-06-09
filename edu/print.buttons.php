<div class='buttons'>
    <?php
    if(!isset($hidePrint)){
        $hidePrint=false;
    }
    if(!$hidePrint){
    ?>
    <button type='button' class='prtBtn' onclick='window.print();window.close();'>طباعة</button>&nbsp;
    <?php
    }
    ?>
    <button type='button' class='cnlBtn' onclick='window.close();'>إغلاق</button>
    <hr><br>
</div>
