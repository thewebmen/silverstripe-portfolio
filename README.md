# silverstripe-portfolio

## Module development
We advise to use [Docker](https://docker.com)/[Docker compose](https://docs.docker.com/compose/) for development.\
We also use [Make](https://www.gnu.org/software/make/) to simplify some commands into shortcuts.

Our development container contains some build-in tools like `PHPCSFixer` and `yarn`.

### Getting development container up
`make build` to build the Docker container and then run detached.\
If you want to only get the container up, you can simply type `make up`.

You can SSH into the container using `make sh`.

### Front-end
Webpack and yarn are used to compile front-end assets.

If you use the Docker environment, you can just run `make watch` to watch for changes or run `make build` to build assets (minified and production ready!)

### All make commands
You can run `make help` to get a list with all available `make` commands.