<?php

function analyzeDirectory($directory) {
    // Vérifie si le répertoire existe
    if (!is_dir($directory)) {
        echo "Le répertoire spécifié n'existe pas.\n";
        return;
    }

    // Ouvre le répertoire
    $files = scandir($directory);
    foreach ($files as $file) {
        // Ignore les fichiers et répertoires spéciaux
        if ($file === '.' || $file === '..') {
            continue;
        }

        $fullPath = $directory . DIRECTORY_SEPARATOR . $file;

        // Si c'est un répertoire, on l'analyse récursivement
        if (is_dir($fullPath)) {
            analyzeDirectory($fullPath);
        } elseif (pathinfo($fullPath, PATHINFO_EXTENSION) === 'php') {
            // Vérifie les erreurs de syntaxe
            checkSyntax($fullPath);
            // Vérifie les espaces de noms
            checkNamespace($fullPath);
        }
    }
}

function checkSyntax($file) {
    // Utilise la commande PHP pour vérifier la syntaxe
    $output = null;
    $returnVar = null;
    exec("php -l " . escapeshellarg($file), $output, $returnVar);

    if ($returnVar !== 0) {
        echo "Erreur de syntaxe dans le fichier: $file\n";
        echo implode("\n", $output) . "\n";
    }
}

function checkNamespace($file) {
    $content = file_get_contents($file);
    if (preg_match('/namespace\s+([a-zA-Z_][\w\\\]*)\s*;/', $content, $matches)) {
        $namespace = $matches[1];
        // Vérifie si le nom de l'espace de noms contient des caractères non conformes
        if (!preg_match('/^[a-zA-Z_][\w\\\]*$/', $namespace)) {
            echo "Espace de noms non conforme dans le fichier: $file\n";
            echo "Espace de noms trouvé: $namespace\n";
        }
    } else {
        echo "Aucun espace de noms trouvé dans le fichier: $file\n";
    }
}

// Remplacez ce chemin par le répertoire que vous souhaitez analyser
$projectDirectory = 'C:\xampp\htdocs\stageconnect\app\Models';
analyzeDirectory($projectDirectory);

?>
