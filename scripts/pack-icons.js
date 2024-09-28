#!/usr/bin/env node

import { program } from 'commander';
import chalk from 'chalk';
import path from 'node:path';
import ora from 'ora';
import { mkdir, readdir, readFile, writeFile } from 'node:fs/promises';
import { gzip } from 'node:zlib';
import { promisify } from 'node:util';

const gzipAsync = promisify(gzip);

const variants = [
  '20px',
  '24px',
  '40px',
  '48px',
  'fill1_20px',
  'fill1_24px',
  'fill1_40px',
  'fill1_48px',
  'grad200_20px',
  'grad200_24px',
  'grad200_40px',
  'grad200_48px',
  'grad200fill1_20px',
  'grad200fill1_24px',
  'grad200fill1_40px',
  'grad200fill1_48px',
  'gradN25_20px',
  'gradN25_24px',
  'gradN25_40px',
  'gradN25_48px',
  'gradN25fill1_20px',
  'gradN25fill1_24px',
  'gradN25fill1_40px',
  'gradN25fill1_48px',
  'wght100_20px',
  'wght100_24px',
  'wght100_40px',
  'wght100_48px',
  'wght100fill1_20px',
  'wght100fill1_24px',
  'wght100fill1_40px',
  'wght100fill1_48px',
  'wght100grad200_20px',
  'wght100grad200_24px',
  'wght100grad200_40px',
  'wght100grad200_48px',
  'wght100grad200fill1_20px',
  'wght100grad200fill1_24px',
  'wght100grad200fill1_40px',
  'wght100grad200fill1_48px',
  'wght100gradN25_20px',
  'wght100gradN25_24px',
  'wght100gradN25_40px',
  'wght100gradN25_48px',
  'wght100gradN25fill1_20px',
  'wght100gradN25fill1_24px',
  'wght100gradN25fill1_40px',
  'wght100gradN25fill1_48px',
  'wght200_20px',
  'wght200_24px',
  'wght200_40px',
  'wght200_48px',
  'wght200fill1_20px',
  'wght200fill1_24px',
  'wght200fill1_40px',
  'wght200fill1_48px',
  'wght200grad200_20px',
  'wght200grad200_24px',
  'wght200grad200_40px',
  'wght200grad200_48px',
  'wght200grad200fill1_20px',
  'wght200grad200fill1_24px',
  'wght200grad200fill1_40px',
  'wght200grad200fill1_48px',
  'wght200gradN25_20px',
  'wght200gradN25_24px',
  'wght200gradN25_40px',
  'wght200gradN25_48px',
  'wght200gradN25fill1_20px',
  'wght200gradN25fill1_24px',
  'wght200gradN25fill1_40px',
  'wght200gradN25fill1_48px',
  'wght300_20px',
  'wght300_24px',
  'wght300_40px',
  'wght300_48px',
  'wght300fill1_20px',
  'wght300fill1_24px',
  'wght300fill1_40px',
  'wght300fill1_48px',
  'wght300grad200_20px',
  'wght300grad200_24px',
  'wght300grad200_40px',
  'wght300grad200_48px',
  'wght300grad200fill1_20px',
  'wght300grad200fill1_24px',
  'wght300grad200fill1_40px',
  'wght300grad200fill1_48px',
  'wght300gradN25_20px',
  'wght300gradN25_24px',
  'wght300gradN25_40px',
  'wght300gradN25_48px',
  'wght300gradN25fill1_20px',
  'wght300gradN25fill1_24px',
  'wght300gradN25fill1_40px',
  'wght300gradN25fill1_48px',
  'wght500_20px',
  'wght500_24px',
  'wght500_40px',
  'wght500_48px',
  'wght500fill1_20px',
  'wght500fill1_24px',
  'wght500fill1_40px',
  'wght500fill1_48px',
  'wght500grad200_20px',
  'wght500grad200_24px',
  'wght500grad200_40px',
  'wght500grad200_48px',
  'wght500grad200fill1_20px',
  'wght500grad200fill1_24px',
  'wght500grad200fill1_40px',
  'wght500grad200fill1_48px',
  'wght500gradN25_20px',
  'wght500gradN25_24px',
  'wght500gradN25_40px',
  'wght500gradN25_48px',
  'wght500gradN25fill1_20px',
  'wght500gradN25fill1_24px',
  'wght500gradN25fill1_40px',
  'wght500gradN25fill1_48px',
  'wght600_20px',
  'wght600_24px',
  'wght600_40px',
  'wght600_48px',
  'wght600fill1_20px',
  'wght600fill1_24px',
  'wght600fill1_40px',
  'wght600fill1_48px',
  'wght600grad200_20px',
  'wght600grad200_24px',
  'wght600grad200_40px',
  'wght600grad200_48px',
  'wght600grad200fill1_20px',
  'wght600grad200fill1_24px',
  'wght600grad200fill1_40px',
  'wght600grad200fill1_48px',
  'wght600gradN25_20px',
  'wght600gradN25_24px',
  'wght600gradN25_40px',
  'wght600gradN25_48px',
  'wght600gradN25fill1_20px',
  'wght600gradN25fill1_24px',
  'wght600gradN25fill1_40px',
  'wght600gradN25fill1_48px',
  'wght700_20px',
  'wght700_24px',
  'wght700_40px',
  'wght700_48px',
  'wght700fill1_20px',
  'wght700fill1_24px',
  'wght700fill1_40px',
  'wght700fill1_48px',
  'wght700grad200_20px',
  'wght700grad200_24px',
  'wght700grad200_40px',
  'wght700grad200_48px',
  'wght700grad200fill1_20px',
  'wght700grad200fill1_24px',
  'wght700grad200fill1_40px',
  'wght700grad200fill1_48px',
  'wght700gradN25_20px',
  'wght700gradN25_24px',
  'wght700gradN25_40px',
  'wght700gradN25_48px',
  'wght700gradN25fill1_20px',
  'wght700gradN25fill1_24px',
  'wght700gradN25fill1_40px',
  'wght700gradN25fill1_48px',
];

