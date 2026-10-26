// Génère les images de l'intégration en WebP, en plusieurs largeurs,
// pour les attributs srcset des pages HTML.
//
//   integration/public/images/serre.jpg
//   → integration/public/images/optimized/serre-400.webp, serre-800.webp…
//
// Les JPEG d'origine restent la source : on relance `npm run images`
// après avoir ajouté ou remplacé une image.

import { mkdir, readdir } from 'node:fs/promises';
import path from 'node:path';
import sharp from 'sharp';

const SOURCE_DIR = 'integration/public/images';
const OUTPUT_DIR = path.join(SOURCE_DIR, 'optimized');
const WIDTHS = [400, 800, 1200, 1600];
const QUALITY = 75;

await mkdir(OUTPUT_DIR, { recursive: true });

const files = (await readdir(SOURCE_DIR)).filter((file) => /\.(jpe?g|png)$/i.test(file));

for (const file of files) {
  const name = path.parse(file).name;
  const image = sharp(path.join(SOURCE_DIR, file));
  const { width: originalWidth } = await image.metadata();

  // On n'agrandit jamais une image : les largeurs supérieures à l'original sont ignorées.
  const widths = WIDTHS.filter((width) => width <= originalWidth);

  for (const width of widths) {
    const output = path.join(OUTPUT_DIR, `${name}-${width}.webp`);
    const { size } = await image.clone().resize({ width }).webp({ quality: QUALITY }).toFile(output);
    console.log(`${output}  ${Math.round(size / 1024)} Ko`);
  }
}
