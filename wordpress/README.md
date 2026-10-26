# Verticité — Thème WordPress sur mesure

Troisième étape du projet ([vue d'ensemble](../README.md)) : l'[intégration HTML / Sass](../integration/README.md) devient un **thème WordPress classique écrit à la main**, dont **tout le contenu se modifie depuis l'admin**, sans toucher au code.

| | |
|---|---|
| **Démo** | [verticite.tristanguyomard.fr](https://verticite.tristanguyomard.fr) |
| **Thème** | [`themes/verticite/`](themes/verticite) : gabarits PHP, Sass, JS, champs ACF en JSON |
| **Plugin** | [`plugins/archi-agence/`](plugins/archi-agence) : type de contenu « Projet » + taxonomie « Type de projet » |
| **Prérequis** | WordPress 6.5 ou plus (testé en 7.1), PHP 8.1 ou plus, [Advanced Custom Fields](https://wordpress.org/plugins/advanced-custom-fields/) (version gratuite) |
| **Aucun** | constructeur de pages, thème parent ou framework |

---

## Ce que le client peut modifier

| Où, dans l'admin | Quoi |
|---|---|
| **Projets** → Ajouter | Titre, texte, image à la une, fiche technique, chiffres clés, galerie (8 images), type de projet |
| **Pages → Accueil** | Bandeau (titre, image, bouton), philosophie, 4 chiffres clés, projets mis en avant (choix et ordre) |
| **Pages → L'agence** (modèle « Agence ») | Sous-titre, 2 fondateurs, 4 étapes de la méthode, 2 images + citation |
| **Réglages → Agence** | Adresse, téléphone, e-mail, bloc « Contactez-nous » commun à toutes les pages |
| **Apparence → Menus** | Navigation principale, liens légaux du pied de page |
| **Apparence → Widgets** | Colonnes du pied de page : Contact, Menu, Réseaux sociaux |
| **Apparence → Personnaliser** | Logo |

---

## Installation

### Avec le dépôt (développement)

1. Créer un site WordPress local ([Local](https://localwp.com/), MAMP, wp-env…).
2. Relier le thème et le plugin au dossier `wp-content` du site par un lien symbolique, pour modifier le code directement dans le dépôt :
   ```bash
   ln -s "$(pwd)/wordpress/themes/verticite" /chemin/du/site/wp-content/themes/verticite
   ```
   ```bash
   ln -s "$(pwd)/wordpress/plugins/archi-agence" /chemin/du/site/wp-content/plugins/archi-agence
   ```
3. Installer et activer **Advanced Custom Fields**, puis **Archi-Agence**, puis le thème **Verticité**.
4. **ACF → Groupes de champs** : synchroniser les groupes proposés (ils sont lus depuis `acf-json/`).
5. **Réglages → Lecture** : choisir une page statique pour l'accueil. Créer une page « L'agence » avec le modèle *Agence*.
6. **Réglages → Permaliens** : enregistrer une fois, pour activer les URL `/project/`.

### Compiler le CSS

Depuis la racine du dépôt :

```bash
npm install
```

```bash
npm run watch:theme
```

Le thème **n'a pas de copie du Sass ni du JavaScript** : il réutilise les sources de l'intégration.

- `themes/verticite/sass/main.scss` charge `integration/sass` (option `--load-path`) et ne change qu'une variable, le chemin des polices. Le résultat est compilé et minifié dans `assets/css/main.css`.
- `npm run build:theme` copie aussi `integration/js` dans `assets/js/`.

Les fichiers compilés sont versionnés : le thème marche sans Node, et on peut le copier seul sur un serveur.

---

## Architecture du thème

```
themes/verticite/
├── style.css                   # En-tête du thème (métadonnées uniquement)
├── functions.php               # Ne fait que charger inc/ : un fichier = une responsabilité
│
├── inc/
│   ├── acf.php                 # verticite_field() : lit un champ ACF sans planter si ACF est désactivé
│   ├── supports.php            # title-tag, vignettes, html5, format d'image, SVG
│   ├── assets.php              # CSS + JS en module ES, version = date du fichier
│   ├── appearance.php          # Personnalisateur : logo
│   ├── menus.php               # Emplacements de menu + classes BEM sur les liens
│   ├── widgets.php             # Zone « Pied de page » + enregistrement des widgets
│   ├── widgets/                # Classes des widgets Contact et Réseaux sociaux
│   ├── options-agence.php      # Page Réglages > Agence (Settings API)
│   ├── helpers.php             # Fonctions pour les gabarits (surface, détails, chiffres…)
│   └── seo.php                 # Meta description générée depuis le contenu
│
├── front-page.php              # Accueil
├── archive-project.php         # Nos réalisations (/project/)
├── taxonomy-project_type.php   # Filtre par type → réutilise archive-project.php
├── single-project.php          # Fiche projet
├── page-templates/agence.php   # Modèle de page « Agence »
├── page.php                    # Pages simples (mentions légales…)
├── 404.php
├── index.php                   # Gabarit de secours obligatoire
├── header.php / footer.php
│
├── parts/                      # Gabarits partiels (get_template_part)
│   ├── page-intro.php          # Titre + sous-titre (+ filtres)
│   ├── project-card.php        # Carte projet, version simple ou détaillée
│   ├── project-filters.php     # Filtres par type de projet
│   ├── key-numbers.php         # Chiffres clés
│   ├── cta.php                 # Bloc « Contactez-nous »
│   └── widgets/                # Affichage des widgets
│
├── acf-json/                   # Groupes de champs ACF versionnés
├── sass/main.scss              # Point d'entrée : réutilise integration/sass
└── assets/                     # CSS compilé, JS (copié depuis integration/js), polices, images
```

Les gabarits suivent la **hiérarchie des modèles** de WordPress : aucun aiguillage manuel, WordPress choisit le bon fichier selon l'URL.

| URL | Gabarit |
|---|---|
| `/` | `front-page.php` |
| `/project/` | `archive-project.php` |
| `/project-type/logement/` | `taxonomy-project_type.php` → `archive-project.php` |
| `/project/halle-aux-grains/` | `single-project.php` |
| `/agency/` | `page-templates/agence.php` (choisi dans l'éditeur) |
| `/politique-de-confidentialite/` | `page.php` |
| URL inconnue | `404.php` |

---

## Choix techniques

### Les données dans un plugin, la présentation dans le thème

Le type de contenu `project` et la taxonomie `project_type` sont déclarés dans le plugin **Archi-Agence**, pas dans `functions.php`. Si le client change de thème un jour, ses projets restent accessibles dans l'admin. Le thème ne fait qu'afficher ce que le plugin déclare.

### Des contenus modifiables avec ACF, versionnés en JSON

Chaque page a son groupe de champs, rangé en onglets qui suivent l'ordre des sections de la maquette. Les groupes sont enregistrés dans `acf-json/` : ils sont **versionnés avec le code**, comparables dans un diff, et se synchronisent sur un autre environnement en un clic.

La version gratuite d'ACF n'a ni Répéteur ni Galerie : les éléments répétés sont des champs numérotés (`number_1` à `number_4`, `galerie_1` à `galerie_8`), regroupés par des fonctions comme `verticite_stats()` pour que les gabarits restent simples.

### Une seule source pour les coordonnées

L'adresse, le téléphone et l'e-mail sont saisis **une seule fois** dans *Réglages → Agence*, page construite avec la **Settings API** (WordPress gère le formulaire, le nonce et les droits ; chaque option a sa fonction de nettoyage). Le widget Contact et le bloc « Contactez-nous » lisent ces réglages, au lieu de stocker une copie qu'il faudrait mettre à jour à plusieurs endroits.

### Des widgets sur mesure

- **Contact** : affiche les coordonnées des réglages dans un `<address>`. L'e-mail est encodé par `antispambot()` contre les robots, et le numéro est transformé en lien `tel:+33…` cliquable.
- **Réseaux sociaux** : nombre de liens libre. On ajoute ou retire des lignes dans l'admin grâce à un `<template>` HTML et une vingtaine de lignes de JS (`assets/js/social-widget.js`).

### Le balisage de la maquette, sans compromis

`wp_nav_menu()` et les widgets génèrent leur propre HTML. Des filtres (`nav_menu_link_attributes`, `widget_nav_menu_args`) y ajoutent les classes BEM de l'intégration, pour que le CSS soit **le même** que celui de la version statique. L'élément de menu « Nos réalisations » reste actif (`aria-current`) sur une fiche projet ou un filtre.

### Le JavaScript en module ES

Les scripts de l'intégration sont chargés tels quels avec `wp_enqueue_script_module()` : ils restent en modules ES, sans bundler. La version de chaque fichier est sa date de modification : le navigateur recharge le fichier dès qu'il change.

### Les filtres sont de vrais liens

« Tous / Logement / Bureaux » pointent vers les pages de taxonomie : chaque filtre a sa propre URL (partageable, indexable), fonctionne sans JavaScript, et le filtre actif est signalé par `aria-current="page"`.

---

## Sécurité

- **Échapper à l'affichage** : `esc_html()`, `esc_attr()` et `esc_url()` selon le contexte, pour toute donnée qui vient de la bdd.
- **Nettoyer à l'enregistrement** : `sanitize_email`, `sanitize_text_field`, `sanitize_textarea_field`, `esc_url_raw`, et vérification du format du téléphone.
- **SVG** : leur envoi est autorisé uniquement aux utilisateurs qui ont déjà le droit `unfiltered_html` (administrateurs), car un SVG peut contenir du JavaScript.
- `defined( 'ABSPATH' ) || exit;` en tête des fichiers PHP : on ne peut pas les appeler directement par leur URL.
- Dépendance à ACF protégée : si le plugin est désactivé, le site reste affiché et l'admin affiche un avertissement.

## Accessibilité

Celle de l'intégration est conservée (lien d'évitement, `aria-*`, focus visible, menu au clavier), plus :
- `lang` réglé automatiquement par `language_attributes()` (`fr-FR`) ;
- liens vers les réseaux sociaux annoncés comme s'ouvrant dans un nouvel onglet ;
- niveaux de titres cohérents : la carte projet passe en `h2` ou `h3` selon la page.

## Qualité mesurée (Lighthouse)

| Page | Performance mobile / desktop | Accessibilité | Bonnes pratiques | SEO |
|---|:---:|:---:|:---:|:---:|
| Accueil | 100 / 100 | 100 | 100 | 100 |
| Nos réalisations | 99 / 100 | 100 | 100 | 100 |
| Fiche projet | 100 / 100 | 100 | 100 | 100 |
| L'agence | 100 / 100 | 100 | 100 | 100 |

<sub>Site en ligne (https://verticite.tristanguyomard.fr, hébergement o2switch, HTTPS). Lighthouse 12.8, Chrome 156 headless, profils mobile (réseau 4G lent simulé) et desktop par défaut.</sub>

Le contenu principal s'affiche en 1,6 à 2,2 s sur mobile et en moins de 0,7 s sur desktop (LCP). La mise en page ne bouge pas pendant le chargement (CLS < 0,01). Ces scores se vérifient en lançant Lighthouse sur la démo.


## Pistes d'amélioration

Des limites assumées pour un projet de portfolio :

- **ACF Pro** : Répéteur et Galerie à la place des champs numérotés.
- **Traduction** : passer les textes du thème dans `__()` / `esc_html_e()` avec le domaine `verticite`.
- **Tests et qualité** : PHP_CodeSniffer avec les WordPress Coding Standards, et une CI GitHub Actions.
- **Thème bloc** : une version Full Site Editing (`theme.json`, patterns) pour comparer les deux approches.
