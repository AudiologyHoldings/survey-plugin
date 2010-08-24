<?php 
header("Content-disposition: attachment; filename=contact_". Inflector::slug(Configure::read('Survey.name')) .".vcf");
header("Content-type: text/x-vcard"); 
//Build the vcard
$vcard = $vcf->begin();
$vcard .= $vcf->fullName(Configure::read('Survey.name'));
$vcard .= $vcf->email(Configure::read('Survey.email'));
$vcard .= $vcf->end();
echo $vcard;