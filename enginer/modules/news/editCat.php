<?phpif (isset($_POST['edit']) && is_numeric($_POST['id'])) {    $NameCat = strip_tags($_POST['nameCat']);    if (!empty($NameCat)) {        Simple_DbApi::update_db($_GET['name_tamlates'] . "_cat", "names", $NameCat, "ids", $_POST['id']);        echo CMS::AlertWindow('Успешно', 'Данные обновлены', 1, 0);        CMS::UserEvents('<b>' . $_SESSION["login_admin"] . '</b> изменил название категории <b>' . $NameCat . '</b> в модуле <b>' . $TitleModule . '</b>. Раздел <a target="_blank" href="main-modules-setting-mod-' . $_GET['name_tamlates'] . '-2.pl"><b>Редактировать категорию</b></a>');    } else echo CMS::AlertWindow('Ошибка', 'Внимание: Не заполнено поле "название"', 3, 0);}echo CMS::AdminModuleSection(8, "", "");echo CMS::AdminModuleSection(10, "", "");$SelectC = Simple_DbApi::select_db($_GET['name_tamlates'] . "_cat", "*", "", "", "names", 1, "", "");if (!empty($SelectC)) {    foreach ($SelectC as $i => $nc) {        echo CMS::AdminModuleSection(12, "%NAME%,%ID%,{window}", $nc['names'] . "<><>" . $nc['ids'] . "<><>" . CMS::AlertWindow('Редактирование категории', CMS::AdminModuleSection(13, '%ID%,%NAME%', $nc['ids'] . '<><>' . $nc['names']), 4, $nc['ids']));    }}echo CMS::AdminModuleSection(11, "", "");echo CMS::AdminModuleSection(9, "", "");?>