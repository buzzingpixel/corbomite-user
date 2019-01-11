# Corbomite User

Part of BuzzingPixel's Corbomite project.

This project provides user management for corbomite projects.

## Usage

When you require this into a Corbomite project, the CLI commands, schedule config, and dependency injection config will automatically be set up.

### Installation

Corbomite User needs to add a few database tables in order to function. In order to do this, it needs to create some migrations which then need to be run. Run the create-migrations command, which will place migration files in your Corobomite project.

```bash
php app user/create-migrations
```

After running that command, you'll need to run the migrations:

```bash
php app migrate/up
```

### Creating a user from the CLI

A CLI action is provided so you can add a user from the CLI.

```bash
php app user/create
```

### The API

Most things you'll need to do are available through the API:

```php
$userApi = Di::get(\corbomite\user\UserApi::class);
```

### Schedule

Make sure you're running the schedule command every minute on a cron job. Corbomite User has two commands that it schedules that need to run in order for everything to work right.

## License

Copyright 2019 BuzzingPixel, LLC

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at [http://www.apache.org/licenses/LICENSE-2.0](http://www.apache.org/licenses/LICENSE-2.0).

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
