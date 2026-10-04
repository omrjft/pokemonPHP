<?php
$datos = null;

// Obtener los primeros 20 Pokémon
$urlLista = "https://pokeapi.co/api/v2/pokemon?limit=20";
$respLista = @file_get_contents($urlLista);
$listaPokemons = [];

if ($respLista !== false) {
    $datosLista = json_decode($respLista, true);
    $listaPokemons = $datosLista["results"] ?? [];
}

// Búsqueda de un Pokémon específico
if (isset($_GET['pokemon']) && !empty($_GET['pokemon'])) {

    $pokemonBuscado = strtolower(trim($_GET['pokemon']));


    $url = "https://pokeapi.co/api/v2/pokemon/" . $pokemonBuscado;


    $respuesta = @file_get_contents($url);

    if ($respuesta !== false) {
        $datos = json_decode($respuesta, true);
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Buscador Pokémon</title>
    <style>

        body {
            background-color: #130c38;
        }

        .cuadricula {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            gap: 15px;
            padding: 0;
            list-style: none;
            
        }

        .tarjeta-pokemon {
            padding: 0;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            background-color: #a6c9ff;
            text-align: center;
            transition: transform 0.2s, box-shadow 0.2s, background-color 0.2s;
            overflow: hidden;
        }

        .tarjeta-pokemon:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(255, 255, 255, 0.6);
            background-color: #bcd7ff;
        }

        .tarjeta-pokemon a {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
            padding: 15px 10px;
            box-sizing: border-box;
            text-decoration: none;
            color: #1a1a2e;
            font-weight: bold;
            font-size: 16px;
        }

        .tarjeta-pokemon a span {
            margin-top: 8px;
        }

        .buscador-form {
            display: flex;
            gap: 10px;
            width: 100%;
            max-width: 480px;
            margin: 15px 0 25px 0;
        }

        .buscador-input {
            flex: 1;
            padding: 12px 18px;
            border: 2px solid transparent;
            border-radius: 25px;
            font-size: 16px;
            outline: none;
            background-color: #ffffff;
            color: #333;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .buscador-input:focus {
            border-color: #a6c9ff;
            box-shadow: 0 4px 12px rgba(166, 201, 255, 0.5);
        }

        .buscador-btn {
            padding: 12px 24px;
            border: none;
            border-radius: 25px;
            background: linear-gradient(135deg, #ff5350, #e03a3e);
            color: #ffffff;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(224, 58, 62, 0.4);
            transition: transform 0.15s, box-shadow 0.2s, opacity 0.2s;
        }

        .buscador-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 14px rgba(255, 118, 120, 0.67);
            opacity: 0.95;
        }

        .buscador-btn:active {
            transform: translateY(0);
        }

        .resultado {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 15px;
            background-color: #f9f9f9;
            text-align: center;
            transition: transform 0.2s, box-shadow 0.2s;
            font-family: Arial, sans-serif;
            font-size: 20px;
            margin-top: 50px;
            margin-left: 30%;
            margin-right: 30%;
            background-color: #a6c9ff;
            font-size: 20px;
        }
        .top{
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        
    </style>
</head>
<body>
<div class="top">
<h1 style="color: #ffffff; font-size: 50px;">Pokémon</h1>

<form method="GET" class="buscador-form">
    <input type="text" name="pokemon" class="buscador-input" placeholder="Escribe un pokémon..." required>
    <button type="submit" class="buscador-btn">Buscar</button>
</form>
</div>
<hr>
<div class="resultado">
<?php if ($datos): ?>
    <h2><?php echo ucfirst($datos["name"]); ?></h2>

    <img src="<?php echo $datos["sprites"]["front_default"]; ?>" width="200" alt="Imagen de pokémon">

    <p>
        Tipo: <?php echo $datos["types"][0]["type"]["name"]; ?>
    </p>

    <p>
        Altura: <?php echo $datos["height"]; ?>
    </p>
    <p>
        Peso: <?php echo $datos["weight"]; ?>
    </p>
<?php elseif (isset($_GET['pokemon'])): ?>
    <p >No se encontró el Pokémon o hubo un error en la búsqueda.</p>
<?php endif; ?>
</div>
<br></br>

<h3 style="color: #ffffff; font-size: 30px;">Lista de Pokémon:</h3>

<?php if (!empty($listaPokemons)): ?>
    <ul class="cuadricula">
        <?php foreach ($listaPokemons as $p): ?>
            <?php
                $partes = explode('/', rtrim($p['url'], '/'));
                $id = end($partes);
                $imgUrl = "https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/{$id}.png";
            ?>
            <li class="tarjeta-pokemon">
                <a href="?pokemon=<?php echo urlencode($p['name']); ?>">
                    <img src="<?php echo $imgUrl; ?>" width="96" height="96" alt="<?php echo $p['name']; ?>">
                    <span><?php echo ucfirst($p['name']); ?></span>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
</body>
</html>


