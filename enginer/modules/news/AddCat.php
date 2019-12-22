<?php

if(isset($_POST['add']))
{
	$NameCat = strip_tags($_POST['nameCat']);
	
	$metaTitle = strip_tags($_POST['SIMPLE_META_TITLE']);
	$metaKey = strip_tags($_POST['SIMPLE_META_KEYWORD']);
	$metaDesc = strip_tags($_POST['SIMPLE_META_DESC']);
	
	if(!empty($NameCat)) 
	{
		Simple_DbApi::insert_db($_GET['name_tamlates']."_cat","ids,names,cat_title,cat_key,cat_dec","<><>".$NameCat."<><>".$metaTitle."<><>".$metaKey."<><>".$metaDesc);
		echo CMS::AlertWindow('Успешно','Категория успешно добавлена',1,0);
		CMS::UserEvents('<b>'.$_SESSION["login_admin"].'</b> добавил категорию <b>'.$NameCat.'</b> в модуле <b>'.$TitleModule.'</b>. Раздел <a target="_blank" href="main-modules-setting-mod-'.$_GET['name_tamlates'].'-1.pl"><b>Добавить категорию</b></a>');
	}
	else echo CMS::AlertWindow('Ошибка','Внимание: Не заполнено поле "название"',3,0);
	
}

echo CMS::AdminModuleSection(5,"{seo}",CMS::SeoForm('','',''));

?>