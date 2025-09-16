import { readFileSync, existsSync } from 'node:fs';
import { resolve, dirname } from 'node:path';

// Recursively traverse the directory structure to find the closest parent folder with a wp-cli.yml file.
let wpPath: string | null = __dirname;
while (wpPath !== '/') {
  if (existsSync(resolve(wpPath, 'wp-cli.yml'))) {
    break;
  }
  wpPath = dirname(wpPath);
}
if (wpPath === '/') {
  wpPath = null;
}

let configPath;
let config: {
  vite?: { build?: { outDir?: string } };
  tailwind: { content?: string[] };
} | null = null;

if (wpPath) {
  console.info(
    `Found wp-cli.yml in ${wpPath}, loading config.json from there.`,
  );
  configPath = resolve(wpPath, './config.json');
  try {
    const configFile = readFileSync(configPath, 'utf-8');
    config = JSON.parse(configFile);
  } catch (error) {
    console.warn(`Could not read config file at ${configPath}.`);
  }
} else {
  console.info(
    'No wp-cli.yml found, using default Tailwind CSS configuration.',
  );
}
const isInWpContext = !!wpPath;

export { isInWpContext, wpPath, configPath, config };
