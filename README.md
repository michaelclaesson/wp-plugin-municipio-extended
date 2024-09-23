# Municipio Extended

A WordPress plugin that adds more features to Municipio

## Development

To build assets (CSS and JS) and watch for changes, run `pnpm dev`

To test the plugin in a local Municipio LTS project, read the section about
_Municipio Extended_ in that project’s README. Example:
https://github.com/whitespace-se/lts.malmstad.se/tree/main/wordpress#municipio-extended

## Git Workflow

1. Branch out from `main`.

1. Make commits as needed.

1. When ready for testing, merge into `dev`:
   ```bash
   git checkout dev
   git pull
   git merge feature/my-feature
   git push
   ```
   This will trigger a Github Action that will create a PR in the Malmstad repo. This repo acts as testing ground for the changes.

1. Close the PR in the Malmstad repo and wait for the changes to be deployed. You can follow the progress in the Github Actions tab in the Malmstad repo, and see when the deployment is finished by checking the status of the container in Portainer: https://portainer.falkenberg.municipio.w8e.se/.

1. When the changes are deployed, check https://malmstad.se to make sure they work as intended.

1. Create a pull request in this repo from your feature branch back to the `main` branch.

1. Link to `malmstad.se/<relevant page>` in the pull request.

1. Link to `malmstad.se/<relevant page>` in the Github issue card and move it to the
   testing column.

1. When testing is complete, close the pull request so the branch merges back
    into `main`. This will trigger a Github Action that will generate a PR for each of the Municipio site repos.

1. Close each of these new PR:s in turn, wait for deployment and make sure the sites works as intended.

## Migrations

This plugin provides a way to add code that does one-time updates to the
database. These updates are called "migrations". To create a migration, add a
new PHP file to the `migrations` directory.

To prevent PHP timeouts for long-running migrations, you can call the
`mx_migration_breakpoint` function in your code at points where a graceful halt
is possible. The file must be able to be run multiple times without issues.

Status of the migrations can be viewed under Tools -> Migrations in WP Admin. If
you have the Activity Log plugin activated, changes in status will also be
logged there.

Additional logging can be added manually in the migration’s file by calling
`mx_migration_progress_log`.

See /migrations/replace-mod-files1.php for an example.
