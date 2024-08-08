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

1. Switch to the `lts.malmstad` repo:
    ```bash
    cd wordpress && composer update
    ```

1. Make a commit on `main` with a descriptive name, such as `feat: implement something`.

1. Push the commit:
    ```bash
    git push
    ```

1. Create a pull request.

1. Link to `lts.malmstad/relevant-sida` in the pull request.

1. Link to `lts.malmstad/relevant-sida` in the Trello card and move it to the testing column.

1. When testing is complete, close the pull request so the branch merges back into `main`.

1. Open each of the project repositories (currently `Arvidsjaur`, `Eslöv`, and `Höör`).

1. Run the following command:
    ```bash
    cd wordpress && composer update
    ```

1. Make a commit on `main` with a descriptive name, such as `feat: implement something`.
