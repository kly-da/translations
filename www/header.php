<html>

<head>
  <title><?
  if ($title_full_replace)
    print $title;
  else
    print "Система коллективных переводов - " . $title;
  ?></title>
  <link rel="icon" type="image/png" href="/images/favicon.png" />
  <link rel="stylesheet" type="text/css" href="/styles/main.css">
<?php if (function_exists('additionalPageHeader')){
  additionalPageHeader();
}?></head>

<body>

<div class="header">
  <div class="middle_text">Шапка</div>
</div>

<div class="main">