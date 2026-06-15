# Conception du bibliotheque numerique
Ce dossier contient l’ensemble des artefacts de conception du projet **bibliotheque numerique**.  
La modélisation suit une architecture en couches (domaine, application, infrastructure) et trois niveaux de vues : conceptuelle, spécification et implémentation.


## Cas d’usage (`cas-usage`)

Ces diagrammes décrivent les interactions entre les acteurs (Visiteur, hôtelière) et le système.

| Fichier                          | Description                |
|----------------------------------|----------------------------|
| `1 - scenario du lecteur`        | cas d'usage d'un lecteur   |
| `2 - scenario du bibliothecaire` | cas d'usage bibliothecaire |

---

## Diagrammes de classes (`digramme-classes`)

Trois vues progressives (du conceptuel au code) et, pour chacune, une découpe par couches hexagonales/DDD.

### 1 – Vue conceptuelle

Modélisation métier de haut niveau, indépendante de toute technologie.

| Fichier | Contenu |
|---------|---------|
| `1 - Diagramme conceptuel (classes métier)` | Entités principales et leurs relations (agrégats, value objects). |
| `info.txt` | Notes et explications complémentaires sur la vue conceptuelle. |

### 2 – Vue spécification

Détail des interfaces, contrats et dépendances entre les couches.

| Fichier | Rôle |
|---------|------|
| `1 - domaine.puml` | Spécification du cœur métier (entités, événements, règles). |
| `2 - application.puml` | Services applicatifs, use cases, ports entrants. |
| `3 - infrastructure.puml` | Ports sortants (repositories, services externes). |
| `info.txt` | Précisions sur les choix d’architecture. |

### 3 – Vue implémentation

Modèle proche du code réel (classes avec attributs, méthodes, types concrets).

| Fichier | Description |
|---------|-------------|
| `1 - domaine.puml` | Implémentation des entités et value objects. |
| `2 - application.puml` | Implémentation des services applicatifs. |
| `3 - infrastructure.puml` | Détails techniques (ORM, API clients, etc.). |
| `info.txt` | Indications sur les frameworks / bibliothèques utilisés. |

---

## Visualisation des diagrammes

Les fichiers `.puml` sont au format **PlantUML**. Pour les visualiser :

- **En local** : installez PlantUML (ou l’extension VS Code) et générez les images.
- **En ligne** : copiez le contenu sur [PlantUML Web Server](http://www.plantuml.com/plantuml/uml/).
- **Sur GitHub** : utilisez un rendu avec `![](http://www.plantuml.com/plantuml/png/...` ou l’intégration via ````puml` (selon les extensions du dépôt).

> 💡 *Conseil* : pour faciliter la lecture, vous pouvez générer une version PDF ou image de chaque diagramme et les stocker dans un sous-dossier `images/`.

---

## Évolution de la documentation

Ce dossier de conception doit rester synchronisé avec le code.  
À chaque modification architecturale majeure, mettez à jour les fichiers `.puml` correspondants et le fichier `info.txt` associé.



# Intégration de la qualité du code (PHP / Laravel)

Ce document vous guide pour intégrer des outils de contrôle qualité dans votre projet Laravel via GitHub Actions, en remplacement d’un outil comme Qodana.

## 📦 Outils utilisés

| Outil | Rôle |
|-------|------|
| **Laravel Pint** | Vérification et correction automatique du style de code (PSR-12 / conventions Laravel) |
| **PHPStan / Larastan** | Analyse statique pour détecter les bugs potentiels, types manquants, erreurs logiques |
| **Composer Audit** | Vérification des vulnérabilités dans les dépendances |
| **PHP Insights** (optionnel) | Métriques de qualité, complexité, architecture |

## ✅ Prérequis

- Projet Laravel 13+
- PHP 8.5+
- Composer
- Dépôt GitHub (public ou privé)

## 🔧 Installation des outils

Exécutez les commandes suivantes dans votre projet :

```bash
# Installer Laravel Pint (souvent déjà présent)
composer require --dev laravel/pint

# Installer Larastan (wrapper PHPStan pour Laravel)
composer require --dev larastan/larastan

# (Optionnel) PHP Insights
composer require --dev nunomaduro/phpinsights
```

Créez un fichier phpstan.neon à la racine :
```yml
includes:
    - vendor/larastan/larastan/extension.neon

parameters:
    paths:
        - app
    level: 5   # Niveau de rigueur (0=le plus faible, 9=le plus strict)
```
