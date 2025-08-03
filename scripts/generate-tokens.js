// scripts/generate-tokens.js
import fs from 'fs/promises';
import path from 'path';
import { fileURLToPath } from 'url';

// Setup ESM __dirname
const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

// Paths
const themePath = path.resolve(__dirname, '../theme.json');
const cssOut = path.resolve(__dirname, '../src/styles/tokens.css');
const jsOut = path.resolve(__dirname, '../src/config/tokens.js');

// Load and parse theme.json
const themeRaw = await fs.readFile(themePath, 'utf8');
const theme = JSON.parse(themeRaw);

// Extract variables from theme.json
const getVariables = () => {
  const css = [];
  const js = [];

  const extract = (prefix, items = [], valueKey = 'color') => {
    for (const item of items) {
      const key = item.slug || item.name?.toLowerCase().replace(/\s+/g, '-');
      const val = item[valueKey];
      if (!key || !val) continue;
      css.push(`  --${prefix}-${key}: ${val};`);
      js.push(`  "${prefix}-${key}": "${val}"`);
    }
  };

  const settings = theme.settings ?? {};

  extract('color', settings.color?.palette, 'color');
  extract('font-size', settings.typography?.fontSizes, 'size');
  extract('line-height', settings.typography?.lineHeights, 'size');
  extract('spacing', settings.spacing?.spacingScale, 'size');
  extract('radius', settings.borders?.radius, 'radius');

  return {
    css: `:root {\n${css.join('\n')}\n}\n`,
    js: `export const tokens = {\n${js.join(',\n')}\n};\n`
  };
};

// Generate and write files
const { css, js } = getVariables();

await fs.mkdir(path.dirname(cssOut), { recursive: true });
await fs.mkdir(path.dirname(jsOut), { recursive: true });

await fs.writeFile(cssOut, css, 'utf8');
await fs.writeFile(jsOut, js, 'utf8');

console.log('✅ Design tokens generated from theme.json');
