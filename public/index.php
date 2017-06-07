<?php
use App\Controllers\Controller;
use App\Database;
use App\Models\RecipeModel;
use App\Models\UserModel;

// Sökväg till grundmappen i projektet
$baseDir = __DIR__ . '/..';

// Ladda in Composers autoload-fil
require $baseDir . '/vendor/autoload.php';

// Ladda konfigurationsdata
$config = require $baseDir. '/config/config.php';

// Normalisera url-sökvägar  ---
$path = function ($uri) {
	$uri = ($uri == "/") ? $uri : rtrim($uri, '/');

	$uri = explode('?', $uri);
	$uri = array_shift($uri);

	return $uri;
};

$dsn = "mysql:host=".$config['host'].";dbname=".$config['db'].";charset=".$config['charset'];
$pdo = new PDO($dsn, $config['user'], $config['password'], $config['options']);
$db = new Database($pdo);

/*
Exempel börjar här! Ta bort detta om du vill

$db->create('recipes', [
	'name' => "Makaroner",
	'quantity' => 5,
	'recipe_difficulty' => "Easy",
	'user_id' => 1
]);

$recipe = $db->getById('recipes', 1);
$recipes = $db->getAll('recipes');

$recipeModel = new RecipeModel($db);
$recipe = $recipeModel->getById(1);
$recipes = $recipeModel->getAll();
$recipeModel->create([
	'name' => "Falukorv",
	'quantity' => 2,
	'recipe_difficulty' => "Hard",
	'user_id' => 1
]);

Exempel slutar här
*/

/**
 * Routing
 * Route: En sökväg/url. Varje case är en route.
 * Vy: En fil som laddas in som visar en sida.
 * En vy behöver en route för att visas men en route behöver inte alltid ha en vy.
 * Tex så är det en bra idé att dirigera om till en annan route efter en POST.
 *
 * Du kan bestämma själv om du vill använda en Controller-klass eller om vill skriva koden direkt
 * i varje case. En bra riktlinje är att om koden blir mer än 4-6 rader i ett case så är det dags
 * att lyfta ut koden till en Controller-metod. Det är viktigt att kunna titta på switch:en
 * och kunna få en bra översikt över vad som händer. Om det ligger för mycket kod i varje case
 * så blir det svårt att få en översikt.
 */
//$controller = new Controller($baseDir);
$url = $path($_SERVER['REQUEST_URI']);
switch ($url) {
	case '/':
		//$controller->index();
		require $baseDir.'/views/index.php';
	break;
	case '/create-recipe':
		// Detta är ett enkelt exempel på hur vi skulle kunna spara datan vid en create.
		// $controller->createRecipe($recipeModel, $_POST);
		$recipeModel = new RecipeModel($db);
		$recipeId = $recipeModel->create($_POST);

		// Dirigera tillbaka till förstasidan efter att vi har sparat.
		// Vi skickar med id:t på receptet som sparades för att kunna använda oss av det i vår vy.
		header('Location: /?id='.$recipeId);
	break;
	default:
		header('HTTP/1.0 404 Not Found');
		echo 'Page not found';
	break;
}