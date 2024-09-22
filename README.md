# Municipio Extended

A WordPress plugin that adds more features to Municipio

## Development

To build assets (CSS and JS) and watch for changes, run `pnpm dev`

To test the plugin in a local Municipio LTS project, read the section about
_Municipio Extended_ in that project’s README. Example:
https://github.com/whitespace-se/lts.malmstad.se/tree/main/wordpress#municipio-extended

## Git Workflow

1. Branch out from `main`.

2. Make commits as needed.

3. When ready for testing, merge into `dev`:

   ```bash
   git checkout dev
   git pull
   git merge feature/my-feature
   git push
   ```

4. Switch to the `lts.malmstad` repo:

   ```bash
   cd wordpress && composer update
   ```

5. Make a commit on `main` with a descriptive name, such as
   `feat: implement something`.

6. Push the commit:

   ```bash
   git push
   ```

7. Create a pull request.

8. Link to `lts.malmstad/relevant-sida` in the pull request.

9. Link to `lts.malmstad/relevant-sida` in the Trello card and move it to the
   testing column.

10. When testing is complete, close the pull request so the branch merges back
    into `main`.

11. Open each of the project repositories (currently `Arvidsjaur`, `Eslöv`, and
    `Höör`).

12. Run the following command:

    ```bash
    cd wordpress && composer update
    ```

13. Make a commit on `main` with a descriptive name, such as
    `feat: implement something`.

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
