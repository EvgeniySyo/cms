<?php
$UrlSite = URL_SITE;
$Js = <<<DATA
<script type="text/javascript" src="{$UrlSite}js_lib/fancybox/jquery.fancybox.js"></script>
<link rel="stylesheet" type="text/css" href="{$UrlSite}js_lib/fancybox/jquery.fancybox.css" media="screen" />
<script type="text/javascript">  
$(document).ready(function(){ 
$("a.gallery").fancybox();
});
</script>
DATA;
?>