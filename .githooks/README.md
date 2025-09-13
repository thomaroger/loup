# Hooks Git pour la normalisation du style de code

Ce projet utilise des hooks Git pour s'assurer que le code respecte les standards de style PSR-12 et Symfony.

## 🔧 Hooks configurés

### Pre-commit Hook
- **Fichier** : `.git/hooks/pre-commit`
- **Déclencheur** : Avant chaque `git commit`
- **Action** : Vérifie le style de code des fichiers PHP modifiés avec ECS
- **Comportement** : Empêche le commit si des erreurs de style sont détectées

### Pre-push Hook
- **Fichier** : `.git/hooks/pre-push`
- **Déclencheur** : Avant chaque `git push`
- **Action** : Vérifie le style de code de tous les fichiers PHP du projet
- **Comportement** : Empêche le push si des erreurs de style sont détectées

## 🚀 Utilisation

### Commits normaux
```bash
git add .
git commit -m "Votre message de commit"
# Le hook pre-commit s'exécute automatiquement
```

### Si des erreurs de style sont détectées
```bash
# Le commit sera bloqué avec un message d'erreur
# Corrigez automatiquement avec :
composer cs-fix

# Puis recommencez le commit
git add .
git commit -m "Votre message de commit"
```

### Vérification manuelle
```bash
# Vérifier le style de code
composer cs-check

# Corriger automatiquement
composer cs-fix
```

## ⚙️ Configuration

Les hooks sont configurés pour analyser :
- **Dossiers** : `src/`, `config/`, `migrations/`
- **Fichiers** : `*.php`
- **Règles** : PSR-12 + standards Symfony

## 🔄 Désactiver temporairement

Si vous devez désactiver temporairement les hooks :

```bash
# Désactiver le hook pre-commit
chmod -x .git/hooks/pre-commit

# Désactiver le hook pre-push
chmod -x .git/hooks/pre-push

# Réactiver les hooks
chmod +x .git/hooks/pre-commit
chmod +x .git/hooks/pre-push
```

## 📝 Notes importantes

- Les hooks ne s'exécutent que sur les fichiers PHP
- Les fichiers Twig ne sont pas vérifiés par ECS (seulement l'indentation manuelle)
- Les hooks sont locaux et ne sont pas partagés avec le dépôt Git
- Pour partager la configuration, utilisez un outil comme `pre-commit` ou `husky`
