<div style="text-align: center;">
    <h1>Dev Page</h1>
</div>
<hr />
<?php
    $currentRecordFields = array(
        "first" => "first",
        "first" => "second",
        "first" => "third"
    );

    $keysWithDuplicates = array();
    foreach ($currentRecordFields as $record) {
        echo "<br /> - {$record}";
    }

    // var_dump($keysWithDuplicates);

    // var_dump (count(array_keys($currentRecordFields)) !== count(array_unique(array_keys($currentRecordFields))));
?>