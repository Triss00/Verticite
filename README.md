# Verticité — Intégration HTML / CSS

Intégration du site vitrine de **Verticité**, une agence d'architecture lyonnaise (fictive) qui transforme garages, usines et entrepôts en lieux de vie lumineux et végétalisés.

Projet de portfolio : **maquette Figma → HTML + Sass**, sans framework CSS ni outil de build lourd. Le but est de montrer une intégration fidèle à la maquette, maintenable et accessible.

---

## En bref

| | |
|---|---|
| **Stack** | HTML5, Sass (Dart Sass 1.105, syntaxe `@use` / `@forward`), JavaScript natif (modules ES) |
| **Source** | Maquette Figma que j'ai réalisé |
| **Pages** | Accueil, Projets, Fiche projet, Agence |
| **Approche** | Mobile-first, 3 points de rupture (768 / 1024 / 1280 px) |
| **Architecture CSS** | Pattern 7-1 simplifié + BEM préfixé (`l-`, `c-`, `s-`, `u-`) |
| **Design tokens** | Primitives → tokens sémantiques → variables CSS, contrôlés à la compilation |
| **Dépendances** | Aucune à l'exécution (polices auto-hébergées, pas de librairie JS) |

---

## Structure du projet

```
.
├── index.html              # Accueil
├── projets.html            # Liste des projets (filtres)
├── detailed_project.html   # Fiche projet (galerie, fiche technique, pagination)
├── agence.html             # Agence (fondateurs, méthode, citation)
├── main.css                # CSS compilé
├── js/
│   ├── main.js             # Point d'entrée
│   └── modules/            # menu.js, slider.js
├── public/
│   ├── fonts/              # woff2
│   ├── images/
│   └── logo/
└── sass/
    ├── main.scss           # Point d'entrée Sass
    ├── abstracts/          # Tokens, fonctions, mixins (ne génèrent aucun CSS)
    ├── base/               # @font-face, reset, :root, typographie
    ├── layout/             # Conteneur, grille, section, header, footer
    ├── components/         # Éléments réutilisables
    ├── sections/           # Blocs propres à une page
    └── utilities/          # Classes utilitaires
```

Chaque dossier a un `_index.scss` qui fait `@forward` de ses fichiers. `main.scss` ne fait que cinq `@use`. Les fichiers chargent les abstracts avec `@use '../abstracts' as *;`.

---

## Conventions de nommage

Méthodologie **BEM**, avec un préfixe qui indique le rôle de chaque classe :

| Préfixe | Rôle | Exemples |
|---|---|---|
| `l-` | **Layout** : structure, aucun style visuel | `l-container`, `l-grid`, `l-section`, `l-header` |
| `c-` | **Component** : élément réutilisable partout | `c-button`, `c-project-card`, `c-slider`, `c-fact-sheet` |
| `s-` | **Section** : bloc propre à une page | `s-hero`, `s-key-numbers`, `s-mosaic`, `s-gallery` |
| `u-` | **Utility** : une seule responsabilité, peut forcer | `u-visually-hidden`, `u-skip-link` |
| `is-` | **État** piloté par le JS | `is-open` |


On comprend le rôle d'une classe en lisant le HTML, sans ouvrir le CSS.

---

## Lancer le projet

### Compiler le Sass

Avec le binaire Dart Sass fourni (macOS) :

```bash
./dart-sass/sass --watch sass/main.scss main.css
```

Ou avec la version npm :

```bash
npx sass --watch sass/main.scss main.css
```

### Ouvrir le site

Il faut passer par un serveur local plutôt qu'ouvrir le fichier directement.

```bash
npx serve .
```

ou l'extension **Live Server** de VS Code.

---

## Comment j'ai procédé

1. **Relever les fondations** dans Figma (variables de couleur, styles de texte, grilles, espacements) et les reporter dans `_tokens.scss` avec les mêmes noms. [Voir la maquette Figma ici](https://www.figma.com/design/OfhepIM1zmZwcQTKQIJUHb/Verticit%C3%A9?node-id=31-7178&p=f)
2. **Associer les rôles** : chaque couleur utilisée dans la maquette devient un token sémantique. Les écarts avec Untitled UI sont commentés (`// Ajout Verticité`).
3. **Découper la maquette** en layout / composants / sections avant d'écrire du HTML.
4. **Intégrer en mobile-first**, puis enrichir aux points de rupture `md` et `lg`.
5. **Vérifier** au clavier et à différentes largeurs, et comparer avec la maquette.
