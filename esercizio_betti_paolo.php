<?php
session_start();

// Numeri primi base
$primi = [2,3,5,7,11,13];

// Selezione difficoltà
if (!isset($_SESSION['difficolta'])) {
    $_SESSION['difficolta'] = '';
}

// Selezione difficoltà da form
if (isset($_POST['difficolta'])) {
    $_SESSION['difficolta'] = $_POST['difficolta'];

    // Numero di numeri da generare in base alla difficoltà
    if ($_SESSION['difficolta'] == 'facile') $num_valori = 3;
    if ($_SESSION['difficolta'] == 'medio') $num_valori = 7;
    if ($_SESSION['difficolta'] == 'difficile') $num_valori = 21;

    // Genera 50 numeri combinati
    $_SESSION['numeri'] = [];
    for ($i=0; $i<50; $i++) {
        $a = $primi[array_rand($primi)];
        $b = $primi[array_rand($primi)];
        $c = $primi[array_rand($primi)];
        $moltiplicatore = rand(7,21); // numero casuale da 7 a 21
        $_SESSION['numeri'][] = $a*$b*$c*$moltiplicatore;
    }
    $_SESSION['tentativi'] = 0;
}

// Se l'utente inserisce un divisore
if (isset($_POST['divisore'])) {
    $div = intval($_POST['divisore']);
    $nuovi = [];
    foreach ($_SESSION['numeri'] as $num) {
        if ($num % $div != 0) $nuovi[] = $num;
    }
    $_SESSION['numeri'] = $nuovi;
    $_SESSION['tentativi']++;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sminatore Matematico</title>
</head>
<body>
<h1>Sminatore Matematico</h1>

<?php
// Se la difficoltà non è stata scelta, mostro la scelta
if ($_SESSION['difficolta'] == '') {
    echo "<p>Scegli la difficoltà:</p>";
    echo "<form method='post'>
            <select name='difficolta'>
                <option value='facile'>Facile</option>
                <option value='medio'>Medio</option>
                <option value='difficile'>Difficile</option>
            </select>
            <button type='submit'>Inizia</button>
          </form>";
}
// Se tutti i numeri sono stati eliminati
elseif (empty($_SESSION['numeri'])) {
    echo "<p>Hai vinto! Hai eliminato tutti i numeri in ".$_SESSION['tentativi']." mosse!</p>";
    session_destroy(); // reset
}
// Altrimenti mostro il gioco
else {
    echo "<p>Numeri rimanenti: ".implode(", ", $_SESSION['numeri'])."</p>";
    echo "<form method='post'>
            <label>Inserisci un divisore:</label>
            <input type='number' name='divisore' required>
            <button type='submit'>Continua</button>
          </form>";
    echo "<p>Tentativi: ".$_SESSION['tentativi']."</p>";
}
?>
</body>
</html>
