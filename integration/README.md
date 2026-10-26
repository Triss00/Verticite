# Verticité — Intégration HTML / Sass

Deuxième étape du projet ([vue d'ensemble](../README.md)) : intégration fidèle de la [maquette Figma](https://www.figma.com/design/OfhepIM1zmZwcQTKQIJUHb/Verticit%C3%A9?node-id=31-7178&p=f) en **HTML5 + Sass + JavaScript natif**, sans framework CSS ni bundler.

L'objectif : un code **fidèle à la maquette, maintenable et accessible**, qui servira ensuite de base au [thème WordPress](../wordpress/README.md), [visible en ligne](https://verticite.tristanguyomard.fr).

---

## En bref

| | |
|---|---|
| **Pages** | Accueil, Nos réalisations (avec filtres), Fiche projet, L'agence |
| **Stack** | HTML5, Sass (Dart Sass, syntaxe `@use` / `@forward`), JavaScript natif (modules ES) |
| **Approche** | Mobile-first, 3 points de rupture (768 / 1024 / 1280 px) |
| **Architecture CSS** | Pattern 7-1 simplifié + BEM préfixé (`l-`, `c-`, `s-`, `u-`, `is-`) |
| **Design tokens** | Primitives → tokens sémantiques → variables CSS, vérifiés à la compilation |
| **Dépendances** | Aucune au chargement de la page (polices hébergées sur le site, pas de librairie JS) |

---

## Lancer le projet

Depuis la racine du dépôt :

```bash
npm install
```

Pour recompiler le CSS à chaque modification :

```bash
npm run watch:integration
```

Pour servir le site sur http://localhost:3000 :

```bash
npm run serve
```

Les chemins des pages sont absolus (`/public/...`) : il faut passer par un serveur local plutôt qu'ouvrir le fichier HTML directement. L'extension **Live Server** de VS Code, ouverte sur le dossier `integration/`, fonctionne aussi.

---

## Structure

```
integration/
├── index.html              # Accueil
├── projets.html            # Liste des projets (filtres)
├── detailed_project.html   # Fiche projet (galerie, fiche technique, pagination)
├── agence.html             # Agence (fondateurs, méthode, citation)
├── main.css                # CSS compilé (+ source map)
├── js/
│   ├── main.js             # Point d'entrée
│   └── modules/            # menu.js, slider.js
├── public/
│   ├── fonts/              # woff2 + licences OFL
│   ├── images/             # JPEG d'origine + optimized/ (WebP générés)
│   └── logo/
└── sass/
    ├── main.scss           # Point d'entrée : cinq @use, rien d'autre
    ├── abstracts/          # Tokens, fonctions, mixins (ne génèrent aucun CSS)
    ├── base/               # @font-face, reset, :root, typographie
    ├── layout/             # Conteneur, grille, section, en-tête, pied de page
    ├── components/         # Éléments réutilisables
    ├── sections/           # Blocs propres à une page
    └── utilities/          # Classes utilitaires
```

Chaque dossier a un `_index.scss` qui fait `@forward` de ses fichiers. Les partials chargent les outils avec `@use '../abstracts' as *;`. Aucune variable globale, aucun `@import` (déprécié).

---

## Design tokens : de Figma au CSS

Les couleurs sont organisées sur **trois niveaux**, comme dans le design system Figma (inspiré d'Untitled UI) :

```
_tokens.scss          _semantic.scss               base/_root.scss                  composants
─────────────         ─────────────────            ─────────────────                ────────────────────
brand-900: #2A1C12 →  text-primary: 'neutral-900' → --color-text-primary: var(…)  →  color(text-primary)
```

1. **Primitives** (`abstracts/_tokens.scss`) : les palettes brutes (`brand-50` à `brand-950`, `neutral-…`), les espacements, rayons, durées et points de rupture, **avec les mêmes noms que dans Figma**.
2. **Tokens sémantiques** (`abstracts/_semantic.scss`) : le *rôle* de chaque couleur (`text-primary`, `bg-secondary`, `border-strong`…).
3. **Variables CSS** (`base/_root.scss`) : tout est émis une seule fois dans `:root`.

Les composants n'utilisent **que** les tokens sémantiques, via des fonctions qui vérifient le nom :

```scss
.c-project-card {
  gap: space(2xl);
  color: color(text-primary);
  @include focus-ring(4px);

  &__title {
    @include text(text-md, medium);
  }
}
```

```scss
// abstracts/_functions.scss
@function color($token) {
  @if not map.has-key($semantic, $token) {
    @error 'Token de couleur inconnu : `#{$token}`. Voir abstracts/_semantic.scss.';
  }
  @return var(--color-#{$token});
}
```

Une faute de frappe (`color(text-primry)`) **bloque la compilation** avec un message clair au lieu de produire un CSS faux sans prévenir. Même principe pour `space()`, `radius()`, `z()`, `mq()` et `text()`.

### Les outils disponibles

| Fonction / mixin | Rôle |
|---|---|
| `rem($px)` | Conversion px → rem |
| `color($token)`, `space($key)`, `radius($key)`, `z($key)`, `duration($key)` | Accès vérifié aux tokens |
| `mq($bp)` / `mq-down($bp)` | Media queries mobile-first à partir des points de rupture nommés |
| `text($style, $weight)` / `text-responsive(…)` | Styles de texte issus de l'échelle typographique Figma |
| `container`, `grid` | Conteneur centré et grille 4 / 8 / 12 colonnes |
| `centered-columns($md, $lg)` | Liste en colonnes dont la dernière ligne, si elle est incomplète, reste centrée |
| `hover` | `:hover` seulement sur les appareils qui le gèrent (pas d'effet bloqué sur mobile) |
| `focus-ring`, `visually-hidden` | Accessibilité |
| `transition(...)` | Transitions désactivées si `prefers-reduced-motion` |

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

## Accessibilité

- Structure sémantique : `header`, `nav`, `main`, `footer`, `section` avec `aria-labelledby`, `hgroup`, `dl` pour la fiche technique, `figure` / `blockquote` pour la citation.
- Lien d'évitement « Aller au contenu ».
- Menu mobile : `aria-expanded`, `aria-controls`, fermeture avec Échap, focus géré.
- Carrousel : de vrais `<button>` avec `aria-label`, désactivés en bout de course ; défilement natif (`scroll-snap`) utilisable au clavier et au doigt.
- Page active signalée par `aria-current`, focus visible sur tous les éléments interactifs.
- Animations réduites si `prefers-reduced-motion`.

## JavaScript

Deux modules ES, sans dépendance, chargés avec `<script type="module">` :

- **`menu.js`** : ouverture et fermeture du menu mobile, synchronisation d'`aria-expanded`.
- **`slider.js`** : flèches précédent / suivant et barre de progression d'un carrousel CSS (`scroll-snap`). Le défilement reste natif : sans JS, le carrousel fonctionne toujours. Si tous les projets tiennent à l'écran, les commandes sont masquées (`is-static`).

Chaque module cherche ses accroches `[data-*]` dans la page et ne fait rien s'il ne les trouve pas. On ne cible pas les classes CSS, ce qui sépare le style du comportement.

---

## Qualité mesurée (Lighthouse)

| Page | Performance mobile / desktop | Accessibilité | Bonnes pratiques | SEO |
|---|:---:|:---:|:---:|:---:|
| Accueil | 99 / 100 | 100 | 100 | 100 |
| Nos réalisations | 98 / 100 | 100 | 100 | 100 |
| Fiche projet | 93 / 100 | 100 | 100 | 100 |
| L'agence | 100 / 100 | 100 | 100 | 100 |

<sub>Lighthouse 12.8, Chrome 156 headless, serveur local (`npm run serve`), profils mobile (réseau 4G lent simulé) et desktop par défaut.</sub>

### Ce que la mesure a permis de corriger

Une première mesure donnait **73 à 75 en performance mobile** et **96 en accessibilité**. Lighthouse pointait deux problèmes :

| Problème | Correction | Effet |
|---|---|---|
| Des JPEG de 0,6 à 1,2 Mo, servis en taille unique sur mobile comme sur desktop | `npm run images` (script [`scripts/optimize-images.mjs`](../scripts/optimize-images.mjs), avec [sharp](https://sharp.pixelplumbing.com/)) génère chaque image en **WebP** en 400, 800, 1200 et 1600 px. Les pages utilisent `srcset` et `sizes`, adaptés à chaque mise en page | Accueil : **4,3 Mo → 0,5 Mo**, affichage de l'image principale (LCP mobile) **15,8 s → 2,0 s** |
| Liens téléphone et e-mail du pied de page trop rapprochés pour un doigt | Zone tactile d'au moins 24 px de haut (`min-height`), comme le demande le critère WCAG 2.2 « 2.5.8 Taille de la cible » | Accessibilité **96 → 100** |

```html
<img class="c-project-card__image"
     src="/public/images/optimized/serre-800.webp"
     srcset="/public/images/optimized/serre-400.webp 400w,
             /public/images/optimized/serre-800.webp 800w,
             /public/images/optimized/serre-1200.webp 1200w"
     sizes="(min-width: 1024px) 384px, (min-width: 768px) 50vw, 100vw"
     alt="Allée plantée sous la verrière du Garage Suspendu"
     width="384" height="415" loading="lazy">
```

Le navigateur choisit lui-même la plus petite image suffisante pour la largeur d'affichage et la densité de l'écran. Les JPEG d'origine restent dans le dépôt comme source : le script les reconvertit à chaque ajout ou remplacement.

Le JavaScript ne bloque jamais l'affichage (Total Blocking Time : 0 ms) et la mise en page ne bouge presque pas pendant le chargement (CLS < 0,01), grâce aux attributs `width` / `height` sur toutes les images.

---

## Comment j'ai procédé

1. **Relever les fondations** dans Figma (variables de couleur, styles de texte, grilles, espacements) et les reporter dans `_tokens.scss` avec les mêmes noms.
2. **Associer les rôles** : chaque couleur utilisée dans la maquette devient un token sémantique.
3. **Découper la maquette** en layout / composants / sections avant d'écrire du HTML.
4. **Intégrer en mobile-first**, puis enrichir aux points de rupture `md` et `lg`.
5. **Vérifier** au clavier et à différentes largeurs, et comparer avec la maquette.

## Et ensuite ?

Cette intégration est la base du [thème WordPress](../wordpress/README.md), et **ses sources sont aussi celles du thème** : il n'y a qu'un seul Sass et un seul JavaScript pour les deux.

- **Sass** : le thème compile `integration/sass` (option `--load-path`) en ne changeant qu'une variable, le chemin des polices :
  ```scss
  // wordpress/themes/verticite/sass/main.scss
  @use 'base' with ($font-path: '../fonts');
  ```
- **JavaScript** : `npm run build:theme` copie `integration/js` dans le thème.

Une correction faite ici profite donc aux deux versions.