# Business College Helsinki - WordPress Summer Project Environment

The project template for the WordPress summer course project with Heroku deployment and local Docker development.

Based on the [WP Heroku Docker Project](https://github.com/anttiviljami/wordpress-heroku-docker-project) by Viljami Kuosmanen.

## Features

- [x] Bedrock-like modern development environment
- [x] Local Heroku-like environment with Docker-compose
- [x] WP-CLI
- [x] Configurable Nginx
- [x] Travis CI pipeline you can run with Docker
- [x] PHP Codesniffer with nicer coding standards for WordPress
- [x] Terraform deployment for full WordPress stack provisioning to Heroku & AWS
- [x] Heroku PHP7 & Node 8 application runtime
- [x] Heroku Redis Cache
- [x] AWS S3 for media uploads
- [x] AWS RDS for MariaDB
- [x] Papertrail log management
- [x] NPM scripts for ease of use
- [x] Theme template with Foundation 6
- [x] Webpack asset building

## Installation

Clone this repo and source set up your environment inside the project root.

```bash
cp .env.sample .env
source .env
```

Start a shell inside Docker. It might take a moment for the images to download
and build. This is normal.

```bash
npm run shell
```

Now you can run `composer install` inside Docker

```bash
composer install && exit
```

You need to run this just once inside the project directory. Autoenv will take care of sourcing the .env file after this.

Install Composer and NPM packages:

```bash
npm install
```

Outside the docker shell, you can now start the main process

```bash
make docker-start
```

You can now navigate to [`http://localhost:8080`](http://localhost:8080) to
start working with your local WordPress installation.

## Development

To start developing the project in a local environment you need to first start the Docker for Windows and then run the docker environment inside the project folder. After it you should start the Webpack watch process.

```bash
# Start the Docker
make docker-start
# To exit from the logs type CTRL + C.
# Start watching for changes in SCSS and JS files.
make watch
```

## Use Makefile

When developing, you can use [Makefile](https://en.wikipedia.org/wiki/Make_(software)) for doing the following operations :

| Name          | Description                                  |
|---------------|----------------------------------------------|
| init          | Install Composre packages including WP       |
| composer-up   | Update PHP dependencies with Composer        |
| docker-start  | Create and start containers                  |
| docker-stop   | Stop and clear all services                  |
| logs          | Follow log output                            |
| rm-containers | Stop and remove all containers.              |
| shell         | Open a shell inside web container.           |
| watch         | Start the asset compilation watch process    |
| build         | Create production versions of assets.        |

## WP-CLI

You can run WP-CLI locally by starting the shell container. A list of basic commands can be found [here](https://developer.wordpress.org/cli/commands/). To start a shell container run:

```bash
make shell
```

To test the WP-CLI run:

```bash
wp db check
```

You should see an output of all the database tables created by WordPress.

## Setup the deployment

### AWS-CLI

To install AWS-CLI do the following in Debian.

```
sudo apt-get install python python-pip
sudo pip install awscli
```

Sign up for a 12 month free account in [AWS](https://portal.aws.amazon.com/billing/signup?refid=em_127222&redirect_url=https%3A%2F%2Faws.amazon.com%2Fregistration-confirmation#/start).

After installing the CLI [configure](https://docs.aws.amazon.com/cli/latest/userguide/cli-chap-configure.html) it.

For AWS, create an IAM user with Administrator rights and set the access credentials in the project's .env file.
```
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
```

### Heroku-CLI

Install Heroku-CLI in Debian:

```
curl https://cli-assets.heroku.com/install.sh | sh
```

Sign up for an account in [Heroku](https://signup.heroku.com/).

You can get your Heroku API key in the Heroku dashboard and set the credentials in the .env file.
```
HEROKU_API_KEY=
HEROKU_EMAIL=
```

### Terraform CLI

To install Terraform CLI do the following in Debian.

```
cd /tmp
wget https://releases.hashicorp.com/terraform/0.12.2/terraform_0.12.2_linux_amd64.zip
sudo apt-get install unzip
sudo unzip /tmp/terraform_0.12.2_linux_amd64.zip
sudo mv /tmp/terraform /usr/local/bin/terraform
sudo chmod +x /usr/local/bin/terraform
```

Test the Terraform CLI is working by running:

```
terraform -v
```
You should see the version of Terraform.

### Heroku Git configurations

Set Heroku-CLI to use SSH for Git:
```bash
heroku create --ssh-git
git config --global url.ssh://git@heroku.com/.insteadOf https://git.heroku.com/
```

Add your SSH key for your Heroku account:
```bash
heroku keys:add
```

### Add collaborators

Add your team members as collaborators for your app: https://devcenter.heroku.com/articles/collaborating#add-collaborators

## Deploy

Set the environment variables in the projects `.env` file.

The state of Terraform is managed in S3, so it should automatically sync any
changes from the remote backend. For this you'll need to manually set up an S3
bucket in the eu-west-1 region with the name `wp-terraform-backend-{yourprojectname}`. The suffix must make the bucket name unique in S3. For example the name could be `wp-terraform-backend-my-awesome-project`. Set the bucket name in `terraform.tf` on line 13.

To create the bucket go to: https://s3.console.aws.amazon.com/s3/home?region=eu-west-1

Set the project name in the .env file to the `TF_VAR_project_name` variable. Take a round-trip back to the project folder to apply environment variables with autoenv: `cd .. && cd name-of-your-project-directory`.

After creating the bucket, run the following commands:
```
terraform init
terraform apply
```

After this you can push to your newly created Heroku app's git URL to trigger
a Heroku deployment.

```
git remote add dev https://git.heroku.com/[my-project-name]-dev.git
git push dev
```

## WP-CLI in Heroku

To run wp-cli in a Heroku instance, just run a temporary dyno.

```bash
heroku run bash
```

Both environments have WP-CLI available as `wp`.
