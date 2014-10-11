<html>

<head>
  <title><?
  if ($title_full_replace)
    print $title;
  else
    print $title . " - Система коллективных переводов";
  ?></title>  
  <script type="text/javascript" src="/scripts/jquery-1.7.2.min.js"></script>
  <link rel="icon" type="image/png" href="/images/favicon.png" />
  <link rel="stylesheet" type="text/css" href="/styles/main.css">
  <link rel="stylesheet" type="text/css" href="/styles/font.css">
<?php 
  if (function_exists('additionalPageHeader')){
  additionalPageHeader();
}?></head>

<body>

<div class="header">
  <div class="middle_text">Шапка</div>
</div>

<div class="main">