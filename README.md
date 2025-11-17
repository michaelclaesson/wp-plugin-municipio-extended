# Municipio Extended

A WordPress plugin that adds more features to Municipio

## Development

To build assets (CSS and JS) and watch for changes, run `pnpm dev`

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
