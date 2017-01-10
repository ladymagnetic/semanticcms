<?php  session_start();  require_once '../lib/DbContent.class.php'; require_once '../config/config.php'; use SemanticCms\Databaseabstraction\DbContent; use SemanticCms\config; $db = new DbContent($config['cms_db']['dbhost'], $config['cms_db']['dbuser'], $config['cms_db']['dbpass'], $config['cms_db']['database']); ?><!DOCTYPE html>
<html vocab="http://schema.org/" typeof="WebPage" lang="de"><head><title>Reisen</title><meta content="de" http-equiv="Content-Language"><meta content="text/html. charset=utf-8" http-equiv="Content-Type"><link rel="stylesheet" href="..\css\Wald.css"></head><body><header typeof="WPHeader"><h1 property="name">Allerweltsblog</h1></header><nav typeof="SiteNavigationElement"> <ul>
<?php  $result = $db->GetAllPagesOfWebsite(1);  while($page = $db->FetchArray($result)) {  if(strcmp($page["title"],"Reisen" ) == 0) { echo '<li> <a id="currentPage" href="'.$page["title"].'.php" itemprop="url">'.$page["title"].'</a></li>'; }  else { echo '<li> <a href="'.$page["title"].'.php" itemprop="url">'.$page["title"].'</a></li>';} } ?>
</ul></nav><main property="mainContentOfPage" typeof="WebPageElement"><?php $result = $db->GetAllArticlesOfPage("Reisen");while($article = $db->FetchArray($result)) { $pubdate = new DateTime($article['publicationdate']); $curdate = new DateTime(date('Y-m-d'));if ($pubdate <= $curdate){echo '<section class=\'article\'> <h2 property="headline">'.htmlspecialchars($article['header']).'</h2>'. '<div class=\'info\'> veröffentlicht am <span>'.$pubdate->format('d. F Y').'</span>'. ' von <span>'.htmlspecialchars($article['author']).'</span></div>'. '<div class=\'content\'>'.$article['content'].'</div><ul>';$res = $db->SelectAllLablesFromAnArticleById($article['id']);while($label = $db->FetchArray($res)) {echo '<li>'.htmlspecialchars($label['lablename']).'</li>';}echo '</ul></section>';}}?> </main><footer typeof="WPFooter"> <ul><li> <a href="Kontakt.php" itemprop="url">Kontakt</a></li></ul></footer>
</body></html>