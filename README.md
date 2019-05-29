# YAWIK JobsSitemap

Generate sitemap for all active jobs.

- [Overview](#overview)
- [Installation](#installation)
- [Usage](#usage)
  - [Configuration](#configuration)
- [License](#license)

## Overview

- generate sitemap manually
- Automatically update sitemap when a job status changes
- ping search engines after update

## Installation

To use as yawik module, you need to require it in your YAWIK project:
``` sh
composer require yawik/jobs-sitemap
```

To contribute, you need to clone the repository:

```sh
git clone git@github.com:yawik/jobs-sitemap.git
cd jobs-sitemap
composer install
```

Start a local webserver with:
``` sh
php -S localhost:8000 -t test/sandbox/public test/sandbox/public/router.php
```
or
``` sh
composer serve
```

you should be able to access your yawik at http://localhost:8000

## Usage

### Configuration

## License

[MIT](./LICENSE)
