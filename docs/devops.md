# Hosting setup

The hosting setup uses the same core components as the previous hosting setup,
meaning EC2 and RDS. The new component is that we use Elastic Beanstalk for
server provisioning and management. For load balancing and SSL termination
an elastic load balancer (ELB) is used. SSL certificates are provisioned with
AWS Certificate Manager (ACM).

Can be controlled either from terminal (see `Deployment flow` section below) or
from the [AWS web console](https://eu-central-1.console.aws.amazon.com/elasticbeanstalk/home?region=eu-central-1#/application/overview?applicationName=wign).

The original setup of using apache as the application web server is kept. To
mitigate the most obvious security attacks the apache plugin
[modsecurity](https://modsecurity.org/) is used. Currently is has rules for sql
injection and xss attacks.

The setup uses docker for both development and production, the same container
image definition is used, to keep the two environments as similar as possible.

The setup scales horizontally by adding more servers when certain thresholds are
reached, these can be configured
[here](https://eu-central-1.console.aws.amazon.com/elasticbeanstalk/home?region=eu-central-1#/environment/configuration?applicationName=wign&environmentId=e-q22eae3cz4&edit=scaling).
Deployment (see below) happens by spinning up a new server and awaiting it to
pass health checks before destroying old servers. The update strategy used is
"RollingWithAdditionalBatch" which for a one server setup (as this is) means
that it will spin up a new server when deployment is done and wait for that to
be come ready before deploying. This means that the deployments take longer than
e.g. having two servers running all the time and updating them one by one. The
cost is lower, but having to servers constantly would make for a more resillient
setup. If this is desired the update strategy should be changed to rolling
[here](https://eu-central-1.console.aws.amazon.com/elasticbeanstalk/home?region=eu-central-1#/environment/configuration?applicationName=wign&environmentId=e-q22eae3cz4&edit=updates_deployments)
and the "Minimum instance count" should be set to "2"
[here](https://eu-central-1.console.aws.amazon.com/elasticbeanstalk/home?region=eu-central-1#/environment/configuration?applicationName=wign&environmentId=e-q22eae3cz4&edit=scaling)

# Development flow

The intended development flow requires two prerequisites: [Docker
compose](https://docs.docker.com/compose/) and `make` (found in all major
distro's package manager). Docker compose manages the needed images for the
application to run (e.g. the application itself and a database) as well as some
handful utility containers for running migrations and a shell for interaction
with the hosting environment (AWS). Make is a kind of layer around
docker-compose which is tasked with building the container and ensuring that it
is build before running `up`. The reason that make is needed in this step is
to keep file ownership belonging to the user developing on the application. It
seems that apache won't serve the files if the files do not belong to the
`www-data` user inside the container. So to account for the image is build with
arguments to change the `www-data` users id to match the developers id on his
linux system. The `Makefile` also has a target for keeping track of whether the
image is previously built to avoid rebuilding excessively while developing. To
force a rebuild delete the file `.built`.

To develop locally it should suffice to run `make up`.

# Deployment flow

Deploying requires the use of AWS tools, which can be installed locally. For
convenience a container with the tools is available using the the command `make
aws-shell`. This requires two types of credentials which should be distributed
outside this repo: `aws-credentials.txt`, with aws access key id and secret key,
and .ssh keys in `.ssh` for accessing the actual servers.

When inside the aws shell the following commands are useful:
- `eb deploy` to deploy all `git commit`ted changes
- `eb deploy --staged` to deploy all `git commit`ted and `git add`ed changes
- `eb ssh` to ssh into servers
- `eb logs` to view logs, can also be accessed from: [aws
console](https://eu-central-1.console.aws.amazon.com/elasticbeanstalk/home?region=eu-central-1#/environment/logs?applicationName=wign&environmentId=e-q22eae3cz4)


# Implemented code modifications

- Stylesheets in `layout/style.blade.php` now uses `//` instead of `http` to
load with whatever scheme the page is loaded with.

- Recreated `GenerateUrl` in `app/Library/helpers.php` as it seemed to be used
in `resources/views/` and `app/Http/Controllers/SearchController.php`. Deleted
the `makeUnderstrokes` method as it seems to do the same and doesn't seemed to
be used.

- Htaccess file does the http -> https redirect (now stored in
`apache/prod.htaccess`)

- Added a migration for `blacklist` because it seemed to be missing.