const styles = ['outlined', 'rounded', 'sharp'];

program
  .version('1.0.0')
  .description('My Node CLI')
  .argument('<source>', 'The repository to use as source')
  .action(async (source, options) => {
    source = path.resolve(source, 'symbols/web');
    const outDir = path.resolve('static/materialsymbols');
    console.log(chalk.green('Packaging icons from'), source);

    let icons = (await readdir(source, { withFileTypes: true }))
      .filter((dirent) => dirent.isDirectory())
      .map((dirent) => dirent.name);
    console.log(
      chalk.green(
        `Found ${chalk.white(icons.length)} icons${icons.length > 500 ? ', so this may take a while...' : ''}`,
      ),
    );
    const spinner = ora(`Packaging...`).start();

    await mkdir(outDir, { recursive: true });
    for (const style of styles) {
      for (const variant of variants) {
        let filename = `${style}_${variant}.xml`;

        let contents = (
          await Promise.all(
            icons.map(async (name) => {
              const iconPath = path.resolve(
                source,
                `${name}/materialsymbols${style}/${name}_${variant}.svg`,
              );
              return (`${name}:` + (await readFile(iconPath, 'utf8'))).replace(
                /\n+/g,
                ' ',
              );
            }),
          )
        ).join('\n');

        contents = await gzipAsync(contents);
        filename += '.gz';

        await writeFile(path.resolve(outDir, filename), contents);
      }
    }

    // await Promise.all(
    //   styles.map(async (style) => {
    //     console.log({ style });

    //     await Promise.all(
    //       variants.map(async (variant) => {
    //         const filename = `${style}_${variant}.xml`;
    //         console.log({ filename });

    //         const contents = (
    //           await Promise.all(
    //             icons.map(async (name) => {
    //               const iconPath = path.resolve(
    //                 source,
    //                 `${name}/materialsymbols${style}/${name}_${variant}.svg`,
    //               );
    //               return (
    //                 `${name}:` + (await readFile(iconPath, 'utf8'))
    //               ).replace(/\n+/g, ' ');
    //             }),
    //           )
    //         ).join('\n');
    //         console.log(path.resolve(outDir, filename));

    //         await writeFile(path.resolve(outDir, filename), contents);
    //       }),
    //     );
    //   }),
    // );

    // icons.forEach((name) => {
    //   const iconPath = path.resolve(source, name);
    //   const styleDirs = readdirSync(iconPath, { withFileTypes: true })
    //     .filter((dirent) => dirent.isDirectory())
    //     .map((dirent) => dirent.name);
    //   const variants = styleDirs.flatMap((styleDirname) => {
    //     const stylePath = path.resolve(iconPath, styleDirname);
    //     const style = styleDirname.replace(/^materialsymbols/, '');
    //     const files = readdirSync(stylePath, { withFileTypes: true }).map(
    //       (dirent) => {
    //         const filename = dirent.name;
    //         const contents = readFileSync(
    //           path.resolve(stylePath, filename),
    //           'utf8',
    //         );
    //         return {
    //           style,
    //           filename,
    //           contents,
    //         };
    //       },
    //     );
    //     return files;
    //   });
    //   const result = {
    //     filename: name + '.xml',
    //     contents: variants
    //       .map((variant) => {
    //         const { style, filename, contents } = variant;
    //         return `${style}/${filename.slice(name.length + 1).slice(0, -'.svg'.length)}:${contents.replace(/\n+/g, ' ')}`;
    //       })
    //       .join('\n'),
    //   };
    //   mkdirSync(outDir, { recursive: true });
    //   writeFileSync(path.resolve(outDir, result.filename), result.contents);
    // });
    spinner.succeed(chalk.green('Done!'));
  });

program.parse(process.argv);
