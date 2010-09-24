<?php
foreach($data as $record){
  $row = array_values($record[$model]);
  $csv->addRow($row);
}
echo $csv->render($filename);
?>