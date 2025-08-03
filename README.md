# Not Another Theme

A modern WordPress starter theme for developers who want full control, fast performance, and no page builder bloat.

## Features

- ⚡ Built with [Vite](https://vitejs.dev/) for blazing-fast development
- 🎨 Uses [Open Props](https://open-props.style/) for scalable design tokens
- 🧩 Includes [Shoelace](https://shoelace.style/) web components
- 🔒 Self-hosted assets, no CDN dependencies
- 🧼 Zero-FOUT Shoelace integration with branded loader and toggleable fallback
- 🧱 Based on `blockbase` for full `theme.json` support

## Getting Started

### 1. Install dependencies

```
npm install
```

### 2. Start dev server

```
npm run dev
```

### 3. Build for production

```
npm run build
```

### 4. Enable theme in WordPress

Upload or symlink the theme into your `wp-content/themes` folder and activate it.

## Configuration

You can disable the fallback loader by adding this filter:

```php
add_filter('nat_disable_sloader', '__return_true');
```

## License

GPLv2 or later. See `style.css` header for details.
