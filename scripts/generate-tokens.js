// scripts/generate-tokens.js
import fs from 'fs/promises';
import path from 'path';
import { fileURLToPath } from 'url';

// ESM __dirname workaround
const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

// Paths
const themePath = path.resolve(__dirname, '../theme.json');
const cssOut = path.resolve(__dirname, '../src/styles/tokens.css');
const jsOut = path.resolve(__dirname, '../src/config/tokens.js');

// Helpers
const kebab = (str = '') =>
  str.replace(/([a-z0-9])([A-Z])/g, '$1-$2').replace(/\s+/g, '-').toLowerCase();

const safeSlug = (item, fallbackPrefix = '') =>
  item?.slug || kebab(item?.name || `${fallbackPrefix}-${Math.random().toString(36).slice(2, 6)}`);

const extractVars = (theme) => {
  const css = [];
  const js = [];

  const emit = (prefix, items = [], valueKey = 'color') => {
    if (!Array.isArray(items)) return;
    for (const item of items) {
      const key = safeSlug(item, prefix);
      const val = item?.[valueKey];
      if (!key || !val) continue;
      css.push(`  --${prefix}-${key}: ${val};`);
      js.push(`  "${prefix}-${key}": "${val}"`);
    }
  };

  const settings = theme.settings ?? {};
  const { color = {}, typography = {}, spacing = {}, borders = {}, shadows = {} } = settings;

  emit('color', color.palette || [], 'color');
  emit('font-size', typography.fontSizes || [], 'size');
  emit('line-height', typography.lineHeights || [], 'size');
  emit('letter-spacing', typography.letterSpacing || [], 'size');
  emit('spacing', spacing.spacingScale || [], 'size');
  emit('radius', borders.radius ? [{ slug: 'default', radius: borders.radius }] : [], 'radius');
  emit('shadow', shadows.shadow || [], 'shadow');

  return {
    css: `:root {\n${css.join('\n')}\n}\n`,
    js: `export const tokens = {\n${js.join(',\n')}\n};\n`,
  };
};

// Generate and write
const themeRaw = await fs.readFile(themePath, 'utf8');
const theme = JSON.parse(themeRaw);
const { css, js } = extractVars(theme);

await fs.mkdir(path.dirname(cssOut), { recursive: true });
await fs.mkdir(path.dirname(jsOut), { recursive: true });

await fs.writeFile(cssOut, css, 'utf8');
await fs.writeFile(jsOut, js, 'utf8');

console.log('✅ Design tokens generated from theme.json');
