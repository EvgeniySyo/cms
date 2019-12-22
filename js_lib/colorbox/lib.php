<?php
$UrlSite = URL_SITE;
$Js = <<<DATA
<script type="text/javascript" src="{$UrlSite}js_lib/colorbox/jquery.colorbox-min.js"></script>
<link rel="stylesheet" type="text/css" href="{$UrlSite}js_lib/colorbox/colorbox.css" media="screen" />
<script type="text/javascript">  
$(document).ready(function(){ 
$("a.gallery").colorbox();
});
</script>
DATA;
?>